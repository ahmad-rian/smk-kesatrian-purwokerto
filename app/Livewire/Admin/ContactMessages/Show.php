<?php

namespace App\Livewire\Admin\ContactMessages;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('livewire.admin.layout')]
class Show extends Component
{
    /**
     * ID pesan kontak
     *
     * @var string
     */
    public string $messageId;

    /**
     * Pesan kontak
     *
     * @var ContactMessage|null
     */
    public ?ContactMessage $message = null;

    /**
     * Mount komponen
     *
     * @param string $contactMessage
     * @return void
     */
    public function mount(string $contactMessage): void
    {
        $this->messageId = $contactMessage;
        $this->message = ContactMessage::findOrFail($contactMessage);
        
        // Tandai pesan sebagai sudah dibaca jika belum dibaca
        if ($this->message->isUnread()) {
            $this->message->markAsRead();
        }
    }

    /**
     * Tandai pesan sebagai belum dibaca
     *
     * @return void
     */
    public function markAsUnread(): void
    {
        $this->message->markAsUnread();
        $this->message->refresh();

        session()->flash('message', 'Pesan kontak ditandai sebagai belum dibaca');
    }

    /**
     * Hapus pesan kontak
     *
     * @return mixed
     */
    public function delete()
    {
        $this->message->delete();

        session()->flash('message', 'Pesan kontak berhasil dihapus');

        // Redirect ke halaman index
        return $this->redirect(route('admin.contact-messages.index'), navigate: true);
    }

    /**
     * Render komponen
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.contact-messages.show');
    }
}