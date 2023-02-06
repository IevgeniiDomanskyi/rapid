<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ExpsSeeder::class);
        $this->call(CoursesSeeder::class);
        $this->call(LevelsSeeder::class);
        $this->call(PostcodesSeeder::class);
        $this->call(RegionsSeeder::class);
    }
}
