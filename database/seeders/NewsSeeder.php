<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsData = [
            [
                'judul' => 'Penerimaan Siswa Baru Tahun Ajaran 2024/2025',
                'slug' => 'penerimaan-siswa-baru-2024-2025',
                'ringkasan' => 'Pendaftaran siswa baru telah dibuka dengan berbagai program keahlian unggulan yang siap mencetak lulusan berkualitas.',
                'konten' => '<p>SMK Kesatrian membuka pendaftaran siswa baru untuk tahun ajaran 2024/2025. Kami menawarkan berbagai program keahlian unggulan yang disesuaikan dengan kebutuhan industri modern.</p><p>Program keahlian yang tersedia meliputi Teknik Komputer dan Jaringan, Multimedia, Akuntansi, dan Administrasi Perkantoran. Setiap program dilengkapi dengan fasilitas modern dan tenaga pengajar yang berpengalaman.</p>',
                'kategori' => 'Pendaftaran',
                'status' => 'published',
                'tanggal_publikasi' => Carbon::now()->subDays(2),
                'meta_title' => 'Penerimaan Siswa Baru SMK Kesatrian 2024/2025',
                'meta_description' => 'Daftar sekarang di SMK Kesatrian untuk tahun ajaran 2024/2025. Berbagai program keahlian unggulan menanti Anda.',
                'featured' => true,
                'views' => 150
            ],
            [
                'judul' => 'Prestasi Siswa SMK Kesatrian di Kompetisi Nasional',
                'slug' => 'prestasi-siswa-kompetisi-nasional',
                'ringkasan' => 'Tim robotika SMK Kesatrian meraih juara 1 dalam kompetisi robotika tingkat nasional yang diselenggarakan di Jakarta.',
                'konten' => '<p>Prestasi membanggakan kembali diraih oleh siswa-siswi SMK Kesatrian. Tim robotika yang terdiri dari 5 siswa berhasil meraih juara 1 dalam Kompetisi Robotika Nasional 2024.</p><p>Kompetisi yang diikuti oleh 50 sekolah dari seluruh Indonesia ini menguji kemampuan siswa dalam merancang dan memprogram robot untuk menyelesaikan berbagai tantangan.</p>',
                'kategori' => 'Prestasi',
                'status' => 'published',
                'tanggal_publikasi' => Carbon::now()->subDays(5),
                'meta_title' => 'Juara 1 Kompetisi Robotika Nasional - SMK Kesatrian',
                'meta_description' => 'Tim robotika SMK Kesatrian meraih prestasi gemilang di kompetisi robotika tingkat nasional.',
                'featured' => true,
                'views' => 89
            ],
            [
                'judul' => 'Kerjasama dengan Industri untuk Program Magang',
                'slug' => 'kerjasama-industri-program-magang',
                'ringkasan' => 'SMK Kesatrian menjalin kerjasama dengan berbagai perusahaan terkemuka untuk memberikan pengalaman magang terbaik bagi siswa.',
                'konten' => '<p>Dalam upaya meningkatkan kualitas lulusan, SMK Kesatrian telah menjalin kerjasama dengan lebih dari 20 perusahaan untuk program magang siswa.</p><p>Program magang ini memberikan kesempatan kepada siswa untuk mendapatkan pengalaman kerja nyata di industri sesuai dengan bidang keahlian masing-masing.</p>',
                'kategori' => 'Kerjasama',
                'status' => 'published',
                'tanggal_publikasi' => Carbon::now()->subWeek(),
                'meta_title' => 'Program Magang SMK Kesatrian dengan Industri',
                'meta_description' => 'Kerjasama SMK Kesatrian dengan industri untuk program magang siswa yang berkualitas.',
                'featured' => false,
                'views' => 67
            ],
            [
                'judul' => 'Workshop Teknologi Terbaru untuk Guru dan Siswa',
                'slug' => 'workshop-teknologi-terbaru',
                'ringkasan' => 'Pelatihan teknologi terbaru diselenggarakan untuk meningkatkan kompetensi guru dan siswa dalam menghadapi era digital.',
                'konten' => '<p>SMK Kesatrian mengadakan workshop teknologi terbaru yang diikuti oleh seluruh guru dan perwakilan siswa. Workshop ini bertujuan untuk meningkatkan kompetensi dalam bidang teknologi informasi.</p><p>Materi yang disampaikan meliputi artificial intelligence, cloud computing, dan cybersecurity yang menjadi tren teknologi saat ini.</p>',
                'kategori' => 'Pelatihan',
                'status' => 'published',
                'tanggal_publikasi' => Carbon::now()->subWeeks(2),
                'meta_title' => 'Workshop Teknologi SMK Kesatrian',
                'meta_description' => 'Workshop teknologi terbaru untuk guru dan siswa SMK Kesatrian.',
                'featured' => false,
                'views' => 45
            ],
            [
                'judul' => 'Fasilitas Laboratorium Baru untuk Praktik Siswa',
                'slug' => 'fasilitas-laboratorium-baru',
                'ringkasan' => 'SMK Kesatrian meresmikan laboratorium baru dengan peralatan modern untuk mendukung praktik siswa.',
                'konten' => '<p>Sebagai komitmen dalam memberikan pendidikan berkualitas, SMK Kesatrian telah meresmikan laboratorium baru yang dilengkapi dengan peralatan modern dan canggih.</p><p>Laboratorium ini akan digunakan untuk praktik siswa dalam berbagai mata pelajaran kejuruan, sehingga siswa dapat belajar dengan fasilitas yang setara dengan industri.</p>',
                'kategori' => 'Fasilitas',
                'status' => 'published',
                'tanggal_publikasi' => Carbon::now()->subWeeks(3),
                'meta_title' => 'Laboratorium Baru SMK Kesatrian',
                'meta_description' => 'Fasilitas laboratorium baru SMK Kesatrian dengan peralatan modern untuk praktik siswa.',
                'featured' => false,
                'views' => 78
            ]
        ];

        foreach ($newsData as $news) {
            News::create($news);
        }
    }
}
