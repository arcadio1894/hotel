<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    public function run()
    {
        Role::create(['name' => 'admin_system', 'description' => 'Administrador del Sistema']);
        Role::create(['name' => 'admin_general', 'description' => 'Administrador General']);
        Role::create(['name' => 'receptionist', 'description' => 'Recepcionista']);
        Role::create(['name' => 'cleaning', 'description' => 'Limpieza']);
        Role::create(['name' => 'customer', 'description' => 'Cliente']);
    }
}
