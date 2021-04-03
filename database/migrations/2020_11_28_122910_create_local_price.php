<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('localprice', function (Blueprint $table) {
            $table->id();
			$table->integer('product_id')->unsigned();
            $table->integer('upper_price')->unsigned()->nullable();
            $table->integer('recommended_price')->unsigned()->nullable();
            $table->integer('lower_price')->unsigned()->nullable();
			$table->boolean('active')->default(false);
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
        Schema::dropIfExists('localprice');
    }
}
