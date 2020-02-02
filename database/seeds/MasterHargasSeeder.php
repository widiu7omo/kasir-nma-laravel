<?php

use Illuminate\Database\Seeder;

class MasterHargasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\MasterHarga::class,30)->create();
    }
}
