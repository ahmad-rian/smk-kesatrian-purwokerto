<?php

namespace App\Livewire\Frontend\SchoolProfile;

use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Title('Profil Sekolah - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Index extends Component
{
    /**
     * Data pengaturan situs untuk profil sekolah
     */
    public $siteSettings;

    /**
     * Mount komponen dan ambil data dari site settings
     */
    public function mount()
    {
        // Ambil data dari site settings (menggunakan struktur kolom langsung)
        $settings = SiteSetting::first();

        if ($settings) {
            $this->siteSettings = [
                'site_logo' => $settings->logo_url,
                'site_name' => $settings->nama_sekolah ?? 'SMK Kesatrian',
                'site_description' => $settings->deskripsi ?? '',
                'school_vision' => $settings->visi ?? '',
                'school_mission' => $settings->misi ?? '',
                'school_address' => $settings->alamat ?? '',
                'school_phone' => $settings->telepon ?? '',
                'school_email' => $settings->email ?? '',
                'school_maps_url' => '' // Manual karena tidak ada di database
            ];
        } else {
            $this->siteSettings = [];
        }
    }

    /**
     * Mendapatkan logo sekolah dari pengaturan situs
     */
    public function getSchoolLogoProperty()
    {
        return $this->siteSettings['site_logo'] ?? '/images/placeholder-image.svg';
    }

    /**
     * Mendapatkan nama sekolah
     */
    public function getSchoolNameProperty()
    {
        return $this->siteSettings['site_name'] ?? 'SMK Kesatrian';
    }

    /**
     * Mendapatkan deskripsi sekolah
     */
    public function getSchoolDescriptionProperty()
    {
        return $this->siteSettings['site_description'] ?? '';
    }

    /**
     * Mendapatkan visi sekolah
     */
    public function getSchoolVisionProperty()
    {
        return $this->siteSettings['school_vision'] ?? 'Menjadi sekolah kejuruan terdepan yang menghasilkan lulusan berkualitas dan berkarakter.';
    }

    /**
     * Mendapatkan misi sekolah
     */
    public function getSchoolMissionProperty()
    {
        return $this->siteSettings['school_mission'] ?? 'Menyelenggarakan pendidikan kejuruan yang berkualitas dengan mengintegrasikan teknologi dan nilai-nilai karakter.';
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
     * Mendapatkan URL Google Maps
     */
    public function getGoogleMapsUrlProperty()
    {
        return $this->siteSettings['school_maps_url'] ?? 'https://www.google.com/maps/dir/SMK+Kesatrian+Purwokerto,+Jalan+Ksatrian,+Karangjengkol,+Sokanegara,+Banyumas+Regency,+Central+Java/Jl.+Ksatrian+No.62,+Karangjengkol,+Sokanegara,+Kec.+Purwokerto+Tim.,+Kabupaten+Banyumas,+Jawa+Tengah+53115/@-7.418908,109.19545,13z/data=!3m1!4b1!4m13!4m12!1m5!1m1!1s0x2e655ee7ae9a4f5f:0x9eaa60babff78f95!2m2!1d109.2366495!2d-7.4189099!1m5!1m1!1s0x2e655ee7ae9a4f5f:0x9eaa60babff78f95!2m2!1d109.2366495!2d-7.4189099?entry=ttu&g_ep=EgoyMDI1MDkxMC4wIKXMDSoASAFQAw%3D%3D';
    }

    /**
     * Render komponen profil sekolah
     */
    public function render()
    {
        return view('livewire.frontend.school-profile.index', [
            'schoolLogo' => $this->schoolLogo,
            'schoolName' => $this->schoolName,
            'schoolDescription' => $this->schoolDescription,
            'schoolVision' => $this->schoolVision,
            'schoolMission' => $this->schoolMission,
            'schoolAddress' => $this->schoolAddress,
            'schoolPhone' => $this->schoolPhone,
            'schoolEmail' => $this->schoolEmail,
            'googleMapsUrl' => $this->googleMapsUrl
        ]);
    }
}
