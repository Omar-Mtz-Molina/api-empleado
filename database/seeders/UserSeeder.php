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
            'name' => 'Ramon Quiroz Trujillo',
            'employee_code' =>  '02542902',
            'email' => 'rquiroz@iusa.com.mx',
            'password' => bcrypt('her5x')

        ]);
    }
}
