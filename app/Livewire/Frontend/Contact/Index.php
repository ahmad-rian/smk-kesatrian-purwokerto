<?php

namespace App\Livewire\Frontend\Contact;

use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Kontak - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Index extends Component
{
    /**
     * Data pengaturan situs untuk informasi kontak
     */
    public $siteSettings;

    /**
     * Mount komponen dan ambil data site settings
     */
    public function mount()
    {
        // Ambil data pengaturan situs untuk informasi kontak
        $settings = SiteSetting::first();

        if ($settings) {
            $this->siteSettings = [
                'school_address' => $settings->alamat ?? 'Jl. Ksatrian No.62, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115',
                'school_phone' => $settings->telepon ?? '(0281) 123456',
                'school_email' => $settings->email ?? 'info@smkkesatrian.sch.id',
                'school_website' => $settings->website ?? 'www.smkkesatrian.sch.id',
                'school_maps_url' => 'https://www.google.com/maps?gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIHCAEQIRigATIHCAIQIRigATIHCAMQIRigATIHCAQQIRigATIHCAUQIRifBTIHCAYQIRiPAjIHCAcQIRiPAjIHCAgQIRiPAtIBCDYxNDRqMGo3qAIAsAIA&um=1&ie=UTF-8&fb=1&gl=id&sa=X&geocode=KV9Pmq7nXmUuMZWP97-6YKqe&daddr=Jl.+Ksatrian+No.62,+Karangjengkol,+Sokanegara,+Kec.+Purwokerto+Tim.,+Kabupaten+Banyumas,+Jawa+Tengah+53115'
            ];
        } else {
            $this->siteSettings = [
                'school_address' => 'Jl. Ksatrian No.62, Karangjengkol, Sokanegara, Kec. Purwokerto Tim., Kabupaten Banyumas, Jawa Tengah 53115',
                'school_phone' => '(0281) 123456',
                'school_email' => 'info@smkkesatrian.sch.id',
                'school_website' => 'www.smkkesatrian.sch.id',
                'school_maps_url' => 'https://www.google.com/maps?gs_lcrp=EgZjaHJvbWUyBggAEEUYOTIHCAEQIRigATIHCAIQIRigATIHCAMQIRigATIHCAQQIRigATIHCAUQIRifBTIHCAYQIRiPAjIHCAcQIRiPAjIHCAgQIRiPAtIBCDYxNDRqMGo3qAIAsAIA&um=1&ie=UTF-8&fb=1&gl=id&sa=X&geocode=KV9Pmq7nXmUuMZWP97-6YKqe&daddr=Jl.+Ksatrian+No.62,+Karangjengkol,+Sokanegara,+Kec.+Purwokerto+Tim.,+Kabupaten+Banyumas,+Jawa+Tengah+53115'
            ];
        }
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
