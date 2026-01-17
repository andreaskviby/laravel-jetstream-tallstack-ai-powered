# Team Management Skills

## Overview
Skills for implementing, extending, and maintaining the team management system in this Laravel Jetstream TALL Stack project.

## Core Skills

### 1. Create Team

**Purpose:** Create a new team with proper owner assignment.

**Implementation:**
```php
public function createTeam(User $user, array $data): Team
{
    // Validate team name uniqueness for user
    if ($user->ownedTeams()->where('name', $data['name'])->exists()) {
        throw ValidationException::withMessages([
            'name' => 'You already have a team with this name.',
        ]);
    }
    
    DB::beginTransaction();
    
    try {
        $team = $user->ownedTeams()->create([
            'name' => $data['name'],
            'personal_team' => $data['personal_team'] ?? false,
        ]);
        
        // Attach owner as admin
        $team->users()->attach($user, ['role' => 'admin']);
        
        // Set as current team if first team
        if ($user->teams()->count() === 1) {
            $user->forceFill([
                'current_team_id' => $team->id,
            ])->save();
        }
        
        DB::commit();
        
        Log::info('Team created', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'team_name' => $team->name,
        ]);
        
        return $team;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

**Key Points:**
- Use database transactions for data consistency
- Validate team name uniqueness per owner
- Automatically attach owner with admin role
- Set as current team if it's user's first team
- Log team creation for audit

### 2. Invite Team Member

**Purpose:** Send invitation to join team with specified role.

**Implementation:**
```php
public function inviteTeamMember(Team $team, string $email, string $role = 'member'): TeamInvitation
{
    // Validate permissions
    if (!Gate::forUser(auth()->user())->check('addTeamMember', $team)) {
        throw new AuthorizationException('You do not have permission to add team members.');
    }
    
    // Check if user already exists
    $existingUser = User::where('email', $email)->first();
    
    if ($existingUser && $team->hasUser($existingUser)) {
        throw ValidationException::withMessages([
            'email' => 'This user is already a team member.',
        ]);
    }
    
    // Check for existing pending invitation
    $existingInvitation = $team->teamInvitations()
        ->where('email', $email)
        ->first();
    
    if ($existingInvitation) {
        // Resend invitation
        Mail::to($email)->send(new TeamInvitationMail($existingInvitation));
        
        return $existingInvitation;
    }
    
    // Create new invitation
    $invitation = $team->teamInvitations()->create([
        'email' => $email,
        'role' => $role,
    ]);
    
    // Send invitation email
    Mail::to($email)->send(new TeamInvitationMail($invitation));
    
    Log::info('Team invitation sent', [
        'team_id' => $team->id,
        'email' => $email,
        'role' => $role,
    ]);
    
    return $invitation;
}
```

**Invitation Email Template:**
```blade
@component('mail::message')
# Team Invitation

Hello!

You have been invited to join the **{{ $invitation->team->name }}** team on {{ config('app.name') }}.

@component('mail::button', ['url' => route('team-invitations.accept', $invitation)])
Accept Invitation
@endcomponent

**Role:** {{ ucfirst($invitation->role) }}

**About the team:**
- Owner: {{ $invitation->team->owner->name }}
- Members: {{ $invitation->team->users->count() }}

If you did not expect this invitation, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
```

### 3. Accept Team Invitation

**Purpose:** Process team invitation acceptance.

**Implementation:**
```php
public function acceptInvitation(TeamInvitation $invitation, User $user): void
{
    // Verify email matches
    if ($invitation->email !== $user->email) {
        throw new AuthorizationException('This invitation is not for your email address.');
    }
    
    DB::beginTransaction();
    
    try {
        $team = $invitation->team;
        
        // Add user to team
        if (!$team->hasUser($user)) {
            $team->users()->attach($user, ['role' => $invitation->role]);
        }
        
        // Delete invitation
        $invitation->delete();
        
        // Set as current team if user has no current team
        if (!$user->current_team_id) {
            $user->forceFill([
                'current_team_id' => $team->id,
            ])->save();
        }
        
        DB::commit();
        
        Log::info('Team invitation accepted', [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);
        
        // Notify team owner
        $team->owner->notify(new TeamMemberJoinedNotification($team, $user));
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

### 4. Remove Team Member

**Purpose:** Remove member from team with proper permission checks.

**Implementation:**
```php
public function removeTeamMember(Team $team, User $userToRemove): void
{
    // Validate permissions
    if (!Gate::forUser(auth()->user())->check('removeTeamMember', [$team, $userToRemove])) {
        throw new AuthorizationException('You do not have permission to remove team members.');
    }
    
    // Cannot remove team owner
    if ($userToRemove->id === $team->user_id) {
        throw ValidationException::withMessages([
            'member' => 'You cannot remove the team owner.',
        ]);
    }
    
    DB::beginTransaction();
    
    try {
        $team->users()->detach($userToRemove);
        
        // If removed user's current team is this team, switch to personal team
        if ($userToRemove->current_team_id === $team->id) {
            $personalTeam = $userToRemove->ownedTeams()
                ->where('personal_team', true)
                ->first();
            
            $userToRemove->forceFill([
                'current_team_id' => $personalTeam?->id,
            ])->save();
        }
        
        DB::commit();
        
        Log::info('Team member removed', [
            'team_id' => $team->id,
            'removed_user_id' => $userToRemove->id,
            'removed_by' => auth()->id(),
        ]);
        
        // Notify removed user
        $userToRemove->notify(new RemovedFromTeamNotification($team));
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

### 5. Update Team Member Role

**Purpose:** Change team member's role.

**Implementation:**
```php
public function updateTeamMemberRole(Team $team, User $user, string $newRole): void
{
    // Validate permissions
    if (!Gate::forUser(auth()->user())->check('updateTeamMember', $team)) {
        throw new AuthorizationException('You do not have permission to update team members.');
    }
    
    // Validate role
    $validRoles = ['admin', 'member', 'editor', 'viewer'];
    if (!in_array($newRole, $validRoles)) {
        throw ValidationException::withMessages([
            'role' => 'Invalid role specified.',
        ]);
    }
    
    // Cannot change owner's role
    if ($user->id === $team->user_id) {
        throw ValidationException::withMessages([
            'role' => 'Cannot change team owner role.',
        ]);
    }
    
    $team->users()->updateExistingPivot($user->id, [
        'role' => $newRole,
        'updated_at' => now(),
    ]);
    
    Log::info('Team member role updated', [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'new_role' => $newRole,
        'updated_by' => auth()->id(),
    ]);
    
    // Notify user of role change
    $user->notify(new RoleChangedNotification($team, $newRole));
}
```

### 6. Switch Current Team

**Purpose:** Allow user to switch their active team context.

**Implementation:**
```php
public function switchTeam(User $user, Team $team): void
{
    // Verify user is member of team
    if (!$user->belongsToTeam($team)) {
        throw new AuthorizationException('You do not belong to this team.');
    }
    
    $user->forceFill([
        'current_team_id' => $team->id,
    ])->save();
    
    Log::info('User switched team', [
        'user_id' => $user->id,
        'team_id' => $team->id,
    ]);
}
```

### 7. Delete Team

**Purpose:** Safely delete team with all dependencies.

**Implementation:**
```php
public function deleteTeam(Team $team): void
{
    // Validate permissions
    if (!Gate::forUser(auth()->user())->check('delete', $team)) {
        throw new AuthorizationException('You do not have permission to delete this team.');
    }
    
    // Prevent deletion of personal team
    if ($team->personal_team) {
        throw ValidationException::withMessages([
            'team' => 'Personal teams cannot be deleted.',
        ]);
    }
    
    DB::beginTransaction();
    
    try {
        // Get all team members before deletion
        $members = $team->users()->get();
        
        // Update users' current team if needed
        foreach ($members as $member) {
            if ($member->current_team_id === $team->id) {
                $personalTeam = $member->ownedTeams()
                    ->where('personal_team', true)
                    ->first();
                
                $member->forceFill([
                    'current_team_id' => $personalTeam?->id,
                ])->save();
            }
        }
        
        // Delete team invitations
        $team->teamInvitations()->delete();
        
        // Detach all users
        $team->users()->detach();
        
        // Delete team
        $team->delete();
        
        DB::commit();
        
        Log::info('Team deleted', [
            'team_id' => $team->id,
            'team_name' => $team->name,
            'deleted_by' => auth()->id(),
        ]);
        
        // Notify all previous members
        foreach ($members->where('id', '!=', $team->user_id) as $member) {
            $member->notify(new TeamDeletedNotification($team));
        }
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}
```

## Advanced Skills

### 8. Team Statistics

**Purpose:** Get team usage and activity statistics.

**Implementation:**
```php
public function getTeamStats(Team $team): array
{
    return [
        'total_members' => $team->users()->count(),
        'roles_breakdown' => $team->users()
            ->select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray(),
        'pending_invitations' => $team->teamInvitations()->count(),
        'created_at' => $team->created_at,
        'days_active' => $team->created_at->diffInDays(now()),
    ];
}
```

### 9. Team Activity Log

**Purpose:** Track team-related activities.

**Implementation:**
```php
public function logTeamActivity(Team $team, string $action, User $user, array $metadata = []): void
{
    DB::table('team_activities')->insert([
        'team_id' => $team->id,
        'user_id' => $user->id,
        'action' => $action,
        'metadata' => json_encode($metadata),
        'ip_address' => request()->ip(),
        'created_at' => now(),
    ]);
}

public function getTeamActivities(Team $team, int $limit = 50): Collection
{
    return DB::table('team_activities')
        ->where('team_id', $team->id)
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();
}
```

### 10. Bulk Team Operations

**Purpose:** Perform operations on multiple team members at once.

**Implementation:**
```php
public function bulkInviteMembers(Team $team, array $emails, string $role = 'member'): array
{
    $results = [
        'success' => [],
        'failed' => [],
    ];
    
    foreach ($emails as $email) {
        try {
            $invitation = $this->inviteTeamMember($team, $email, $role);
            $results['success'][] = [
                'email' => $email,
                'invitation_id' => $invitation->id,
            ];
        } catch (\Exception $e) {
            $results['failed'][] = [
                'email' => $email,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    return $results;
}
```

## Testing Skills

### Test Team Creation
```php
public function test_user_can_create_team(): void
{
    $user = User::factory()->create();
    
    $team = $this->createTeam($user, ['name' => 'Test Team']);
    
    $this->assertDatabaseHas('teams', [
        'id' => $team->id,
        'user_id' => $user->id,
        'name' => 'Test Team',
    ]);
    
    $this->assertTrue($team->hasUser($user));
}
```

### Test Team Invitation
```php
public function test_can_invite_team_member(): void
{
    $owner = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $owner->id]);
    
    Mail::fake();
    
    $invitation = $this->actingAs($owner)
        ->inviteTeamMember($team, 'newmember@example.com', 'member');
    
    Mail::assertSent(TeamInvitationMail::class);
    
    $this->assertDatabaseHas('team_invitations', [
        'team_id' => $team->id,
        'email' => 'newmember@example.com',
    ]);
}
```

## Authorization Policies

```php
// app/Policies/TeamPolicy.php

public function addTeamMember(User $user, Team $team): bool
{
    return $user->ownsTeam($team) || $this->hasRole($user, $team, 'admin');
}

public function removeTeamMember(User $user, Team $team, User $teamMember): bool
{
    return $user->ownsTeam($team) || ($this->hasRole($user, $team, 'admin') && !$teamMember->ownsTeam($team));
}

public function updateTeamMember(User $user, Team $team): bool
{
    return $user->ownsTeam($team) || $this->hasRole($user, $team, 'admin');
}

protected function hasRole(User $user, Team $team, string $role): bool
{
    return $team->users()
        ->where('users.id', $user->id)
        ->wherePivot('role', $role)
        ->exists();
}
```

## Configuration Best Practices

1. **Roles:** Define clear role hierarchy
2. **Permissions:** Use policies for authorization
3. **Notifications:** Keep team members informed
4. **Activity tracking:** Log important team actions
5. **Validation:** Prevent invalid team operations

## Troubleshooting

### Common Issues:
1. **Cannot add member:** Check authorization policies
2. **Invitation not received:** Verify email configuration
3. **Cannot delete team:** Check if personal team
4. **Role changes not working:** Verify permissions

---

**Related Skills:**
- [OTP Authentication Skills](otp-authentication-skills.md)
- [Security Best Practices](security-best-practices.md)
- [Email Configuration Skills](email-configuration-skills.md)
