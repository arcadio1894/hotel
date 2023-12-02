<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{

    public function run()
    {
        //MODULO PERMISOS
        Permission::create([
            'name' => 'access_permission',
            'description' => 'Gestionar Roles y Permisos'
        ]);
        Permission::create([
            'name' => 'list_permission',
            'description' => 'Listar Permisos'
        ]);
        Permission::create([
            'name' => 'create_permission',
            'description' => 'Crear Permisos'
        ]);
        Permission::create([
            'name' => 'update_permission',
            'description' => 'Modificar Permisos'
        ]);
        Permission::create([
            'name' => 'destroy_permission',
            'description' => 'Eliminar Permisos'
        ]);

        //MODULO ROLES
        Permission::create([
            'name' => 'list_role',
            'description' => 'Listar Roles'
        ]);
        Permission::create([
            'name' => 'create_role',
            'description' => 'Crear Roles'
        ]);
        Permission::create([
            'name' => 'update_role',
            'description' => 'Modificar Roles'
        ]);
        Permission::create([
            'name' => 'destroy_role',
            'description' => 'Eliminar Roles'
        ]);


        //MODULO EMPLOYER

        //MODULO CUSTOMER

        //MODULO SEASON

        //MODULO ROOMTYPE
    }
}
