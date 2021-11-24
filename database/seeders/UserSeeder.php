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
            'name' => 'MARCO ANTONIO LOPEZ VELDAÑEZ',
            'employee_code' => '6038261',
            'email' => 'marco@iusa.com.mx',
            'password' => bcrypt('A0xJYS'),
        ]);
    }
}
