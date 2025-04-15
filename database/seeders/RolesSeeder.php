<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar roles: Administrador y Contribuyente
        DB::table('roles')->insert([
            ['nombre' => 'administrador'],
            ['nombre' => 'contribuyente'],
            ['nombre' => 'departamente'],
        ]);
    }
}
