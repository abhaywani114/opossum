<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('systemid')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('fullname')->nullable();
            $table->string('username')->nullable();

            $table->enum('type',['staff','admin'.'external'])->default('staff');

            $table->enum('status',['pending','active','inactive'])->
				default('pending');
            $table->string('access_code')->nullable();
            $table->timestamp('last_login')->nullable();
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
        Schema::dropIfExists('users');
    }
}
