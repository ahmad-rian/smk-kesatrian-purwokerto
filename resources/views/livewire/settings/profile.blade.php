<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $nama = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->nama = Auth::user()->nama;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'nama' => ['required', 'string', 'max:255'],

            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', nama: $user->nama);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div class="space-y-6">
    <!-- Profile Form -->
    <x-mary-card>
        <x-slot:title class="title text-xl font-semibold">{{ __('Profile Information') }}</x-slot:title>

        <form wire:submit="updateProfileInformation" class="space-y-6">
            <x-mary-input wire:model="nama" :label="__('Name')" type="text" required autofocus autocomplete="name">
                <x-slot:prepend>
                    <div class="flex items-center justify-center w-10 h-10 text-gray-500">
                        <x-mary-icon name="o-user" class="w-5 h-5" />
                    </div>
                </x-slot:prepend>
            </x-mary-input>

            <div>
                <x-mary-input wire:model="email" :label="__('Email')" type="email" required autocomplete="email">
                    <x-slot:prepend>
                        <div class="flex items-center justify-center w-10 h-10 text-gray-500">
                            <x-mary-icon name="o-envelope" class="w-5 h-5" />
                        </div>
                    </x-slot:prepend>
                </x-mary-input>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div class="mt-4">
                        <x-mary-alert icon="o-exclamation-triangle" class="alert-warning">
                            <x-slot:title>{{ __('Email Unverified') }}</x-slot:title>
                            <x-mary-button wire:click.prevent="resendVerificationNotification" class="btn-sm btn-ghost"
                                spinner>
                                {{ __('Click here to re-send the verification email.') }}
                            </x-mary-button>
                        </x-mary-alert>

                        @if (session('status') === 'verification-link-sent')
                            <x-mary-alert icon="o-check-circle" class="alert-success mt-2">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </x-mary-alert>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <x-mary-button type="submit" class="btn-primary" spinner="updateProfileInformation">
                    {{ __('Save Changes') }}
                </x-mary-button>

                <x-action-message class="text-success" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-mary-card>

    <!-- Delete Account Section -->
    <div class="mt-8">
        <livewire:settings.delete-user-form />
    </div>
</div>
