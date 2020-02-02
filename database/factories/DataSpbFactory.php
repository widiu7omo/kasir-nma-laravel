<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DataSpb;
use Faker\Generator as Faker;

$factory->define(DataSpb::class, function (Faker $faker) {
    return [
        'range_spb'=>$faker->randomNumber(9)."-".$faker->randomNumber(9),
        'master_korlap_id'=>App\MasterKorlap::all()->random()->id,
        'tanggal_pengambilan'=>$faker->date()
    ];
});
