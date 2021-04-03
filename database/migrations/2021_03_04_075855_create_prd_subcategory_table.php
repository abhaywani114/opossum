<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrdSubcategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prd_subcategory', function (Blueprint $table) {
            $table->id();
			// FK to prd_category.id
            $table->integer('category_id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_matrix')->default(false);
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
        Schema::dropIfExists('prd_subcategory');
    }
}
