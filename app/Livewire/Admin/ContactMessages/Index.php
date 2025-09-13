<?php

namespace App\Livewire\Admin\ContactMessages;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination;

    /**
     * Kata kunci pencarian
     *
     * @var string
     */
    public string $search = '';

    /**
     * Filter status pesan
     *
     * @var string|null
     */
    public ?string $status = null;

    /**
     * Jumlah item per halaman
     *
     * @var int
     */
    public int $perPage = 10;

    /**
     * Reset pagination ketika pencarian berubah
     *
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika filter status berubah
     *
     * @return void
     */
    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    /**
     * Hapus pesan kontak
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        session()->flash('message', 'Pesan kontak berhasil dihapus');
    }

    /**
     * Tandai pesan sebagai sudah dibaca
     *
     * @param string $id
     * @return void
     */
    public function markAsRead(string $id): void
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsRead();

        session()->flash('message', 'Pesan kontak ditandai sebagai sudah dibaca');
    }

    /**
     * Tandai pesan sebagai belum dibaca
     *
     * @param string $id
     * @return void
     */
    public function markAsUnread(string $id): void
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsUnread();

        session()->flash('message', 'Pesan kontak ditandai sebagai belum dibaca');
    }

    /**
     * Render komponen
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $query = ContactMessage::query();

        // Filter berdasarkan kata kunci pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('subjek', 'like', '%' . $this->search . '%')
                  ->orWhere('pesan', 'like', '%' . $this->search . '%');
            });
        }

        // Filter berdasarkan status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Urutkan berdasarkan waktu pembuatan (terbaru dulu)
        $messages = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.contact-messages.index', [
            'messages' => $messages,
        ]);
    }
}