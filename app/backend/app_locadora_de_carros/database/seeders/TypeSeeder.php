<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importando as models
use App\Models\Brand;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // consultando o valor da última marca cadastrado
        $brand_range = Brand::max('id');

        // laço para criação dos dados
        for ($i = 1; $i <= 100; $i++) {

            // sorteando a quantidade de portas
            $qtd_doors = rand(1, 5);

            // sorteando a quantidade de lugares
            $qtd_seats = rand(1, 20);

            // sorteando a existência de air bag
            $air_bag = rand(0, 1);

            // sorteando a existência de abs
            $abs = rand(0, 1);

            // sorteando uma marca
            $brand_id = rand(1, $brand_range);

            // criando o objeto e salvando no BD
            Type::create([
                'name' => "Modelo $i",
                'qtd_doors' => $qtd_doors,
                'qtd_seats' => $qtd_seats,
                'air_bag' => $air_bag,
                'abs' => $abs,
                'brand_id' => $brand_id
            ]);
        }
    }
}
