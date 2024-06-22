<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sukijem',
                'email' => 'bankunit1@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Rendy Pangalila',
                'email' => 'bankunit4@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mawar Ratna Sari',
                'email' => 'bankunit5@gmail.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Arga Pangestu',
                'email' => 'bankunit6@gmail.com',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
