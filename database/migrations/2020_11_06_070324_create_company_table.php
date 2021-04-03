<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();;
			$table->string('business_reg_no')->nullable();;
            $table->string('systemid')->nullable();;

			// public/image/company/{company_id}/corporate_logo/logo_file.png
            $table->string('corporate_logo')->nullable();
			$table->integer('owner_user_id')->unsigned()->nullable();;
			$table->string('gst_vat_sst')->nullable();;
			// FK to currency.id, company:currency = 1:1
            $table->integer('currency_id')->unsigned()->nullable();
			// Office Address 
			$table->string('office_address')->nullable();
			// Company-wide loyalty programme:
			// This is number of points in exchange for 1 MYR.
            $table->integer('loyalty_pgm')->unsigned()->nullable();

			$table->enum('status',['pending','active','inactive'])->
				default('pending');

			//Stores the approved date from pending->active
			$table->timestamp('approved_at')->nullable();

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
        Schema::dropIfExists('company');
    }
}
