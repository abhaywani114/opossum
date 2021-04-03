<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantlinkrelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchantlinkrelation', function (Blueprint $table) {
            $table->id();
            // FK to company.id
            $table->integer('company_id')->unsigned();
            // FK to merchantlink.id
            $table->integer('merchantlink_id')->unsigned();
            // FK to location.id
            $table->integer('default_location_id')->unsigned()->nullable();
            $table->enum('ptype', ['supplier','dealer'])->nullable();
            $table->enum('status', ['active','inactive'])->default('inactive');
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
        Schema::dropIfExists('merchantlinkrelation');
    }
}
