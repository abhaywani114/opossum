<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvreceiptcarparklotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evreceiptcarparklot', function (Blueprint $table) {
            $table->id();
			// FK to ev_receipt.id
			$table->integer('evreceipt_id')->unsigned();
			// FK to carparklot.id
			$table->integer('carparklot_id')->unsigned();
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
        Schema::dropIfExists('evreceiptcarparklot');
    }
}
