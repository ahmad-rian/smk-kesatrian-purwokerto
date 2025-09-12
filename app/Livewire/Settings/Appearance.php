<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;

#[Title('Appearance Settings - SMK Kesatrian')]

class Appearance extends Component
{
    #[Validate('required|in:light,dark,system')]
    public string $appearance = 'light';

    public function mount(): void
    {
        $this->appearance = session('appearance', 'light');
    }

    public function updatedAppearance($value): void
    {
        $this->validate();

        // Save to session
        session(['appearance' => $value]);

        // Apply theme immediately dengan multiple approaches
        $this->dispatch('appearance-updated', appearance: $value);

        // Force JavaScript execution
        $this->js("
            console.log('Theme changing to: $value');
            window.currentAppearance = '$value';
            
            // Method 1: Direct DOM manipulation
            document.documentElement.setAttribute('data-theme', '$value');
            
            // Method 2: Call function if exists
            if (typeof window.applyTheme === 'function') {
                window.applyTheme('$value');
            }
            
            // Method 3: Dispatch custom event
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: '$value' } }));
            
            console.log('Current data-theme:', document.documentElement.getAttribute('data-theme'));
        ");

        // Flash success message
        session()->flash('status', 'appearance-updated');
    }

    public function render()
    {
        return view('livewire.settings.appearance')
            ->layout('livewire.admin.layout');
    }
}
