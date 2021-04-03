<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comm_receipt', function (Blueprint $table) {
            $table->id();
            $table->string('systemid');
			// FK to product.id
            $table->integer('product_id')->unsigned();
			// FK to vehicle.id
            $table->integer('vehicle_id')->unsigned();
			// Filled amount of fuel in money
            $table->integer('filled_amount')->unsigned();
			// Filled volume of fuel in litre
            $table->integer('filled_volume')->unsigned();
            $table->string('initial_totalizer')->nullable();
            $table->string('final_totalizer')->nullable();
            $table->datetime('time_started')->nullable();
            $table->datetime('time_completed')->nullable();
            // FK to users.id; user who generated this report
            $table->integer('staff_user_id')->unsigned();
            // FK to location.id; data from this location
            $table->integer('location_id')->unsigned();
            // FK to terminal.id; data from this terminal
            $table->integer('terminal')->unsigned();

            $table->softDeletes();
            $table->timestamps();
            $table->engine = "ARIA";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comm_receipt');
    }
}
