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
 * Livewire Component untuk membuat pengaturan situs baru
 * 
 * Menyediakan form untuk input semua data pengaturan situs
 * dengan validasi dan upload gambar otomatis
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Create extends Component
{
    use Toast, WithFileUploads;

    // Form Properties - Informasi Dasar
    public string $nama_sekolah = '';
    public string $alamat = '';
    public string $telepon = '';
    public string $email = '';

    // Form Properties - Upload Files
    public ?UploadedFile $logo = null;
    public ?UploadedFile $foto_kepala_sekolah = null;

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
     * Persiapkan data untuk disimpan ke database
     */
    private function prepareDataForSave(array $validatedData): array
    {
        $data = [
            'nama_sekolah' => $this->nama_sekolah,
            'alamat' => $this->alamat ?: null,
            'telepon' => $this->telepon ?: null,
            'email' => $this->email ?: null,
            'visi' => $this->visi ?: null,
            'misi' => $this->misi ?: null,
            'media_sosial' => [
                'instagram' => $this->instagram ?: null,
                'facebook' => $this->facebook ?: null,
                'youtube' => $this->youtube ?: null,
                'tiktok' => $this->tiktok ?: null,
            ],
        ];

        // Handle logo upload
        if ($this->logo) {
            $data['logo'] = $this->imageService->convertToWebP(
                $this->logo, 
                'site-settings/logos'
            );
        }

        // Handle foto kepala sekolah upload
        if ($this->foto_kepala_sekolah) {
            $data['foto_kepala_sekolah'] = $this->imageService->convertToWebP(
                $this->foto_kepala_sekolah, 
                'site-settings/photos'
            );
        }

        return $data;
    }

    /**
     * Simpan pengaturan situs baru
     */
    public function save(): void
    {
        try {
            // Validasi input
            $validatedData = $this->validateInput();
            
            // Cek apakah sudah ada pengaturan (hanya boleh satu)
            if (SiteSetting::exists()) {
                $this->error('Pengaturan situs sudah ada. Silakan edit pengaturan yang sudah ada.');
                return;
            }

            DB::transaction(function () use ($validatedData) {
                // Persiapkan data
                $data = $this->prepareDataForSave($validatedData);
                
                // Simpan ke database
                SiteSetting::create($data);
                
                $this->success('Pengaturan situs berhasil dibuat!');
            });

            // Redirect ke halaman index
            $this->redirect(route('admin.site-settings'), navigate: true);
            
        } catch (ValidationException $e) {
            // Error sudah ditangani di validateInput()
            return;
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat menyimpan pengaturan: ' . $e->getMessage());
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
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.site-settings.create');
    }


}