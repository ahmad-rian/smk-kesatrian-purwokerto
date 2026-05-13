<?php

namespace App\Livewire\Frontend\CustomPage;

use App\Models\CustomPage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.frontend')]
class Show extends Component
{
    public CustomPage $page;

    public function mount(string $slug): void
    {
        $this->page = CustomPage::published()->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.frontend.custom-page.show');
    }
}
