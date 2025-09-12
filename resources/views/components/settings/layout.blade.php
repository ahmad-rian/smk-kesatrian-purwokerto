<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <!-- Settings Navigation -->
        <x-mary-menu class="bg-base-200 rounded-lg p-2">
            <x-mary-menu-item title="{{ __('Profile') }}" :href="route('admin.settings.profile')" wire:navigate 
                :active="request()->routeIs('admin.settings.profile')" icon="o-user" />
            <x-mary-menu-item title="{{ __('Password') }}" :href="route('admin.settings.password')" wire:navigate 
                :active="request()->routeIs('admin.settings.password')" icon="o-key" />
            <x-mary-menu-item title="{{ __('Appearance') }}" :href="route('admin.settings.appearance')" wire:navigate 
                :active="request()->routeIs('admin.settings.appearance')" icon="o-paint-brush" />
        </x-mary-menu>
    </div>

    <div class="md:hidden">
        <div class="divider"></div>
    </div>

    <div class="flex-1 self-stretch max-md:pt-6">
        <div class="mb-6">
            <h1 class="title text-2xl font-bold text-base-content">{{ $heading ?? '' }}</h1>
            <p class="body text-base-content/70 mt-1">{{ $subheading ?? '' }}</p>
        </div>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
