<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolActivity;
use Carbon\Carbon;

/**
 * Seeder untuk mengisi data sample School Activities
 * Digunakan untuk testing dan development
 */
class SchoolActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'nama_kegiatan' => 'Penerimaan Peserta Didik Baru (PPDB)',
                'slug' => 'ppdb-2024',
                'kategori' => 'Akademik',
                'deskripsi' => 'Kegiatan penerimaan siswa baru untuk tahun ajaran 2024/2025. Meliputi pendaftaran online, tes seleksi, dan pengumuman hasil.',
                'tanggal_mulai' => '2024-06-01',
                'tanggal_selesai' => '2024-06-25',
                'lokasi' => 'SMK Kesatrian',
                'penanggungjawab' => 'Tim PPDB',
                'gambar_utama' => null,
                'aktif' => true,
                'unggulan' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kegiatan' => 'Prakerin (Praktik Kerja Industri)',
                'slug' => 'prakerin-gelombang-1',
                'kategori' => 'Praktik Kerja',
                'deskripsi' => 'Program praktik kerja industri untuk siswa kelas XI di berbagai perusahaan mitra. Durasi 3 bulan untuk meningkatkan kompetensi siswa.',
                'tanggal_mulai' => '2024-07-01',
                'tanggal_selesai' => '2024-09-30',
                'lokasi' => 'Berbagai Perusahaan Mitra',
                'penanggungjawab' => 'Koordinator Prakerin',
                'gambar_utama' => null,
                'aktif' => true,
                'unggulan' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kegiatan' => 'Lomba Kompetensi Siswa (LKS)',
                'slug' => 'lks-tingkat-provinsi',
                'kategori' => 'Kompetisi',
                'deskripsi' => 'Kompetisi tahunan untuk mengukur kemampuan siswa SMK dalam bidang keahlian masing-masing tingkat provinsi.',
                'tanggal_mulai' => '2024-08-15',
                'tanggal_selesai' => '2024-08-17',
                'lokasi' => 'Gedung Serbaguna Provinsi',
                'penanggungjawab' => 'Tim LKS SMK Kesatrian',
                'gambar_utama' => null,
                'aktif' => true,
                'unggulan' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kegiatan' => 'Job Fair SMK Kesatrian 2024',
                'slug' => 'job-fair-2024',
                'kategori' => 'Karir',
                'deskripsi' => 'Bursa kerja khusus untuk alumni dan siswa kelas XII SMK Kesatrian dengan berbagai perusahaan ternama.',
                'tanggal_mulai' => '2024-10-10',
                'tanggal_selesai' => '2024-10-12',
                'lokasi' => 'Aula SMK Kesatrian',
                'penanggungjawab' => 'Humas & BKK',
                'gambar_utama' => null,
                'aktif' => true,
                'unggulan' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama_kegiatan' => 'Pelatihan Sertifikasi Komputer',
                'slug' => 'pelatihan-sertifikasi-komputer',
                'kategori' => 'Pelatihan',
                'deskripsi' => 'Program pelatihan dan sertifikasi komputer untuk meningkatkan kompetensi siswa dalam bidang teknologi informasi.',
                'tanggal_mulai' => '2024-11-01',
                'tanggal_selesai' => '2024-11-30',
                'lokasi' => 'Lab Komputer SMK Kesatrian',
                'penanggungjawab' => 'Jurusan TKJ',
                'gambar_utama' => null,
                'aktif' => false,
                'unggulan' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($activities as $activity) {
            SchoolActivity::create($activity);
        }

        $this->command->info('Sample School Activities berhasil dibuat!');
    }
}
