<?php

namespace App\Http\Livewire\Teams;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateTeamBrandingForm extends Component
{
    use WithFileUploads;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * The new team logo file.
     *
     * @var mixed
     */
    public $logo;

    /**
     * Mount the component.
     *
     * @param  mixed  $team
     * @return void
     */
    public function mount($team)
    {
        $this->team = $team;
        $this->state = $team->only(['primary_color', 'secondary_color']);
    }

    /**
     * Update the team's branding information.
     *
     * @return void
     */
    public function updateTeamBranding()
    {
        $this->resetErrorBag();

        $validated = Validator::make([
            'logo' => $this->logo,
            'primary_color' => $this->state['primary_color'] ?? null,
            'secondary_color' => $this->state['secondary_color'] ?? null,
        ], [
            'logo' => ['nullable', 'image', 'max:1024'],
            'primary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ], [
            'logo.max' => 'The logo must not be greater than 1MB.',
            'primary_color.regex' => 'The primary color must be a valid hex color code (e.g., #FF5733).',
            'secondary_color.regex' => 'The secondary color must be a valid hex color code (e.g., #33C3FF).',
        ])->validateWithBag('updateTeamBranding');

        // Handle logo upload
        if ($this->logo) {
            // Delete old logo if it exists
            if ($this->team->logo_path) {
                Storage::disk('public')->delete($this->team->logo_path);
            }

            // Store new logo
            $path = $this->logo->store('team-logos', 'public');
            $this->team->forceFill([
                'logo_path' => $path,
            ])->save();
        }

        // Update colors
        $this->team->forceFill([
            'primary_color' => $validated['primary_color'],
            'secondary_color' => $validated['secondary_color'],
        ])->save();

        $this->dispatch('saved');

        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Delete the team's logo.
     *
     * @return void
     */
    public function deleteLogo()
    {
        if ($this->team->logo_path) {
            Storage::disk('public')->delete($this->team->logo_path);

            $this->team->forceFill([
                'logo_path' => null,
            ])->save();

            $this->dispatch('saved');
        }
    }

    /**
     * Get the current team of the user's context.
     *
     * @return mixed
     */
    public function getTeamProperty()
    {
        return $this->team;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.teams.update-team-branding-form');
    }
}
