<?php

namespace Database\Seeders;

use App\Models\Access\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrador',
            ]
        );

        Role::updateOrCreate(
            ['slug' => 'tecnico'],
            [
                'name' => 'Técnico',
            ]
        );
    }
}
