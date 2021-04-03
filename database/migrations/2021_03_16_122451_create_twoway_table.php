<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwowayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twoway', function (Blueprint $table) {
            $table->id();
            $table->integer('initiator_user_id')->unsigned();
            $table->integer('responder_user_id')->unsigned();
            $table->enum('status',
                ['active','inactive'])->default('active');
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
        Schema::dropIfExists('twoway');
    }
}
