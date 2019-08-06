<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'idrol' => 1,
            'nombre' => 'Elder Orlando',
            'usuario' => 'elder',
            'password' => bcrypt('secret'),
        ]);
    }
}
