<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePshiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/* pshift.created_at is actually the end shift timestamp */
        Schema::create('pshift', function (Blueprint $table) {
            $table->id();
            $table->integer('eoddetails_id')->unsigned()->nullable();
            // FK to users.id, to capture the person who
            // clicked on "Change Shift"
            $table->integer('endpshift_presser_user_id')->unsigned()->
				nullable();
            // FK to terminal.id
            $table->integer('terminal_id')->unsigned()->nullable();
            // FK to location.id
            $table->integer('location_id')->unsigned()->nullable();
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
        Schema::dropIfExists('pshift');
    }
}
