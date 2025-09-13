<?php

namespace App\Livewire\Frontend;

use App\Models\ContactMessage;
use Livewire\Component;

/**
 * Component form kontak untuk pengunjung website
 */
class ContactForm extends Component
{
    /**
     * Nama pengirim
     *
     * @var string
     */
    public string $nama = '';

    /**
     * Email pengirim
     *
     * @var string
     */
    public string $email = '';

    /**
     * Telepon pengirim (opsional)
     *
     * @var string|null
     */
    public ?string $telepon = null;

    /**
     * Subjek pesan
     *
     * @var string
     */
    public string $subjek = '';

    /**
     * Isi pesan
     *
     * @var string
     */
    public string $pesan = '';

    /**
     * Status sukses pengiriman
     *
     * @var bool
     */
    public bool $success = false;

    /**
     * Aturan validasi
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'nullable|string|max:20',
            'subjek' => 'required|string|max:255',
            'pesan' => 'required|string|min:10',
        ];
    }

    /**
     * Pesan validasi
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'telepon.max' => 'Telepon maksimal 20 karakter',
            'subjek.required' => 'Subjek harus diisi',
            'subjek.max' => 'Subjek maksimal 255 karakter',
            'pesan.required' => 'Pesan harus diisi',
            'pesan.min' => 'Pesan minimal 10 karakter',
        ];
    }

    /**
     * Kirim pesan kontak
     *
     * @return void
     */
    public function submit(): void
    {
        $this->validate();

        // Simpan pesan kontak
        ContactMessage::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'telepon' => $this->telepon,
            'subjek' => $this->subjek,
            'pesan' => $this->pesan,
            'status' => ContactMessage::STATUS_UNREAD,
        ]);

        // Reset form dan tampilkan pesan sukses
        $this->reset(['nama', 'email', 'telepon', 'subjek', 'pesan']);
        $this->success = true;
    }

    /**
     * Render komponen
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.frontend.contact-form');
    }
}