<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->enum('document_type', ['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE']);
            $table->string('document'); 
            $table->string('name');
            $table->string('lastname');
            $table->string('phone');
            $table->string('email');
            $table->string('birth');
            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
