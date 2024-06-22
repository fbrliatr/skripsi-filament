<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankUnit;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Warga;

class WargaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Warga::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'bank_unit_id' => BankUnit::factory(),
            'transaksi_id' => Transaksi::factory(),
            'name' => $this->faker->name(),
            'alamat' => $this->faker->text(),
        ];
    }
}
