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
            'name' => 'IGMAR IRIGOYEN FALCON',
            'employee_code' => '6038164',
            'email' => 'iirigoyen@iusa.com.mx',
            'password' => bcrypt('Axs41'),
        ]);
    }
}
