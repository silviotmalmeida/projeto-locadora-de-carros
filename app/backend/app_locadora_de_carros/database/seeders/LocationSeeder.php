<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importando as models
use App\Models\Location;
use App\Models\Car;
use App\Models\Client;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // consultando o valor do último cliente cadastrado
        $client_range = Client::max('id');

        // consultando o valor do último carro cadastrado
        $car_range = Car::max('id');

        // laço para criação dos dados
        for ($i = 1; $i <= 250; $i++) {

            // sorteando a quantidade estimada de dias da locação
            $estimated_days = rand(1, 30);

            // sorteando a quantidade real de dias da locação
            $real_days = rand(1, 30);

            // sorteando a data inicial
            $initial_date = date("Y-m-d\TH:i:sO", mt_rand(1, time()));

            // sorteando a data prevista
            $final_date_estimated = date("Y-m-d\TH:i:sO", strtotime("+$estimated_days days", strtotime($initial_date)));

            // sorteando a data prevista
            $final_date = date("Y-m-d\TH:i:sO", strtotime("+$real_days days", strtotime($initial_date)));

            // sorteando o valor diário
            $daily_value = round(rand(30, 100) / 0.333, 2);

            // sorteando a quilometragem inicial
            $initial_km = rand(1, 9999);

            // sorteando a quilometragem inicial
            $final_km = $initial_km + rand(50, 500);

            // sorteando um cliente
            $client_id = rand(1, $client_range);

            // sorteando um carro
            $car_id = rand(1, $car_range);

            // criando o objeto e salvando no BD
            Location::create([
                'initial_date' => $initial_date,
                'final_date_estimated' => $final_date_estimated,
                'final_date' => $final_date,
                'daily_value' => $daily_value,
                'initial_km' => $initial_km,
                'final_km' => $final_km,
                'client_id' => $client_id,
                'car_id' => $car_id
            ]);
        }
    }
}