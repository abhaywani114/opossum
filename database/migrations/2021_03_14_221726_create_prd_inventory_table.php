<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_inventory', function (Blueprint $table) {
		   
			$table->id();
			// FK to product.id
            $table->integer('product_id')->unsigned();
            $table->integer('price')->unsigned()->nullable();
            $table->integer('qty')->unsigned()->nullable();
            $table->integer('cogs')->unsigned()->nullable();
            $table->integer('cost')->unsigned()->nullable();
            $table->integer('pending')->unsigned()->default(0);
            $table->integer('loyalty')->unsigned()->nullable();

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
        Schema::dropIfExists('prd_inventory');
    }
}
