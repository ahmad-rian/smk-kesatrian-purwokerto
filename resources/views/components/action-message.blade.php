@props([
    'on',
])

@php
    $messageId = 'action-message-' . $on . '-' . uniqid();
@endphp

<div
    id="{{ $messageId }}"
    style="display: none; opacity: 0; transition: opacity 0.3s ease;"
    {{ $attributes->merge(['class' => 'text-sm']) }}
>
    {{ $slot->isEmpty() ? __('Saved.') : $slot }}
</div>

<script>
    document.addEventListener('livewire:init', () => {
        // Listen for the specific event
        Livewire.on('{{ $on }}', () => {
            const messageEl = document.getElementById('{{ $messageId }}');
            if (messageEl) {
                // Clear any existing timeout
                if (messageEl.hideTimeout) {
                    clearTimeout(messageEl.hideTimeout);
                }
                
                // Show message
                messageEl.style.display = 'block';
                messageEl.style.opacity = '1';
                
                // Hide after 2 seconds
                messageEl.hideTimeout = setTimeout(() => {
                    messageEl.style.opacity = '0';
                    setTimeout(() => {
                        messageEl.style.display = 'none';
                    }, 300);
                }, 2000);
            }
        });
    });
</script>
