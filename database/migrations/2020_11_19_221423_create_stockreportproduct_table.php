<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockreportproductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockreportproduct', function (Blueprint $table) {
            $table->id();
            $table->integer('stockreport_id')->unsigned();
            $table->integer('product_id')->unsigned();

            /* To be inputted by creator, needs to be signed */
            $table->float('quantity')->nullable();

            /* To be inputted by checker, for corrections */
            $table->float('correction')->nullable();

            /* To be inputted by checker */ 
            $table->float('received')->nullable();

            $table->enum('status',['checked','unchecked'])->default('unchecked');
            $table->integer('lost')->unsigned()->nullable();
            $table->string('remark')->nullable();
            $table->string('image')->nullable();

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
        Schema::dropIfExists('stockreportproduct');
    }
}
