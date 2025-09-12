<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeder untuk Admin
        $admin = User::where('email', 'ahmad.ritonga@mhs.unsoed.ac.id')->first();
        if (!$admin) {
            User::create([
                'id' => Str::ulid(), // Generate ULID untuk primary key
                'email' => 'ahmad.ritonga@mhs.unsoed.ac.id',
                'nama' => 'Ahmad Ritonga',
                'password' => Hash::make(Str::random(32)), // Password random untuk SSO
                'role' => 'admin',
                'aktif' => true,
                'diizinkan' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Seeder untuk User
        $user = User::where('email', 'alriansr@gmail.com')->first();
        if (!$user) {
            User::create([
                'id' => Str::ulid(), // Generate ULID untuk primary key
                'email' => 'alriansr@gmail.com',
                'nama' => 'Alrian SR',
                'password' => Hash::make(Str::random(32)), // Password random untuk SSO
                'role' => 'user',
                'aktif' => true,
                'diizinkan' => true,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('User seeder completed successfully!');
        $this->command->info('Admin: ahmad.ritonga@mhs.unsoed.ac.id');
        $this->command->info('User: alriansr@gmail.com');
    }
}