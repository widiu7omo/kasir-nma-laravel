<?php

use Illuminate\Database\Seeder;

class MasterKorlapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\MasterKorlap::class,10)->create();
    }
}
