<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

/**
 * Seeder untuk data Site Setting
 * 
 * Membuat data sample untuk testing dan development
 */
class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data existing jika ada
        SiteSetting::truncate();
        
        // Buat data sample untuk testing
        SiteSetting::create([
            'nama_sekolah' => 'SMK Kesatrian Purwokerto',
            'nama_singkat' => 'SMK Kesatrian',
            'tahun_berdiri' => 1985,
            'tagline' => 'Mencetak Generasi Unggul dan Berkarakter',
            'deskripsi' => 'SMK Kesatrian Purwokerto adalah sekolah menengah kejuruan yang berfokus pada pengembangan keterampilan teknis dan karakter siswa. Dengan fasilitas modern dan tenaga pengajar yang kompeten, kami berkomitmen untuk mencetak lulusan yang siap kerja dan berwirausaha.',
            'alamat' => 'Jl. Raya Purwokerto No. 123, Purwokerto Selatan, Banyumas, Jawa Tengah 53147',
            'telepon' => '(0281) 123-4567',
            'email' => 'info@smkkesatrian.sch.id',
            'website' => 'https://www.smkkesatrian.sch.id',
            'media_sosial' => [
                'instagram' => '@smkkesatrian_pwt',
                'facebook' => 'SMK Kesatrian Purwokerto',
                'youtube' => 'SMK Kesatrian Channel',
                'tiktok' => '@smkkesatrian'
            ],
            'visi' => 'Menjadi SMK unggulan yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global pada tahun 2030.',
            'misi' => "1. Menyelenggarakan pendidikan kejuruan yang berkualitas dan relevan dengan kebutuhan industri\n2. Mengembangkan karakter siswa yang berakhlak mulia dan berjiwa entrepreneur\n3. Meningkatkan kompetensi tenaga pendidik dan kependidikan secara berkelanjutan\n4. Menyediakan sarana dan prasarana pembelajaran yang modern dan memadai\n5. Menjalin kerjasama dengan dunia usaha dan dunia industri (DUDI)\n6. Mengembangkan budaya mutu dan inovasi dalam setiap kegiatan sekolah",
            'nama_kepala_sekolah' => 'Dr. Ahmad Suryanto, S.Pd., M.M.',
            'logo' => null, // Akan diisi saat upload gambar
            'foto_kepala_sekolah' => null, // Akan diisi saat upload gambar
        ]);
        
        // Buat data alternatif untuk testing update
        SiteSetting::create([
            'nama_sekolah' => 'SMK Teknologi Maju',
            'nama_singkat' => 'SMK Tekno',
            'tahun_berdiri' => 2000,
            'tagline' => 'Teknologi untuk Masa Depan',
            'deskripsi' => 'SMK yang fokus pada teknologi informasi dan komunikasi dengan kurikulum yang selalu update mengikuti perkembangan zaman.',
            'alamat' => 'Jl. Teknologi No. 456, Jakarta Selatan 12345',
            'telepon' => '(021) 987-6543',
            'email' => 'admin@smkteknologi.sch.id',
            'website' => 'https://smkteknologi.sch.id',
            'media_sosial' => [
                'instagram' => '@smkteknologi',
                'facebook' => 'SMK Teknologi Maju',
                'youtube' => 'SMK Teknologi Channel',
                'tiktok' => '@smkteknomaju'
            ],
            'visi' => 'Menjadi SMK terdepan dalam bidang teknologi informasi dan komunikasi.',
            'misi' => "1. Memberikan pendidikan teknologi terkini\n2. Mengembangkan inovasi dan kreativitas siswa\n3. Mempersiapkan tenaga kerja yang kompeten di bidang IT",
            'nama_kepala_sekolah' => 'Prof. Dr. Siti Nurhaliza, S.Kom., M.T.',
            'logo' => null,
            'foto_kepala_sekolah' => null,
        ]);
        
        $this->command->info('Site Settings seeder completed successfully!');
    }
}