<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnewayrelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onewayrelation', function (Blueprint $table) {
            $table->id();
            $table->integer('oneway_id')->unsigned();
            $table->integer('default_location_id')->
				unsigned()->nullable();
            $table->enum('ptype', ['supplier','dealer'])->
				nullable();
            $table->enum('status', ['active','inactive'])->
                default('inactive');
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
        Schema::dropIfExists('onewayrelation');
    }
}
