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
            'name' => 'AMAIRANI SARAI ALBINO ERASMO',
            'employee_code' => '6053779',
            'email' => 'asalbino@iusa.com.mx',
            'password' => bcrypt('0xs51'),
        ]);
    }
}
