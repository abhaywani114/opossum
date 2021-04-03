<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/* Table is to the first layer of a product category:
		   product
		   1. category
		   2. subcategory
		   3. product
		*/
        Schema::create('prd_category', function (Blueprint $table) {
            $table->id();
			$table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('enable')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->engine = "ARIA";
        });


		/* This is not a bug. This is the third layer, unfortunately just differs
		   from a */
        Schema::create('prdcategory', function (Blueprint $table) {
            $table->id();
			// FK to prd_category.id
			$table->integer('category_id')->unsigned();
			// FK to prd_subcategory.id
            $table->integer('subcategory_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('prd_category');
        Schema::dropIfExists('prdcategory');
    }
}
