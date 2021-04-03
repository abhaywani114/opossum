<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loginout', function (Blueprint $table) {
            $table->id();
			$table->datetime('login');
			$table->datetime('logout')->nullable();
			// FK to location.id
			$table->integer('location_id')->unsigned();
			// FK to users.id
			$table->integer('user_id')->unsigned();
			// FK to pshift.id
			$table->integer('shift_id')->unsigned();
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
        Schema::dropIfExists('loginout');
    }
}
