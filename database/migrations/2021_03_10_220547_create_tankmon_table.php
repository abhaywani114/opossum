<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTankmonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tankmon', function (Blueprint $table) {
            $table->id();
			// FK to tank.id
            $table->integer('tank_id')->unsigned();
			// Tank filling in %
            $table->integer('tank_filling_pct')->unsigned();
			// Product height in mm
            $table->integer('product_mm')->unsigned();
			// Water height in mm
            $table->integer('water_mm')->unsigned();
			// Temperature in celcius
            $table->integer('temperature_c')->unsigned();
			// Produt height in litre
            $table->integer('product_l')->unsigned();
			// Water height in litre
            $table->integer('water_l')->unsigned();
			// Ullage in litre
            $table->integer('ullage_l')->unsigned();
			// Temperature compensated in litre
            $table->integer('tc_volume')->unsigned();

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
        Schema::dropIfExists('tankmon');
    }
}
