<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;
use App\Models\StudyProgram;

/**
 * Seeder untuk data Fasilitas SMK Kesatrian
 * 
 * Mengisi data fasilitas sekolah dengan kategori:
 * - Laboratorium dan workshop
 * - Fasilitas umum dan penunjang
 * - Fasilitas olahraga dan ekstrakurikuler
 * - Fasilitas teknologi dan multimedia
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID program studi untuk relasi
        $tkjProgram = StudyProgram::where('kode', 'TKJ')->first();
        $rplProgram = StudyProgram::where('kode', 'RPL')->first();
        $mmProgram = StudyProgram::where('kode', 'MM')->first();
        $otkpProgram = StudyProgram::where('kode', 'OTKP')->first();
        $aklProgram = StudyProgram::where('kode', 'AKL')->first();
        $bdpProgram = StudyProgram::where('kode', 'BDP')->first();

        $facilities = [
            // Laboratorium Komputer dan Jaringan
            [
                'nama' => 'Laboratorium Komputer dan Jaringan',
                'kategori' => 'Laboratorium',
                'deskripsi' => 'Laboratorium lengkap dengan 40 unit komputer terbaru, server rack, switch managed, router, dan peralatan jaringan profesional. Dilengkapi dengan software simulasi jaringan dan sistem operasi server untuk praktik administrasi jaringan.',
                'study_program_id' => $tkjProgram?->id,
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'nama' => 'Laboratorium Programming',
                'kategori' => 'Laboratorium',
                'deskripsi' => 'Ruang khusus untuk pembelajaran pemrograman dengan 35 unit komputer high-spec, dual monitor setup, dan akses internet dedicated. Tersedia berbagai IDE dan development tools untuk web, mobile, dan desktop development.',
                'study_program_id' => $rplProgram?->id,
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'nama' => 'Studio Multimedia',
                'kategori' => 'Studio',
                'deskripsi' => 'Studio kreatif dengan workstation grafis profesional, tablet grafis Wacom, kamera DSLR, lighting equipment, dan green screen. Dilengkapi software Adobe Creative Suite, Blender, dan perangkat editing video 4K.',
                'study_program_id' => $mmProgram?->id,
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'nama' => 'Laboratorium Akuntansi',
                'kategori' => 'Laboratorium',
                'deskripsi' => 'Laboratorium dengan 30 unit komputer yang dilengkapi software akuntansi profesional seperti MYOB, Accurate, dan Zahir. Tersedia juga simulator aplikasi perbankan dan perpajakan untuk praktik real-world scenario.',
                'study_program_id' => $aklProgram?->id,
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'nama' => 'Ruang Praktik Perkantoran',
                'kategori' => 'Ruang Praktik',
                'deskripsi' => 'Ruang yang didesain menyerupai kantor modern dengan meja kerja ergonomis, printer multifungsi, mesin fotokopi, scanner, dan peralatan administrasi lengkap. Dilengkapi sistem telepon dan video conference.',
                'study_program_id' => $otkpProgram?->id,
                'aktif' => true,
                'urutan' => 5,
            ],
            [
                'nama' => 'Digital Marketing Lab',
                'kategori' => 'Laboratorium',
                'deskripsi' => 'Laboratorium khusus untuk pembelajaran digital marketing dengan setup live streaming, photography corner, dan akses ke berbagai platform e-commerce. Dilengkapi tools analytics dan social media management.',
                'study_program_id' => $bdpProgram?->id,
                'aktif' => true,
                'urutan' => 6,
            ],

            // Fasilitas Umum
            [
                'nama' => 'Perpustakaan Digital',
                'kategori' => 'Fasilitas Umum',
                'deskripsi' => 'Perpustakaan modern dengan koleksi buku fisik dan digital lebih dari 10.000 judul. Dilengkapi area baca yang nyaman, akses WiFi, dan sistem katalog online. Tersedia juga e-book dan jurnal ilmiah.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 7,
            ],
            [
                'nama' => 'Aula Serbaguna',
                'kategori' => 'Fasilitas Umum',
                'deskripsi' => 'Aula dengan kapasitas 500 orang yang dilengkapi sistem audio visual modern, AC central, dan panggung yang dapat disesuaikan. Digunakan untuk acara sekolah, seminar, dan kegiatan ekstrakurikuler.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 8,
            ],
            [
                'nama' => 'Masjid Sekolah',
                'kategori' => 'Fasilitas Ibadah',
                'deskripsi' => 'Masjid dengan kapasitas 300 jamaah yang dilengkapi AC, sound system, dan tempat wudhu yang bersih. Digunakan untuk sholat berjamaah, kajian keagamaan, dan kegiatan rohani siswa.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 9,
            ],
            [
                'nama' => 'Kantin Sekolah',
                'kategori' => 'Fasilitas Umum',
                'deskripsi' => 'Kantin bersih dan sehat dengan berbagai pilihan makanan bergizi. Dilengkapi meja dan kursi yang cukup, serta sistem pembayaran cashless untuk kemudahan transaksi siswa.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 10,
            ],
            [
                'nama' => 'Klinik Kesehatan',
                'kategori' => 'Fasilitas Kesehatan',
                'deskripsi' => 'Klinik dengan tenaga medis profesional dan peralatan P3K lengkap. Menyediakan layanan kesehatan dasar, pemeriksaan rutin, dan penanganan pertama untuk kecelakaan ringan.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 11,
            ],

            // Fasilitas Olahraga
            [
                'nama' => 'Lapangan Basket Indoor',
                'kategori' => 'Fasilitas Olahraga',
                'deskripsi' => 'Lapangan basket indoor dengan lantai parket berkualitas, ring basket standar internasional, dan tribun penonton. Dilengkapi AC dan pencahayaan yang optimal untuk pertandingan dan latihan.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 12,
            ],
            [
                'nama' => 'Lapangan Futsal',
                'kategori' => 'Fasilitas Olahraga',
                'deskripsi' => 'Lapangan futsal outdoor dengan rumput sintetis berkualitas tinggi, gawang standar, dan pencahayaan untuk pertandingan malam. Digunakan untuk ekstrakurikuler futsal dan turnamen antar kelas.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 13,
            ],
            [
                'nama' => 'Gymnasium',
                'kategori' => 'Fasilitas Olahraga',
                'deskripsi' => 'Ruang olahraga indoor serbaguna untuk berbagai aktivitas seperti badminton, voli, dan senam. Dilengkapi dengan peralatan olahraga lengkap dan matras untuk kegiatan fitness.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 14,
            ],

            // Fasilitas Teknologi
            [
                'nama' => 'Server Room',
                'kategori' => 'Fasilitas Teknologi',
                'deskripsi' => 'Ruang server dengan sistem pendingin khusus, UPS backup, dan keamanan berlapis. Mengelola seluruh infrastruktur IT sekolah termasuk sistem informasi akademik dan jaringan internet.',
                'study_program_id' => $tkjProgram?->id,
                'aktif' => true,
                'urutan' => 15,
            ],
            [
                'nama' => 'Smart Classroom',
                'kategori' => 'Ruang Kelas',
                'deskripsi' => 'Ruang kelas dengan teknologi smart board interaktif, proyektor 4K, sistem audio yang jernih, dan akses internet fiber. Mendukung pembelajaran digital dan presentasi multimedia.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 16,
            ],
            [
                'nama' => 'Recording Studio',
                'kategori' => 'Studio',
                'deskripsi' => 'Studio rekaman profesional dengan acoustic treatment, mixing console, microphone condenser, dan software DAW terbaru. Digunakan untuk produksi audio, podcast, dan pembelajaran multimedia.',
                'study_program_id' => $mmProgram?->id,
                'aktif' => true,
                'urutan' => 17,
            ],
            [
                'nama' => 'Co-working Space',
                'kategori' => 'Ruang Kolaborasi',
                'deskripsi' => 'Ruang kerja bersama dengan desain modern dan fleksibel. Dilengkapi WiFi kencang, power outlet di setiap meja, whiteboard, dan area diskusi untuk project collaboration dan startup incubation.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 18,
            ],

            // Fasilitas Penunjang
            [
                'nama' => 'Parkir Kendaraan',
                'kategori' => 'Fasilitas Penunjang',
                'deskripsi' => 'Area parkir yang luas dan aman untuk sepeda motor dan mobil siswa serta guru. Dilengkapi dengan sistem keamanan CCTV dan petugas parkir untuk menjaga ketertiban.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 19,
            ],
            [
                'nama' => 'Taman Sekolah',
                'kategori' => 'Fasilitas Penunjang',
                'deskripsi' => 'Taman hijau dengan berbagai tanaman hias dan pohon rindang. Menyediakan area santai dan belajar outdoor yang nyaman, serta mendukung program sekolah hijau dan ramah lingkungan.',
                'study_program_id' => null,
                'aktif' => true,
                'urutan' => 20,
            ]
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}