<?php

namespace App\Livewire\Frontend\Contact;

use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Kontak - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Index extends Component
{
    /**
     * Form fields untuk kontak
     */
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:20')]
    public $phone = '';

    #[Validate('required|string|max:255')]
    public $subject = '';

    #[Validate('required|string|min:10')]
    public $message = '';

    /**
     * Data pengaturan situs untuk informasi kontak
     */
    public $siteSettings;

    /**
     * Status pesan sukses
     */
    public $successMessage = '';

    /**
     * Mount komponen dan ambil data site settings
     */
    public function mount()
    {
        // Ambil data pengaturan situs untuk informasi kontak (menggunakan struktur kolom langsung)
        $settings = SiteSetting::first();

        if ($settings) {
            $this->siteSettings = [
                'school_address' => $settings->alamat ?? '',
                'school_phone' => $settings->telepon ?? '',
                'school_email' => $settings->email ?? '',
                'school_website' => $settings->website ?? '',
                'school_maps_url' => '' // Manual karena tidak ada di database
            ];
        } else {
            $this->siteSettings = [];
        }
    }

    /**
     * Validasi dan kirim pesan kontak
     */
    public function submitContact()
    {
        // Validasi input
        $this->validate();

        try {
            // Simpan pesan kontak ke database
            ContactMessage::create([
                'nama' => $this->name,
                'email' => $this->email,
                'telepon' => $this->phone,
                'subjek' => $this->subject,
                'pesan' => $this->message,
                'status' => ContactMessage::STATUS_UNREAD
            ]);

            // Reset form
            $this->reset(['name', 'email', 'phone', 'subject', 'message']);

            // Set pesan sukses
            $this->successMessage = 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.';

            // Dispatch event untuk notifikasi
            $this->dispatch('contact-sent');
        } catch (\Exception $e) {
            // Handle error
            session()->flash('error', 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.');
        }
    }

    /**
     * Reset pesan sukses
     */
    public function resetSuccessMessage()
    {
        $this->successMessage = '';
    }

    /**
     * Mendapatkan alamat sekolah
     */
    public function getSchoolAddressProperty()
    {
        return $this->siteSettings['school_address'] ?? 'Jl. Ksatrian No.62, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115';
    }

    /**
     * Mendapatkan nomor telepon sekolah
     */
    public function getSchoolPhoneProperty()
    {
        return $this->siteSettings['school_phone'] ?? '(0281) 123456';
    }

    /**
     * Mendapatkan email sekolah
     */
    public function getSchoolEmailProperty()
    {
        return $this->siteSettings['school_email'] ?? 'info@smkkesatrian.sch.id';
    }

    /**
     * Mendapatkan fax sekolah
     */
    public function getSchoolFaxProperty()
    {
        return $this->siteSettings['school_fax'] ?? '(0281) 123457';
    }

    /**
     * Mendapatkan website sekolah
     */
    public function getSchoolWebsiteProperty()
    {
        return $this->siteSettings['school_website'] ?? 'www.smkkesatrian.sch.id';
    }

    /**
     * Mendapatkan URL Google Maps
     */
    public function getGoogleMapsUrlProperty()
    {
        return $this->siteSettings['school_maps_url'] ?? 'https://www.google.com/maps?gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIHCAEQIRigATIHCAIQIRigATIHCAMQIRigATIHCAQQIRigATIHCAUQIRifBTIHCAYQIRiPAjIHCAcQIRiPAjIHCAgQIRiPAtIBCDYxNDRqMGo3qAIAsAIA&um=1&ie=UTF-8&fb=1&gl=id&sa=X&geocode=KV9Pmq7nXmUuMZWP97-6YKqe&daddr=Jl.+Ksatrian+No.62,+Karangjengkol,+Sokanegara,+Kec.+Purwokerto+Tim.,+Kabupaten+Banyumas,+Jawa+Tengah+53115';
    }

    /**
     * Render komponen kontak
     */
    public function render()
    {
        return view('livewire.frontend.contact.index', [
            'schoolAddress' => $this->schoolAddress,
            'schoolPhone' => $this->schoolPhone,
            'schoolEmail' => $this->schoolEmail,
            'schoolFax' => $this->schoolFax,
            'schoolWebsite' => $this->schoolWebsite,
            'googleMapsUrl' => $this->googleMapsUrl
        ]);
    }
}
