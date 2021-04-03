<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEoddetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/* This stores the actual final calculated values of a business day,
         * from Start to EOD. This is a summation of all the calculated values
         * from all the invididual receipts contained within Start to
         * EOD period. */
        Schema::create('eoddetails', function (Blueprint $table) {
            $table->id();
			/* FK to brancheod.id */
            $table->integer('eod_id')->unsigned();

            /* Record the start date */
            $table->datetime('startdate');

            $table->integer('total_amount')->unsigned();
            $table->integer('rounding');

            /* These 3 values must add up to total_amount + rounding */
            $table->integer('sales')->unsigned();
            $table->integer('sst')->unsigned();
            $table->integer('discount')->unsigned();

            /* Capturing values from UI */
            $table->integer('cash')->unsigned();
            $table->integer('cash_change')->unsigned();
            $table->integer('creditcard')->unsigned();

            $table->integer('wallet')->unsigned();

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
        Schema::dropIfExists('eoddetails');
    }
}
