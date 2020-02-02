<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MasterKorlap;
use Faker\Generator as Faker;

$factory->define(MasterKorlap::class, function (Faker $faker) {
    return [
        //
        'nama_korlap'=>$faker->firstName.$faker->lastName,
        'created_at'=>$faker->dateTime
    ];
});
