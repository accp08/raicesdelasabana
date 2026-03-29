<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@raicesdelasabana.com');
        $password = env('ADMIN_PASSWORD', 'ChangeMe123!');

        if (!User::where('email', $email)->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }
    }
}
