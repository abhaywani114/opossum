<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdbmatrixbarcodegen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prdbmatrixbarcodegen', function (Blueprint $table) {
            $table->bigIncrements('id');
			// FK to product.id
			$table->integer('product_id')->unsigned();
			// FK to bmatrix.id
			$table->integer('bmatrix_id')->unsigned();
			// Subcategory ID is in: bmatrix.subcategory_id

			// Boolean to store whether barcode matrix has been generated
			$table->boolean('bmbarcode_generated')->default(false);
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
        Schema::dropIfExists('prdbmatrixbarcodegen');
    }
}
