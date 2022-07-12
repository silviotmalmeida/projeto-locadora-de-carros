<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importando as models
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // laço para criação dos dados
        for ($i = 1; $i <= 50; $i++) {

            // criando o objeto e salvando no BD
            Client::create([
                'name' => "Cliente $i"
            ]);
        }
    }
}
