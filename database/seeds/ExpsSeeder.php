<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('exps')->truncate();

        DB::table('exps')->insert([
            'crm_id' => 'Exp_1',
            'value' => 1,
            'title' => 'Novice',
            'description' => 'A rider who has received no post-test training and has under 5 years road experience',
        ]);

        DB::table('exps')->insert([
            'crm_id' => 'Exp_2',
            'value' => 2,
            'title' => 'Intermediate',
            'description' => 'A rider who has over 5 years road experience but no post-test experience',
        ]);

        DB::table('exps')->insert([
            'crm_id' => 'Exp_3',
            'value' => 3,
            'title' => 'Experienced',
            'description' => 'A rider who has taken post-test training and/or passed one of the recognised advanced motorcycle tests',
        ]);
    }
}
