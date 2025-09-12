<?php

namespace App\Livewire\Admin\SiteSettings;

use App\Http\Requests\SiteSettingRequest;
use App\Models\SiteSetting;
use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk edit pengaturan situs
 * 
 * Menyediakan form untuk update data pengaturan situs
 * dengan validasi dan upload gambar otomatis
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use Toast, WithFileUploads;

    /**
     * Model SiteSetting yang sedang diedit
     */
    public SiteSetting $siteSetting;

    // Form Properties - Informasi Dasar
    public string $nama_sekolah = '';
    public string $nama_singkat = '';
    public ?int $tahun_berdiri = null;
    public string $tagline = '';
    public string $deskripsi = '';
    public string $alamat = '';
    public string $telepon = '';
    public string $email = '';
    public string $website = '';
    public string $nama_kepala_sekolah = '';

    // Form Properties - Upload Files (file baru)
    public ?UploadedFile $logo = null;
    public ?UploadedFile $foto_kepala_sekolah = null;

    // Properties untuk menyimpan path gambar saat ini
    public ?string $currentLogo = null;
    public ?string $currentFotoKepalaSekolah = null;

    // Form Properties - Visi & Misi
    public string $visi = '';
    public string $misi = '';

    // Form Properties - Media Sosial
    public string $instagram = '';
    public string $facebook = '';
    public string $youtube = '';
    public string $tiktok = '';

    /**
     * Service untuk konversi gambar
     */
    private ImageConversionService $imageService;

    /**
     * Boot component dengan dependency injection
     */
    public function boot(ImageConversionService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * Mount component dengan data SiteSetting
     */
    public function mount(SiteSetting $siteSetting): void
    {
        $this->siteSetting = $siteSetting;
        $this->loadCurrentData();
    }

    /**
     * Load data saat ini ke form properties
     */
    private function loadCurrentData(): void
    {
        $this->nama_sekolah = $this->siteSetting->nama_sekolah ?? '';
        $this->nama_singkat = $this->siteSetting->nama_singkat ?? '';
        $this->tahun_berdiri = $this->siteSetting->tahun_berdiri;
        $this->tagline = $this->siteSetting->tagline ?? '';
        $this->deskripsi = $this->siteSetting->deskripsi ?? '';
        $this->alamat = $this->siteSetting->alamat ?? '';
        $this->telepon = $this->siteSetting->telepon ?? '';
        $this->email = $this->siteSetting->email ?? '';
        $this->website = $this->siteSetting->website ?? '';
        $this->nama_kepala_sekolah = $this->siteSetting->nama_kepala_sekolah ?? '';
        $this->visi = $this->siteSetting->visi ?? '';
        $this->misi = $this->siteSetting->misi ?? '';

        // Load current images
        $this->currentLogo = $this->siteSetting->logo;
        $this->currentFotoKepalaSekolah = $this->siteSetting->foto_kepala_sekolah;

        // Load media sosial
        $mediaSosial = $this->siteSetting->media_sosial ?? [];
        $this->instagram = $mediaSosial['instagram'] ?? '';
        $this->facebook = $mediaSosial['facebook'] ?? '';
        $this->youtube = $mediaSosial['youtube'] ?? '';
        $this->tiktok = $mediaSosial['tiktok'] ?? '';
    }

    /**
     * Validasi input menggunakan SiteSettingRequest
     */
    private function validateInput(): array
    {
        $request = new SiteSettingRequest();
        
        try {
            return $this->validate($request->rules(), $request->messages(), $request->attributes());
        } catch (ValidationException $e) {
            $this->error('Terdapat kesalahan pada form. Silakan periksa kembali.');
            throw $e;
        }
    }

    /**
     * Persiapkan data untuk update ke database
     */
    private function prepareDataForUpdate(array $validatedData): array
    {
        $data = [
            'nama_sekolah' => $this->nama_sekolah,
            'nama_singkat' => $this->nama_singkat ?: null,
            'tahun_berdiri' => $this->tahun_berdiri,
            'tagline' => $this->tagline ?: null,
            'deskripsi' => $this->deskripsi ?: null,
            'alamat' => $this->alamat ?: null,
            'telepon' => $this->telepon ?: null,
            'email' => $this->email ?: null,
            'website' => $this->website ?: null,
            'nama_kepala_sekolah' => $this->nama_kepala_sekolah ?: null,
            'visi' => $this->visi ?: null,
            'misi' => $this->misi ?: null,
            'media_sosial' => [
                'instagram' => $this->instagram ?: null,
                'facebook' => $this->facebook ?: null,
                'youtube' => $this->youtube ?: null,
                'tiktok' => $this->tiktok ?: null,
            ],
        ];

        // Handle logo upload (hanya jika ada file baru)
        if ($this->logo) {
            // Hapus logo lama jika ada
            if ($this->currentLogo) {
                $this->imageService->deleteOldImage($this->currentLogo);
            }
            
            $data['logo'] = $this->imageService->convertToWebP(
                $this->logo, 
                'site-settings/logos'
            );
        }

        // Handle foto kepala sekolah upload (hanya jika ada file baru)
        if ($this->foto_kepala_sekolah) {
            // Hapus foto lama jika ada
            if ($this->currentFotoKepalaSekolah) {
                $this->imageService->deleteOldImage($this->currentFotoKepalaSekolah);
            }
            
            $data['foto_kepala_sekolah'] = $this->imageService->convertToWebP(
                $this->foto_kepala_sekolah, 
                'site-settings/photos'
            );
        }

        return $data;
    }

    /**
     * Update pengaturan situs
     */
    public function update(): void
    {
        try {
            // Validasi input
            $validatedData = $this->validateInput();
            
            DB::transaction(function () use ($validatedData) {
                // Persiapkan data
                $data = $this->prepareDataForUpdate($validatedData);
                
                // Update ke database
                $this->siteSetting->update($data);
                
                $this->success('Pengaturan situs berhasil diperbarui!');
            });

            // Redirect ke halaman index
            $this->redirect(route('admin.site-settings'), navigate: true);
            
        } catch (ValidationException $e) {
            // Error sudah ditangani di validateInput()
            return;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengaturan situs
     */
    public function delete(): void
    {
        try {
            DB::transaction(function () {
                // Hapus gambar yang terkait
                if ($this->siteSetting->logo) {
                    $this->imageService->deleteOldImage($this->siteSetting->logo);
                }
                
                if ($this->siteSetting->foto_kepala_sekolah) {
                    $this->imageService->deleteOldImage($this->siteSetting->foto_kepala_sekolah);
                }
                
                // Hapus record dari database
                $this->siteSetting->delete();
                
                $this->success('Pengaturan situs berhasil dihapus!');
            });

            // Redirect ke halaman index
            $this->redirect(route('admin.site-settings'), navigate: true);
            
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat menghapus pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Kembali ke halaman index
     */
    public function back(): void
    {
        $this->redirect(route('admin.site-settings'), navigate: true);
    }

    /**
     * Get layout view
     */
    protected function getLayoutView(): string
    {
        return 'livewire.admin.layout';
    }

    /**
     * Render komponen
     */
    public function render(): View
    {
        return view('livewire.admin.site-settings.edit');
    }
}