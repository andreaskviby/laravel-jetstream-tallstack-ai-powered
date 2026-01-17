<?php

namespace Tests\Feature;

use App\Actions\Fortify\CreateNewUser;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class NewUserTeamInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_with_invitation_skips_personal_team_creation(): void
    {
        if (! Features::sendsTeamInvitations()) {
            $this->markTestSkipped('Team invitations not enabled.');
        }

        // Create an existing user with a team
        $teamOwner = User::factory()->withPersonalTeam()->create();
        $team = $teamOwner->currentTeam;

        // Create a team invitation for a new user
        $invitationEmail = 'newuser@example.com';
        $team->teamInvitations()->create([
            'email' => $invitationEmail,
            'role' => 'editor',
        ]);

        // Register the new user
        $action = new CreateNewUser();
        $newUser = $action->create([
            'name' => 'New User',
            'email' => $invitationEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => true,
        ]);

        // Assert that the new user does NOT have a personal team (owned teams count should be 0)
        $this->assertCount(0, $newUser->ownedTeams);

        // Assert that the new user is a member of the invited team
        $this->assertTrue($newUser->belongsToTeam($team));

        // Assert that the user's current team is the invited team
        $this->assertEquals($team->id, $newUser->current_team_id);

        // Assert that the user has the correct role
        $this->assertEquals('editor', $newUser->teamRole($team)->key);

        // Assert that the invitation has been deleted
        $this->assertCount(0, TeamInvitation::where('email', $invitationEmail)->get());
    }

    public function test_new_user_without_invitation_creates_personal_team(): void
    {
        // Register a new user without any pending invitations
        $action = new CreateNewUser();
        $newUser = $action->create([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => true,
        ]);

        // Assert that the new user has a personal team
        $this->assertCount(1, $newUser->ownedTeams);
        $this->assertTrue($newUser->currentTeam->personal_team);
    }

    public function test_new_user_with_multiple_invitations_joins_all_teams(): void
    {
        if (! Features::sendsTeamInvitations()) {
            $this->markTestSkipped('Team invitations not enabled.');
        }

        // Create two users with teams
        $teamOwner1 = User::factory()->withPersonalTeam()->create();
        $team1 = $teamOwner1->currentTeam;

        $teamOwner2 = User::factory()->withPersonalTeam()->create();
        $team2 = $teamOwner2->currentTeam;

        // Create team invitations for a new user from both teams
        $invitationEmail = 'newuser@example.com';
        $team1->teamInvitations()->create([
            'email' => $invitationEmail,
            'role' => 'editor',
        ]);
        $team2->teamInvitations()->create([
            'email' => $invitationEmail,
            'role' => 'admin',
        ]);

        // Register the new user
        $action = new CreateNewUser();
        $newUser = $action->create([
            'name' => 'New User',
            'email' => $invitationEmail,
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => true,
        ]);

        // Assert that the new user does NOT have a personal team
        $this->assertCount(0, $newUser->ownedTeams);

        // Assert that the new user is a member of both teams
        $this->assertTrue($newUser->belongsToTeam($team1));
        $this->assertTrue($newUser->belongsToTeam($team2));

        // Assert that the user's current team is the first invited team
        $this->assertEquals($team1->id, $newUser->current_team_id);

        // Assert that all invitations have been deleted
        $this->assertCount(0, TeamInvitation::where('email', $invitationEmail)->get());
    }
}
