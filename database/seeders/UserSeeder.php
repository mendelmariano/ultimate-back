<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        $usuariosHomologacao = [
            ['name' => 'admin',  'password' => bcrypt('password'), 'email' =>'mendelbsi@gmail.com']
          
        ];

        User::insert($usuariosHomologacao);
        //User::factory(10)->create();
    }
}
