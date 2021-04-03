<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrancheodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brancheod', function (Blueprint $table) {
            $table->id();
            // The user who had pressed "Branch EOD'
            // FK to users.id
            $table->integer('eod_presser_user_id')->unsigned();
            // FK to location.id
            $table->integer('location_id')->unsigned();
            // Which terminal the Branch EOD originated from
            // FK to terminal.id
            $table->integer('terminal_id')->unsigned();

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
        Schema::dropIfExists('brancheod');
    }
}
