<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();

        User::updateOrCreate(
            ['email' => 'admin@preventivas.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'role_id' => $adminRole->id,
                'active' => true,
            ]
        );
    }
}
