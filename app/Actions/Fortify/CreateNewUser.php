<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]), function (User $user) use ($input) {
                // Check if the user has pending team invitations
                $invitations = Jetstream::teamInvitationModel()::where('email', $input['email'])->get();
                
                if ($invitations->isNotEmpty()) {
                    // If user has pending invitations, accept them and skip personal team creation
                    $this->acceptPendingInvitations($user, $invitations);
                } else {
                    // Otherwise, create a personal team
                    $this->createTeam($user);
                }
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }

    /**
     * Accept all pending team invitations for the user.
     */
    protected function acceptPendingInvitations(User $user, Collection $invitations): void
    {
        foreach ($invitations as $invitation) {
            // Add the user to the team
            $invitation->team->users()->attach($user, ['role' => $invitation->role]);
            
            // Delete the invitation
            $invitation->delete();
        }
        
        // Set the user's current team to the first team they were invited to
        if ($invitations->isNotEmpty()) {
            $user->forceFill([
                'current_team_id' => $invitations->first()->team_id,
            ])->save();
        }
    }
}
