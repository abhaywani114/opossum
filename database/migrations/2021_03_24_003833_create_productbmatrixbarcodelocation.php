<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductbmatrixbarcodelocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productbmatrixbarcodelocation', function (Blueprint $table) {
            $table->bigIncrements('id');
			// FK to productbmatrixbarcode.id
			$table->integer('productbmatrixbarcode_id')->unsigned();
			// FK to location.id
			$table->integer('location_id')->unsigned();
			// FK to rack.id
			$table->integer('rack_id')->unsigned();
			// FK to merchant.id;
			// Relates productbmatrixbarcodelocation to a franchisee
			$table->integer('franchisee_merchant_id')->unsigned()->nullable();
			$table->integer('quantity');
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
        Schema::dropIfExists('productbmatrixbarcodelocation');
    }
}
