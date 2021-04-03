<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductbarcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productbarcode', function (Blueprint $table) {
            $table->bigIncrements('id');
			// FK to product.id
			$table->integer('product_id')->unsigned();
			// FK to merchantproduct.id
			$table->integer('merchantproduct_id')->unsigned();
			$table->string('name')->nullable();
			$table->string('barcode')->nullable();
			$table->string('barcode_type')->default('C128');;
			$table->string('sku')->nullable();
			$table->date('expirydate')->nullable();
			$table->date('startdate')->nullable();
			$table->integer('quantity')->nullable();
			$table->text('notes')->nullable();
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
        Schema::dropIfExists('productbarcode');
    }
}
