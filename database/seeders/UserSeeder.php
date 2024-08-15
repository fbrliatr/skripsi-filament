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
                'name' => 'Bank Pusat',
                'email' => 'bankpusat@gmail.com',
                'roles_id' => '1',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sukijem',
                'email' => 'bankunit1@gmail.com',
                'role' => '2',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Rendy Pangalila',
                'email' => 'bankunit4@gmail.com',
                'roles_id' => '2',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mawar Ratna Sari',
                'email' => 'bankunit5@gmail.com',
                'roles_id' => '2',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Arga Pangestu',
                'email' => 'bankunit6@gmail.com',
                'roles_id' => '2',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Ayu Larasati',
                'email' => 'ayularas@gmail.com',
                'roles_id' => '3',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Aika Ardana',
                'email' => 'Aikarda@gmail.com',
                'roles_id' => '3',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Fidela Lathifa',
                'email' => 'fidelala@gmail.com',
                'roles_id' => '3',
                'password' => Hash::make('password'),
            ],
        ]);
    }
}
