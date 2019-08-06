<?php

use Illuminate\Database\Seeder;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias')->insert([
            'nombre' => 'harinas',
            'descripcion' => 'todas las harinas',
        ]);

        DB::table('categorias')->insert([
            'nombre' => 'pastas',
            'descripcion' => 'todas las pastas',
        ]);
    }
}
