<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        $this->call(BrandSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(UserSeeder::class);
    }
}
