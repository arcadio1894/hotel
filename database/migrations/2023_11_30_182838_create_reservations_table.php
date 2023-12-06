<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('employer_id')->constrained('employers');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('paymethod_id')->constrained('paymethods');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('initial_pay', 10, 2);
            $table->integer('total_guest');
            $table->softDeletes();
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
        Schema::dropIfExists('reservations');
    }
}
