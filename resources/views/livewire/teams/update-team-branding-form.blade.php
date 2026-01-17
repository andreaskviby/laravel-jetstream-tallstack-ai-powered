<div>
    <x-form-section submit="updateTeamBranding">
        <x-slot name="title">
            {{ __('Team Branding') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Customize your team\'s appearance with a logo and brand colors.') }}
        </x-slot>

        <x-slot name="form">
            <!-- Team Logo -->
            <div class="col-span-6 sm:col-span-4">
                <!-- Logo File Input -->
                <input type="file" 
                       class="hidden"
                       wire:model="logo"
                       x-ref="logo" />

                <x-label for="logo" value="{{ __('Team Logo') }}" />

                <!-- Current Logo -->
                @if ($team->logo_path)
                    <div class="mt-2">
                        <img src="{{ $team->logo_url }}" alt="{{ $team->name }}" class="rounded-lg h-20 w-20 object-cover">
                    </div>
                @endif

                <!-- New Logo Preview -->
                @if ($logo)
                    <div class="mt-2">
                        <span class="block rounded-lg w-20 h-20 bg-cover bg-no-repeat bg-center"
                              style="background-image: url('{{ $logo->temporaryUrl() }}');">
                        </span>
                    </div>
                @endif

                <x-secondary-button class="mt-2 me-2" 
                                    type="button" 
                                    x-on:click.prevent="$refs.logo.click()">
                    {{ __('Select A New Logo') }}
                </x-secondary-button>

                @if ($team->logo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteLogo">
                        {{ __('Remove Logo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="logo" class="mt-2" />
            </div>

            <!-- Primary Color -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="primary_color" value="{{ __('Primary Brand Color') }}" />
                <div class="flex items-center mt-2">
                    <input type="color" 
                           id="primary_color" 
                           wire:model="state.primary_color" 
                           class="h-10 w-20 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    <input type="text" 
                           wire:model="state.primary_color" 
                           placeholder="#FF5733"
                           class="ml-2 flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>
                <x-input-error for="primary_color" class="mt-2" />
            </div>

            <!-- Secondary Color -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="secondary_color" value="{{ __('Secondary Brand Color') }}" />
                <div class="flex items-center mt-2">
                    <input type="color" 
                           id="secondary_color" 
                           wire:model="state.secondary_color" 
                           class="h-10 w-20 rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm">
                    <input type="text" 
                           wire:model="state.secondary_color" 
                           placeholder="#33C3FF"
                           class="ml-2 flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                </div>
                <x-input-error for="secondary_color" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button>
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
