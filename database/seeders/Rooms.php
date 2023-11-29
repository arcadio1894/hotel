<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\Models\Season;
use Illuminate\Database\Seeder;

class Rooms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoomType::create([
            'name' => 'Standard',
            'description' => 'Habitacion normal',
            'capacity' => 2,
        ]);

        RoomType::create([
            'name' => 'Suite',
            'description' => 'Habitación grante',
            'capacity' => 4,
        ]);
        $roomType = RoomType::first();
        Room::create([
            'room_type_id' => $roomType->id,
            'level' => 1,
            'number' => 101,
            'status' => 'D',
        ]);

        Room::create([
            'room_type_id' => $roomType->id,
            'level' => 2,
            'number' => 201,
            'status' => 'O',
        ]);

        Season::create([
            'name' => 'Navidad',
            'start_date' => '2023-12-15',
            'end_date' => '2024-01-15',
        ]);

        Season::create([
            'name' => 'San Valentin',
            'start_date' => '2024-02-10',
            'end_date' => '2024-02-20',
        ]);

        $room = Room::first();
        $season = Season::first();

        RoomPrice::create([
            'room_id' => $room->id,
            'season_id' => $season->id,
            'duration_hours' => 24,
            'price' => 150.00,
        ]);

    }
}
