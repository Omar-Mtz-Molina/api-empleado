<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'ARCHUNDIA SANCHEZ IRAN MICHELL',
            'employee_code' => '15101086',
            'email' => 'imarchundia@iusa.com.mx',
            'password' => bcrypt('B0xJYX0'),
        ]);
    }
}
