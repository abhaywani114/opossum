<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('systemid');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            // public/images/product/{product_id}/{photo_1}
            $table->string('photo_1')->nullable();
            // public/images/product/{product_id}/thumb/{thumbnail_1}
            $table->string('thumbnail_1')->nullable();
            $table->string('sku')->nullable();
            $table->enum('ptype',[
                'inventory','services','rawmaterial',
                'voucher','warranty','membership','drum',
                'customization','ecommerce','oilgas','openitem'
            ])->default('inventory');

            // FK to prd_category.id
            $table->integer('prdcategory_id')->unsigned()->nullable();
            // FK to prd_subcategory.id
            $table->integer('prdsubcategory_id')->unsigned()->nullable();
            // FK to prdcategory.id
            $table->integer('prdprdcategory_id')->unsigned()->nullable();

            $table->integer('brand_id')->unsigned()->nullable();

            $table->enum('status',['active','inactive'])->
				default('active');

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
        Schema::dropIfExists('product');
    }
}
