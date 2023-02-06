<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = DB::table('users')->where('email', 'laurence.baylac@hotdogsolutions.com')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Laurence',
                'lastname' => 'Baylac',
                'email' => 'laurence.baylac@hotdogsolutions.com',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'brian@rapidtraining.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Brian',
                'lastname' => 'Glover-Smith',
                'email' => 'brian@rapidtraining.co.uk',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'paul@rapidtraining.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Paul',
                'lastname' => 'Wilkinson',
                'email' => 'paul@rapidtraining.co.uk',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'graham.s@rapidtraining.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Graham',
                'lastname' => 'S',
                'email' => 'graham.s@rapidtraining.co.uk',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'chris@rapidtraining.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Chris',
                'lastname' => 'Admin',
                'email' => 'chris@rapidtraining.co.uk',
                'phone' => '07972584301',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'natalie@adkca.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Natalie',
                'lastname' => 'Langan',
                'email' => 'natalie@adkca.co.uk',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'ryan@rapidtraining.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Ryan',
                'lastname' => 'Decarteret',
                'email' => 'ryan@rapidtraining.co.uk',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }

        $admin = DB::table('users')->where('email', 'admin@rapidtraining.co.uk')->first();
        if (empty($admin)) {
            DB::table('users')->insert([
                'firstname' => 'Anne',
                'lastname' => 'Bland',
                'email' => 'admin@rapidtraining.co.uk',
                'password' => bcrypt('123123123'),
                'role' => '2',
                'gdpr' => true,
            ]);
        }
    }
}
