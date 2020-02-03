<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            MasterHargasSeeder::class,
            MasterKorlapSeeder::class,
            DataSpbSeeder::class,
            DataTimbanganSeeder::class
        ]);
    }
}
