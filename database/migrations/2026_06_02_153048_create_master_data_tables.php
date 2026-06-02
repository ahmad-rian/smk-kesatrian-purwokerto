<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Classrooms (Kelas)
        if (!Schema::hasTable('classrooms')) {
            Schema::create('classrooms', function (Blueprint $table) {
                $table->id();
                $table->string('level')->nullable(); 
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // 2. Tabel Teachers (Guru)
        if (!Schema::hasTable('teachers')) {
            Schema::create('teachers', function (Blueprint $table) {
                $table->id();
                $table->string('nip')->nullable();
                $table->string('name');
                $table->string('gender')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabel Students (Siswa)
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('nis')->unique();
                $table->string('name');
                $table->string('gender')->nullable();
                $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Proses Import CSV
        $this->importClassrooms();
        $this->importTeachers();
        $this->importStudents();
    }

    private function importClassrooms()
    {
        $path = base_path('import-data/Master Data - kelas.csv');
        if (!file_exists($path)) return;

        $file = fopen($path, 'r');
        $currentLevel = null;
        
        while (($row = fgetcsv($file)) !== false) {
            if (empty(array_filter($row))) continue;
            
            $level = trim($row[0] ?? '');
            if (!empty($level)) {
                $currentLevel = $level;
            }
            
            $name = trim($row[1] ?? '');
            if (!empty($name)) {
                $exists = DB::table('classrooms')->where('name', $name)->exists();
                if (!$exists) {
                    DB::table('classrooms')->insert([
                        'level' => $currentLevel,
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        fclose($file);
    }

    private function importTeachers()
    {
        $path = base_path('import-data/Master Data - Guru.csv');
        if (!file_exists($path)) return;

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // skip header

        while (($row = fgetcsv($file)) !== false) {
            if (empty(array_filter($row))) continue;
            
            $nip = trim($row[0] ?? '');
            $name = trim($row[1] ?? '');
            $gender = trim($row[2] ?? '');
            $email = trim($row[3] ?? '');

            if (empty($name)) continue;

            $query = DB::table('teachers');
            if (!empty($nip)) {
                $query->where('nip', $nip);
            } else {
                $query->where('name', $name);
            }

            if (!$query->exists()) {
                DB::table('teachers')->insert([
                    'nip' => empty($nip) ? null : $nip,
                    'name' => $name,
                    'gender' => empty($gender) ? null : $gender,
                    'email' => empty($email) ? null : $email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        fclose($file);
    }

    private function importStudents()
    {
        $path = base_path('import-data/Master Data - Siswa.csv');
        if (!file_exists($path)) return;

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // skip header

        $classrooms = DB::table('classrooms')->pluck('id', 'name')->toArray();

        while (($row = fgetcsv($file)) !== false) {
            if (empty(array_filter($row))) continue;
            
            $nis = trim($row[0] ?? '');
            $name = trim($row[1] ?? '');
            $gender = trim($row[2] ?? '');
            $className = trim($row[3] ?? '');

            if (empty($nis) || empty($name)) continue;

            $classroomId = $classrooms[$className] ?? null;

            if (!DB::table('students')->where('nis', $nis)->exists()) {
                DB::table('students')->insert([
                    'nis' => $nis,
                    'name' => $name,
                    'gender' => empty($gender) ? null : $gender,
                    'classroom_id' => $classroomId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        fclose($file);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data_tables');
    }
};
