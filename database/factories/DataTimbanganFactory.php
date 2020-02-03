<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DataTimbangan;
use Faker\Generator as Faker;

$factory->define(DataTimbangan::class, function (Faker $faker) {
    return [
        'no_ticket' => $faker->dateTime,
        'tanggal_masuk' => $faker->date(),
        'no_kendaraan' => $faker->randomAscii,
        'pelanggan' => $faker->name,
        'tandan' => $faker->randomNumber(5),
        'first_weight' => $faker->randomNumber(3),
        'second_weight' => $faker->randomNumber(3),
        'netto_weight' => $faker->randomNumber(5),
        'potongan_gradding' => $faker->randomNumber(3),
        'setelah_gradding' => $faker->randomNumber(5),
    ];
});
