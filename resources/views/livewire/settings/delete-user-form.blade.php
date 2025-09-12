<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';
    public bool $showDeleteModal = false;

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }

    /**
     * Close the delete modal and reset form.
     */
    public function closeModal(): void
    {
        $this->showDeleteModal = false;
        $this->password = '';
        $this->resetErrorBag();
    }

    /**
     * Show modal when there are validation errors.
     */
    public function updated($propertyName): void
    {
        if ($this->getErrorBag()->isNotEmpty()) {
            $this->showDeleteModal = true;
        }
    }
}; ?>

<section class="mt-10 space-y-6">
    <x-mary-card title="{{ __('Delete Account') }}" subtitle="{{ __('Delete your account and all of its resources') }}" class="border-red-200 dark:border-red-800">
        <div class="space-y-4">
            <x-mary-alert icon="o-exclamation-triangle" class="alert-warning">
                <span class="font-medium">{{ __('Warning!') }}</span>
                {{ __('This action cannot be undone. All your data will be permanently deleted.') }}
            </x-mary-alert>

            <!-- Delete Account Button -->
            <x-mary-button 
                label="{{ __('Delete Account') }}" 
                class="btn-error" 
                icon="o-trash" 
                @click="$wire.showDeleteModal = true" 
            />
        </div>
    </x-mary-card>

    <!-- Delete Confirmation Modal -->
    <x-mary-modal wire:model="showDeleteModal" title="{{ __('Confirm Account Deletion') }}" class="backdrop-blur" :show="$showDeleteModal || $errors->isNotEmpty()">
        <div class="space-y-4">
            <div class="text-center">
                <x-mary-icon name="o-exclamation-triangle" class="w-16 h-16 mx-auto text-error mb-4" />
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete your account?') }}
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <form wire:submit="deleteUser" class="space-y-4">
                <x-mary-input 
                    wire:model="password" 
                    label="{{ __('Password') }}" 
                    type="password" 
                    placeholder="{{ __('Enter your password to confirm') }}"
                    required 
                />

                <div class="flex justify-end gap-3 pt-4">
                    <x-mary-button 
                        label="{{ __('Cancel') }}" 
                        class="btn-ghost" 
                        wire:click="closeModal" 
                    />
                    <x-mary-button 
                        label="{{ __('Delete Account') }}" 
                        type="submit" 
                        class="btn-error" 
                        icon="o-trash"
                        spinner="deleteUser"
                    />
                </div>
            </form>
        </div>
    </x-mary-modal>
</section>
