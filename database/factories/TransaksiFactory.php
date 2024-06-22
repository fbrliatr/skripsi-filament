<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BankUnit;
use App\Models\Transaksi;
use App\Models\Warga;
use App\Models\WargaBankUnit;

class TransaksiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaksi::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'bank_unit_id' => BankUnit::factory(),
            'warga_id' => Warga::factory(),
            'berat' => $this->faker->numberBetween(-10000, 10000),
            'kategori' => $this->faker->word(),
            'status' => $this->faker->word(),
            'tanggal' => $this->faker->dateTime(),
            'price' => $this->faker->numberBetween(-100000, 100000),
            // 'warga_bank_unit_id' => WargaBankUnit::factory(),
        ];
    }
}
