<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthreceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authreceipt', function (Blueprint $table) {
            $table->id();
			// This is native. NOT FK. Generated when Authorize is pressed.
			$table->string('auth_systemid');
			// FK to receipt.id. To be mapped later during PREPAID and POSTPAID
			$table->integer('receipt_id')->unsigned()->nullable();
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
        Schema::dropIfExists('authreceipt');
    }
}
