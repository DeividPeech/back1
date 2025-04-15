<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar departamentos aleatorios
        DB::table('departamentos')->insert([
            ['nombre' => 'Recursos Humanos'],
            ['nombre' => 'Soporte Técnico'],
            ['nombre' => 'Finanzas'],
        ]);
    }
}
