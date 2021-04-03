<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarparklotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carparklot', function (Blueprint $table) {
            $table->id();
			$table->string('systemid');
			$table->integer('lot_no')->unsigned();
			// This is in monetary value, stored in cents
			$table->integer('rate')->unsigned();
			$table->integer('kwh')->unsigned();
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
        Schema::dropIfExists('carparklot');
    }
}
