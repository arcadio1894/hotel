<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DocumentTypesSeeder::class);
        $this->call(PaymentMethodsSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoomsSeeder::class);
        $this->call(StatusSeeder::class);
    }
}
