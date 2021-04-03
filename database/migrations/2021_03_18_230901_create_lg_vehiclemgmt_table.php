<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLgVehiclemgmtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lg_vehiclemgmt', function (Blueprint $table) {
            $table->id();
			$table->string('systemid');
			// FK to merchant.id; relates vehicles to the merchant
			$table->integer('merchant_id')->unsigned();
			// This is the same as Number Plate displayed
			$table->string('vehicle_license')->nullable();
			$table->string('type')->nullable();
			$table->integer('max_volumetric')->unsigned()->nullable();
			$table->string('capabilities')->nullable()->nullable();
			$table->string('ownership')->nullable();
			$table->integer('deliveryman_user_id')->unsigned()->nullable();
			// FK to location.id
			$table->integer('location_id')->unsigned()->nullable();
            // Gurrent GPS coordinates, stored in degrees
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
            // This for access card number
            $table->string('rfid_no')->nullable();
            $table->enum('status', ['rfid_active','rfid_inactive'])->
				default('rfid_active');

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
        Schema::dropIfExists('lg_vehiclemgmt');
    }
}
