<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->truncate();

        DB::table('courses')->insert([
            'crm_id' => 'Course_bm',
            'title' => 'BIKEMASTER™',
            'description' => 'When performance really matters, BIKEMASTER is for you.',
        ]);

        DB::table('courses')->insert([
            'crm_id' => 'Course_rm',
            'title' => 'ROADMASTER™',
            'description' => 'A three level programme for riders with a passion for the open road.',
        ]);

        DB::table('courses')->insert([
            'crm_id' => 'Course_b',
            'title' => 'BESPOKE',
            'description' => 'One-off coaching focused on fine-tuning riding technique.',
        ]);
    }
}
