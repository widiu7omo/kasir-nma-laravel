<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MasterHarga;
use Faker\Generator as Faker;

$factory->define(MasterHarga::class, function (Faker $faker) {
    return [
        'harga'=>$faker->randomNumber(4),
        'tanggal'=>$faker->date()
    ];
});
