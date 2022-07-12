<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importando as models
use App\Models\Type;
use App\Models\Car;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // consultando o valor do último modelo cadastrado
        $type_range = Type::max('id');

        // laço para criação dos dados
        for ($i = 1; $i <= 100; $i++) {

            // caracteres possiveis na placa
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

            // montando a placa
            $plate = '';
            for ($x = 0; $x < 3; $x++) {
                $plate = $plate . $chars[rand(0, strlen($chars) - 1)];
            }
            $plate = $plate . '-' . rand(0, 9) . str_pad($i, 3, '0', STR_PAD_LEFT);

            // sorteando a quilometragem
            $km = rand(1, 9999);

            // sorteando a disponibilidade
            $available = rand(0, 1);

            // sorteando um modelo
            $type_id = rand(1, $type_range);

            // criando o objeto e salvando no BD
            Car::create([
                'plate' => $plate,
                'km' => $km,
                'available' => $available,
                'type_id' => $type_id
            ]);
        }
    }
}
