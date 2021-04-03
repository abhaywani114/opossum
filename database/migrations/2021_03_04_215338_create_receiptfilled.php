<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptfilled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiptfilled', function (Blueprint $table) {
            $table->id();
			// FK to authreceipt.auth_systemid
            $table->string('auth_systemid')->unique();
			// This is filled amount, to be taken from PTS LastAmount
            $table->float('filled')->unsigned();
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
        Schema::dropIfExists('receiptfilled');
    }
}
