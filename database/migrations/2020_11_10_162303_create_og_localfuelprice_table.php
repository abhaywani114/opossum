<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOgLocalfuelpriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('og_localfuelprice', function (Blueprint $table) {
            $table->bigIncrements('id');
			// FK to prd_ogfuel.id
			$table->integer('ogfuel_id')->unsigned();
			// FK to company.id
			$table->integer('company_id')->unsigned();
			// FK to location.id
			$table->integer('location_id')->unsigned();
			$table->datetime('start');
            $table->integer('price')->unsigned();
            $table->integer('controller_price')->nullable()->unsigned();
            $table->dateTime('push_date')->nullable();
			// FK to users.id
			$table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('og_localfuelprice');
    }
}
