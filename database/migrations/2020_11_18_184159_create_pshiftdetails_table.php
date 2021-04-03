<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePshiftdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/* This stores the actual final calculated values of shift,
         * from start_pshift to end_pshift. This is a summation of all the
         * calculated values from all the invididual receipts contained
         * within the shift period. */
        Schema::create('pshiftdetails', function (Blueprint $table) {
            $table->id();

            /* FK to pshift.id; terminal_id and location_id are
             * accessed via pshift  */
            $table->integer('pshift_id')->unsigned();

            /* FK to eoddetails.id */
            $table->integer('eoddetails_id')->unsigned();

            /* Record the start datetime of the shift */
            $table->datetime('startdate') ;

            $table->integer('total_amount')->unsigned();
            $table->integer('rounding');

            /* These 3 values must add up to total_amount + rounding */
            $table->integer('sales')->unsigned();
            $table->integer('sst')->unsigned();
            $table->integer('discount')->unsigned();

            /* Capturing values from screen B */
            $table->integer('cash')->unsigned();
            $table->integer('cash_change')->unsigned();
            $table->integer('creditcard')->unsigned();

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
        Schema::dropIfExists('pshiftdetails');
    }
}
