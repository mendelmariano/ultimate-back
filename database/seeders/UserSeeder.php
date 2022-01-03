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
            ['name' => 'admin', 'username'=>'mendelmariano', 'password' => bcrypt('password'), 'email' =>'mendelbsi@gmail.com', 'whatsapp' =>'62992111954', 'menuIds' =>'1,2', 'active'=>'1']

        ];

        User::insert($usuariosHomologacao);
        //User::factory(10)->create();
    }
}
