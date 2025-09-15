<?php

namespace Database\Seeders;

use App\Models\SchoolActivity;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiteSettingSeeder::class,
            StudyProgramSeeder::class,
            SchoolActivitySeeder::class,
            FacilitySeeder::class,
            GallerySeeder::class,
            NewsSeeder::class,
        ]);
    }
}
