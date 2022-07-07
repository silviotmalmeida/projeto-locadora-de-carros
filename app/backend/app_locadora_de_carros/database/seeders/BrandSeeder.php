<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importando a model
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // laço para criação dos dados
        for ($i = 1; $i <= 10; $i++) {

            // criando o objeto e salvando no BD
            Brand::create([
                'name' => "Marca $i"
            ]);
        }
    }
}
