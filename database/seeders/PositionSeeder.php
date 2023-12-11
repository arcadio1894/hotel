<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'name' => 'Limpieza',
            'description' => 'Limpieza de habitaciones'
        ]);

        Position::create([
            'name' => 'Cajero',
            'description' => 'Pagos de servicio del hotel'
        ]);
        Position::create([
            'name' => 'Recepción',
            'description' => 'Reserva de habitaciones y atención al cliente'
        ]);
        Position::create([
            'name' => 'Camarero',
            'description' => 'Servicio de comida y otros al cuarto'
        ]);
        Position::create([
            'name' => 'Cocinero',
            'description' => 'Preparar la comida del hotel'
        ]);
        Position::create([
            'name' => 'Seguridad',
            'description' => 'Seguridad de los clientes y local'
        ]);
        Position::create([
            'name' => 'Sin Rol',
            'description' => 'Sin rol'
        ]);

    }
}
