<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<div class="space-y-6">
    <!-- Password Form -->
    <x-mary-card>
        <x-slot:title class="title text-xl font-semibold">{{ __('Update Password') }}</x-slot:title>
                
                <form wire:submit="updatePassword" class="space-y-6">
                    <x-mary-input
                        wire:model="current_password"
                        :label="__('Current password')"
                        type="password"
                        required
                        autocomplete="current-password"
                        icon="o-key"
                    />
                    <x-mary-input
                        wire:model="password"
                        :label="__('New password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        icon="o-lock-closed"
                    />
                    <x-mary-input
                        wire:model="password_confirmation"
                        :label="__('Confirm Password')"
                        type="password"
                        required
                        autocomplete="new-password"
                        icon="o-lock-closed"
                    />

                    <div class="flex items-center gap-4">
                        <x-mary-button type="submit" class="btn-primary" spinner="updatePassword">
                            {{ __('Update Password') }}
                        </x-mary-button>

                        <x-action-message class="text-success" on="password-updated">
                            {{ __('Saved.') }}
                        </x-action-message>
                    </div>
        </form>
    </x-mary-card>
</div>
