<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudyProgram;

/**
 * Seeder untuk data Program Studi SMK Kesatrian
 * 
 * Mengisi data program studi dengan informasi lengkap:
 * - Kode dan nama program studi
 * - Deskripsi detail setiap jurusan
 * - Kompetensi yang akan dipelajari
 * - Prospek karir lulusan
 * - Warna tema untuk UI
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyPrograms = [
            [
                'kode' => 'TKJ',
                'nama' => 'Teknik Komputer dan Jaringan',
                'deskripsi' => 'Program studi yang mempelajari tentang instalasi, konfigurasi, dan pemeliharaan komputer serta jaringan. Siswa akan dibekali kemampuan untuk merancang, membangun, dan mengelola infrastruktur IT yang handal dan aman.',
                'warna' => '#3B82F6', // Blue
                'kompetensi' => [
                    'Instalasi dan konfigurasi sistem operasi',
                    'Administrasi jaringan komputer',
                    'Troubleshooting hardware dan software',
                    'Keamanan jaringan dan sistem',
                    'Pemrograman dasar dan scripting',
                    'Manajemen server dan database'
                ],
                'prospek_karir' => [
                    'Network Administrator',
                    'System Administrator',
                    'IT Support Specialist',
                    'Network Engineer',
                    'Cyber Security Analyst',
                    'Database Administrator'
                ],
                'ketua_program' => 'Drs. Ahmad Wijaya, M.Kom',
                'aktif' => true,
                'urutan' => 1,
            ],
            [
                'kode' => 'RPL',
                'nama' => 'Rekayasa Perangkat Lunak',
                'deskripsi' => 'Program studi yang fokus pada pengembangan aplikasi dan sistem perangkat lunak. Siswa akan mempelajari berbagai bahasa pemrograman, metodologi pengembangan software, dan teknologi terkini dalam dunia IT.',
                'warna' => '#10B981', // Green
                'kompetensi' => [
                    'Pemrograman web dan mobile',
                    'Database design dan management',
                    'Software engineering principles',
                    'UI/UX Design',
                    'Version control dan collaboration',
                    'Testing dan debugging aplikasi'
                ],
                'prospek_karir' => [
                    'Software Developer',
                    'Web Developer',
                    'Mobile App Developer',
                    'Full Stack Developer',
                    'Software Engineer',
                    'Quality Assurance Tester'
                ],
                'ketua_program' => 'Ir. Siti Nurhaliza, M.T',
                'aktif' => true,
                'urutan' => 2,
            ],
            [
                'kode' => 'MM',
                'nama' => 'Multimedia',
                'deskripsi' => 'Program studi yang menggabungkan seni dan teknologi untuk menciptakan konten digital yang menarik. Siswa akan belajar desain grafis, animasi, video editing, dan pengembangan konten multimedia interaktif.',
                'warna' => '#8B5CF6', // Purple
                'kompetensi' => [
                    'Desain grafis dan ilustrasi digital',
                    'Animasi 2D dan 3D',
                    'Video editing dan post-production',
                    'Web design dan development',
                    'Photography dan videography',
                    'Interactive media development'
                ],
                'prospek_karir' => [
                    'Graphic Designer',
                    'Animator',
                    'Video Editor',
                    'Web Designer',
                    'Content Creator',
                    'Digital Marketing Specialist'
                ],
                'ketua_program' => 'Dra. Maya Sari, M.Sn',
                'aktif' => true,
                'urutan' => 3,
            ],
            [
                'kode' => 'OTKP',
                'nama' => 'Otomatisasi dan Tata Kelola Perkantoran',
                'deskripsi' => 'Program studi yang mempersiapkan siswa untuk menjadi tenaga profesional di bidang administrasi perkantoran modern. Fokus pada penguasaan teknologi perkantoran, manajemen dokumen, dan komunikasi bisnis.',
                'warna' => '#F59E0B', // Orange
                'kompetensi' => [
                    'Administrasi perkantoran modern',
                    'Manajemen dokumen digital',
                    'Komunikasi bisnis dan korespondensi',
                    'Aplikasi perkantoran (MS Office, Google Workspace)',
                    'Customer service dan public relations',
                    'Event planning dan organizing'
                ],
                'prospek_karir' => [
                    'Administrative Assistant',
                    'Office Manager',
                    'Secretary',
                    'Customer Service Representative',
                    'Event Coordinator',
                    'Human Resources Assistant'
                ],
                'ketua_program' => 'Drs. Bambang Sutrisno, M.M',
                'aktif' => true,
                'urutan' => 4,
            ],
            [
                'kode' => 'AKL',
                'nama' => 'Akuntansi dan Keuangan Lembaga',
                'deskripsi' => 'Program studi yang membekali siswa dengan pengetahuan dan keterampilan di bidang akuntansi, keuangan, dan perpajakan. Siswa akan mempelajari sistem informasi akuntansi dan aplikasi keuangan modern.',
                'warna' => '#EF4444', // Red
                'kompetensi' => [
                    'Prinsip-prinsip akuntansi dan pembukuan',
                    'Laporan keuangan dan analisis',
                    'Perpajakan dan compliance',
                    'Sistem informasi akuntansi',
                    'Budgeting dan financial planning',
                    'Audit dan internal control'
                ],
                'prospek_karir' => [
                    'Accounting Staff',
                    'Bookkeeper',
                    'Tax Consultant',
                    'Financial Analyst',
                    'Auditor',
                    'Treasury Staff'
                ],
                'ketua_program' => 'Dra. Rina Kartika, M.Ak',
                'aktif' => true,
                'urutan' => 5,
            ],
            [
                'kode' => 'BDP',
                'nama' => 'Bisnis Daring dan Pemasaran',
                'deskripsi' => 'Program studi yang mempersiapkan siswa untuk terjun ke dunia bisnis digital dan e-commerce. Fokus pada strategi pemasaran online, manajemen toko online, dan pengembangan bisnis digital.',
                'warna' => '#06B6D4', // Cyan
                'kompetensi' => [
                    'Digital marketing dan social media',
                    'E-commerce dan marketplace management',
                    'Content marketing dan copywriting',
                    'SEO dan SEM optimization',
                    'Business analytics dan reporting',
                    'Customer relationship management'
                ],
                'prospek_karir' => [
                    'Digital Marketing Specialist',
                    'E-commerce Manager',
                    'Social Media Manager',
                    'Online Business Owner',
                    'Content Marketing Specialist',
                    'SEO Specialist'
                ],
                'ketua_program' => 'Dr. Indra Gunawan, M.M',
                'aktif' => true,
                'urutan' => 6,
            ]
        ];

        foreach ($studyPrograms as $program) {
            StudyProgram::updateOrCreate(
                ['kode' => $program['kode']],
                $program
            );
        }
    }
}
