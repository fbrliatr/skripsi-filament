<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Transaksi;
use App\Models\TransaksiWarga;
use App\Models\Warga;

class TransaksiWargaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransaksiWarga::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'transaksi_id' => Transaksi::factory(),
            'warga_id' => Warga::factory(),
            'berat' => $this->faker->numberBetween(-10000, 10000),
            'price' => $this->faker->numberBetween(-10000, 10000),
            'transaksi_warga_id' => TransaksiWarga::factory(),
        ];
    }
}
