<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_pays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->nullable()->constrained("reservations");
            $table->decimal("pay", 9,2)->nullable();
            $table->dateTime("date_pay");
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
        Schema::dropIfExists('reservation_pays');
    }
}
