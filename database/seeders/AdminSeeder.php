<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crea la cuenta del administrador. forceFill es necesario porque "rol"
 * no es asignable en masa (medida contra la escalada de privilegios).
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@huellitas.pe']);

        $admin->forceFill([
            'name' => 'Administrador',
            'password' => Hash::make('password123'),
            'rol' => 'admin',
            'telefono' => '999999999',
            'direccion' => 'Arequipa',
            'distrito' => 'Arequipa',
        ])->save();
    }
}