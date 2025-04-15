<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador sin departamento
        DB::table('users')->insert([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('123'), // Asegúrate de usar una contraseña segura
            'role_id' => 1, // ID de administrador (dependiendo de tu base de datos, asegúrate de que sea el rol correcto)
            'departamento_id' => null, // No tiene departamento
        ]);

        // Crear usuario para el departamento de Recursos Humanos
        DB::table('users')->insert([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@empresa.com',
            'password' => Hash::make('123'), // Contraseña segura
            'role_id' => 3, // ID del rol "departamento" (asegúrate de que este rol esté correcto)
            'departamento_id' => 1, // ID del departamento de Recursos Humanos
        ]);

        // Crear usuario para el departamento de Finanzas
        DB::table('users')->insert([
            'name' => 'María Gómez',
            'email' => 'maria.gomez@empresa.com',
            'password' => Hash::make('123'), // Contraseña segura
            'role_id' => 3, // ID del rol "departamento"
            'departamento_id' => 2, // ID del departamento de Finanzas
        ]);
    }
}
