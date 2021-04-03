<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockreportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockreport', function (Blueprint $table) {
            $table->id();
			// $a = new SystemID('stockreport');
            $table->string('systemid');
            // User who created this stockreport
            $table->integer('creator_user_id')->unsigned();
            // User who received the Tracking Report
            $table->integer('receiver_user_id')->unsigned()->nullable();

            $table->enum('status',[
                'pending','confirmed','in_progress','cancelled','received'
            ])->default('pending');

            // Transfer => Tracking Reports
            $table->enum('type', [
                'voided','transfer','stockin','stockout',
                'stocktake','cforward','refundcp','daily_variance'
            ]);

            // FK to location.id; the originating/from location
            $table->integer('location_id')->unsigned();
            // FK to location.id; the destination location
			$table->integer('dest_location_id')->unsigned()->nullable();
            // The timestamp which Receive is pressed
            $table->timestamp('received_tstamp');

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
        Schema::dropIfExists('stockreport');
    }
}
