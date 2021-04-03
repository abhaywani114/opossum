<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductbmatrixbarcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productbmatrixbarcode', function (Blueprint $table) {
			$table->bigIncrements('id');
			// FK to product.id
			$table->integer('product_id')->unsigned();
			// Stores the bmatrix barcodes for this product
			$table->string('bmatrixbarcode');
			// Stores the bmatrix barcodes in JSON for this product
			$table->string('bmatrixbarcodejson');
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
        Schema::dropIfExists('productbmatrixbarcode');
    }
}
