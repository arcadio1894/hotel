<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            ['name' => 'Yape'],
            ['name' => 'Plin'],
            ['name' => 'Efectivo'],
            ['name' => 'Transferencia'],
            ['name' => 'Tarjeta de Crédito'],
            ['name' => 'Tarjeta de Débito'],
        ];

        DB::table('paymethods')->insert($paymentMethods);
    }
}
