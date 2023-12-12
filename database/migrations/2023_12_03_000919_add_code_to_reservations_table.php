<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
           // Agrega el nuevo campo "code"
           $table->string('code')->nullable()->after('total_guest');

           // Modifica los campos existentes para que sean nulos
           $table->foreignId('customer_id')->nullable()->change();
           $table->foreignId('employer_id')->nullable()->change();
           $table->foreignId('status_id')->nullable()->change();
           $table->foreignId('paymethod_id')->nullable()->change();
           $table->dateTime('start_date')->nullable()->change();
           $table->dateTime('end_date')->nullable()->change();
           $table->decimal('initial_pay', 10, 2)->nullable()->change();
           $table->integer('total_guest')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
          // Revertir los cambios realizados en el método "up"
          $table->dropColumn('code');
          $table->foreignId('customer_id')->nullable(false)->change();
          $table->foreignId('employer_id')->nullable(false)->change();
          $table->foreignId('status_id')->nullable(false)->change();
          $table->foreignId('paymethod_id')->nullable(false)->change();
          $table->dateTime('start_date')->nullable(false)->change();
          $table->dateTime('end_date')->nullable(false)->change();
          $table->decimal('initial_pay', 10, 2)->nullable(false)->change();
          $table->integer('total_guest')->nullable(false)->change();
        });
    }
}
