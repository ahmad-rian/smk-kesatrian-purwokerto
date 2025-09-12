<?php

namespace App\Livewire\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Profile Settings - SMK Kesatrian')]
#[Layout('livewire.admin.layout')]
class Profile extends Component
{
    public string $nama = '';
    public string $email = '';

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
        $validated = $this->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore(Auth::id())],
        ]);

        $originalEmail = Auth::user()->email;

        User::where('id', Auth::id())->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'email_verified_at' => $originalEmail !== $validated['email'] ? null : Auth::user()->email_verified_at,
        ]);

        $this->dispatch('profile-updated', nama: $validated['nama']);

        // Flash success message
        session()->flash('status', 'profile-updated');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->email_verified_at !== null) {
            $this->redirect(route('admin.dashboard'), navigate: true);
            return;
        }

        // Placeholder for email verification - implement as needed
        $this->dispatch('verification-link-sent');
        session()->flash('status', 'verification-link-sent');
    }

    public function render()
    {
        return view('livewire.settings.profile');
    }
}
