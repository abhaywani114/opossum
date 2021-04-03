<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdOpenitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_openitem', function (Blueprint $table) {
            $table->id();
			// FK to product.id
            $table->integer('product_id')->unsigned();
            $table->integer('price')->unsigned()->nullable();
            $table->integer('qty')->nullable();
            $table->integer('loyalty')->unsigned()->nullable();
			$table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('prd_openitem');
    }
}
