<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->truncate();

        DB::table('levels')->insert([
            'crm_id' => 'Level_bm_1',
            'course_id' => 1,
            'level' => 1,
            'price' => 795,
            'fee' => 360,
            'title' => 'FUNDAMENTALS OF HIGH PERFORMANCE RIDING',
            'description' => '3 days road coaching',
            'track_dates' => 0,
            'road_dates' => 3,
        ]);

        DB::table('levels')->insert([
            'crm_id' => 'Level_bm_2',
            'course_id' => 1,
            'level' => 2,
            'price' => 1195,
            'fee' => 360,
            'title' => 'DEVELOPING YOUR POTENTIAL',
            'description' => '1 day track plus 3 days road coaching',
            'track_dates' => 1,
            'road_dates' => 3,
        ]);

        DB::table('levels')->insert([
            'crm_id' => 'Level_bm_3',
            'course_id' => 1,
            'level' => 3,
            'price' => 1195,
            'fee' => 360,
            'title' => 'BIKEMASTER™',
            'description' => '1 day track plus 3 days road coaching',
            'track_dates' => 1,
            'road_dates' => 3,
        ]);


        DB::table('levels')->insert([
            'crm_id' => 'Level_rm_1',
            'course_id' => 2,
            'level' => 1,
            'price' => 795,
            'fee' => 360,
            'title' => 'FUNDAMENTALS OF HIGH PERFORMANCE RIDING',
            'description' => '3 days road coaching',
            'track_dates' => 0,
            'road_dates' => 3,
        ]);

        DB::table('levels')->insert([
            'crm_id' => 'Level_rm_2',
            'course_id' => 2,
            'level' => 2,
            'price' => 795,
            'fee' => 360,
            'title' => 'DEVELOPING YOUR POTENTIAL',
            'description' => '3 days road coaching ',
            'track_dates' => 0,
            'road_dates' => 3,
        ]);

        DB::table('levels')->insert([
            'crm_id' => 'Level_rm_3',
            'course_id' => 2,
            'level' => 3,
            'price' => 795,
            'fee' => 360,
            'title' => 'ROADMASTER™',
            'description' => '3 days road coaching',
            'track_dates' => 0,
            'road_dates' => 3,
        ]);


        DB::table('levels')->insert([
            'crm_id' => 'Level_b_1',
            'course_id' => 3,
            'level' => 0,
            'price' => 265,
            'fee' => 120,
            'title' => 'One-off coaching focused on fine-tuning riding technique',
            'description' => 'Based on intensive 4 hours (solo) or extended 8 hours (paired)',
            'track_dates' => 1,
            'road_dates' => 1,
        ]);

        DB::table('levels')->insert([
            'crm_id' => 'Level_b_fd',
            'course_id' => 3,
            'level' => 0,
            'price' => 395,
            'fee' => 180,
            'title' => 'One-off coaching focused on fine-tuning riding technique Full Day',
            'description' => 'Based on intensive full day',
            'track_dates' => 1,
            'road_dates' => 1,
        ]);
    }
}
