<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BankUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bank_units')->insert([
            [   'name' => 'Bank Unit 1',
                'user_id' => 'sukijem',
                'alamat' => 'Jl. Margahayu RT.01',
            ],
            [
                'name' => 'Bank Unit 2',
                'pengelola' => 'Rendy Pangalila',
                'alamat' => 'Jl. Margahayu RT.02',
            ],
            [
                'name' => 'Bank Unit 3',
                'pengelola' => 'Mawar Ratna Sari',
                'alamat' => 'Jl. Margahayu RT.03',
            ],
            [
                'name' => 'Bank Unit 4',
                'pengelola' => 'Arga Pangestu',
                'alamat' => 'Jl. Margahayu RT.04',
            ],
            [
                'name' => 'Bank Unit 5',
                'pengelola' => 'Sagara Bagaskara',
                'alamat' => 'Jl. Margahayu RT.05',
            ],
            [
                'name' => 'Bank Unit 6',
                'pengelola' => 'Andini Revelyn',
                'alamat' => 'Jl. Margahayu RT.06',
            ],
        ]);
    }
}
