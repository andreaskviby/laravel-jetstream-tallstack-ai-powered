<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Teams\UpdateTeamBrandingForm;

class UpdateTeamBrandingTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_branding_can_be_updated()
    {
        Storage::fake('public');

        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->currentTeam;

        $this->actingAs($user);

        $logo = UploadedFile::fake()->image('logo.jpg');

        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->set('logo', $logo)
            ->set('state.primary_color', '#FF5733')
            ->set('state.secondary_color', '#33C3FF')
            ->call('updateTeamBranding');

        $team = $team->fresh();

        $this->assertNotNull($team->logo_path);
        $this->assertEquals('#FF5733', $team->primary_color);
        $this->assertEquals('#33C3FF', $team->secondary_color);

        Storage::disk('public')->assertExists($team->logo_path);
    }

    public function test_team_logo_can_be_deleted()
    {
        Storage::fake('public');

        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->currentTeam;

        $this->actingAs($user);

        // First upload a logo
        $logo = UploadedFile::fake()->image('logo.jpg');
        
        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->set('logo', $logo)
            ->call('updateTeamBranding');

        $team = $team->fresh();
        $logoPath = $team->logo_path;

        $this->assertNotNull($logoPath);
        Storage::disk('public')->assertExists($logoPath);

        // Now delete the logo
        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->call('deleteLogo');

        $team = $team->fresh();

        $this->assertNull($team->logo_path);
        Storage::disk('public')->assertMissing($logoPath);
    }

    public function test_primary_color_must_be_valid_hex_code()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->currentTeam;

        $this->actingAs($user);

        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->set('state.primary_color', 'invalid-color')
            ->call('updateTeamBranding')
            ->assertHasErrors(['primary_color' => 'regex']);
    }

    public function test_secondary_color_must_be_valid_hex_code()
    {
        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->currentTeam;

        $this->actingAs($user);

        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->set('state.secondary_color', 'not-a-hex-color')
            ->call('updateTeamBranding')
            ->assertHasErrors(['secondary_color' => 'regex']);
    }

    public function test_logo_must_be_an_image()
    {
        Storage::fake('public');

        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->currentTeam;

        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->set('logo', $file)
            ->call('updateTeamBranding')
            ->assertHasErrors(['logo' => 'image']);
    }

    public function test_logo_size_must_not_exceed_1mb()
    {
        Storage::fake('public');

        $user = User::factory()->withPersonalTeam()->create();
        $team = $user->currentTeam;

        $this->actingAs($user);

        $logo = UploadedFile::fake()->image('logo.jpg')->size(1025); // 1025 KB

        Livewire::test(UpdateTeamBrandingForm::class, ['team' => $team])
            ->set('logo', $logo)
            ->call('updateTeamBranding')
            ->assertHasErrors(['logo' => 'max']);
    }
}
