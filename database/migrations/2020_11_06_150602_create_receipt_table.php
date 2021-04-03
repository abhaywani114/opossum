<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt', function (Blueprint $table) {
            $table->id();

            // Unique ID coming from a DB sequence, "receipt_seq"
            $table->string('systemid');

            // Cash received, typically more than amount,
            // thereby, expect some change
            $table->integer('cash_received')->unsigned()->nullable();
            $table->integer('cash_change')->unsigned()->nullable();

            // Typically 10%
            $table->integer('servicecharge')->unsigned()->nullable();

             /* Service Tax is NOT Service Charge.
             * This is stored as percent e.g. 6.5% */
            $table->float('service_tax')->default(0);

            // Types of payment we accept
            $table->enum('payment_type',
                ['cash','creditcard','point','wallet'])->default('cash');

            $table->integer('terminal_id')->unsigned();

            // FK to users.id. The user_id of cashier
            $table->integer('staff_user_id')->unsigned();

            // Store the last 4 digits of the creditcard
            $table->integer('creditcard_no')->unsigned()->nullable();

            // FK to company.id. This to support receipt access via:
            // superadmin, direct, franchise, foodcourt and mall
            $table->integer('company_id')->unsigned();

            $table->enum('mode',['inclusive','exclusive'])->
                default('inclusive');

            $table->enum('status',[
                'active','confirmed','printed','voided','frozen',
                'completed','refunded'])->default('active');
            $table->string('remark');

            /* Store receipt company name which was active at
			 * time of generation */
            $table->string('company_name')->nullable();

            /* Store receipt GST/VAT/SSTwhich was active at
			 * time of generation */
            $table->string('gst_vat_sst')->nullable();

            /* Store receipt Business Registration no. was active at
			 * time of generation */
            $table->string('business_reg_no')->nullable();

            /* Store receipt addresss which was active at time of generation
             * Can be either company or branch addresss */
            $table->string('receipt_address')->nullable();

            /* Store receipt currency which was active at time of generation */
            $table->string('currency')->nullable();

            /* Store receeipt logo which was active at time of generation.
             * This is a localcopy and may not be the same as the current
             * receipt logo */
            $table->string('receipt_logo')->nullable();

            /* Stores rounded of value. Can be negative */
            $table->integer('round')->default(0);

			// *** VOID attributes ***
            // When this receipt was voided
            $table->timestamp('voided_at')->nullable()->default(null);
            // FK to users.id, to store who had voided this receipt
            $table->integer('void_user_id')->unsigned()->nullable();
            $table->text('void_reason')->nullable();

            // FK to og_pump.pump_no and og_pump.pump_id
            $table->integer('pump_no')->unsigned()->default(null);

            // FK to og_pump.pump_id.
            $table->integer('pump_id')->unsigned()->default(null);

            // Transacted type
            $table->enum('transacted',['opt','pos','wallet'])->default('pos');

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
        Schema::dropIfExists('receipt');
    }
}
