<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = [
            'DNI',
            'PASAPORTE',
            'CARNÉ DE EXTRANJERIA',
            'LICENCIA DE CONDUCIR',
            'CARNÉ DE ESTUDIANTE',
            'RUC',
        ];

        foreach ($documentTypes as $documentType) {
            DB::table('document_types')->insert([
                'name' => $documentType,
            ]);
        }
    }
}
