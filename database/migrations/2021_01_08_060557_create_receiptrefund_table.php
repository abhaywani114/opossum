<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptrefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiptrefund', function (Blueprint $table) {
            $table->id();
			// FK to receipt.id
            $table->integer('receipt_id')->unsigned()->unique();
			// Staff who had confirmed the refund
            $table->integer('staff_user_id')->unsigned();
			// Amount to be refunded
            $table->float('refund_amount')->unsigned();
			// Quantity to be refunded
            $table->float('qty')->unsigned();
			// Text for the transaction
            $table->string('description')->nullable();
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
        Schema::dropIfExists('receiptrefund');
    }
}
