<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Buscar o crear el super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'aravenanacho@gmail.com'],
            [
                'name' => 'Nacho Aravena',
                'password' => Hash::make('Jose1997'),
                'tipo_usuario' => 'admin',
                'admin_role' => 'super_admin',
            ]
        );

        // Si ya existe, actualizar los campos
        if (!$superAdmin->wasRecentlyCreated) {
            $superAdmin->update([
                'name' => 'Nacho Aravena',
                'password' => Hash::make('Jose1997'),
                'tipo_usuario' => 'admin',
                'admin_role' => 'super_admin',
            ]);

            $this->command->info('Super Admin actualizado: ' . $superAdmin->email);
        } else {
            $this->command->info('Super Admin creado: ' . $superAdmin->email);
        }
    }
}
