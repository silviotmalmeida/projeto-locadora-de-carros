<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// importando a model
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'User';
        $user->email = 'user@email.com';
        $user->password = bcrypt('123456789');
        $user->save();
    }
}
