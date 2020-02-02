<?php

use Illuminate\Database\Seeder;
use App\DataSpb;
class DataSpbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(DataSpb::class,100)->create();
    }
}
