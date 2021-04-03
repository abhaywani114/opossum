<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalcountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminalcount', function (Blueprint $table) {
            $table->id();
            // FK to opos_terminal.id
            $table->integer('terminal_id')->unsigned();
            // The number of receipts that is allowed for this terminal
            $table->integer('allowed_receipt_count')->unsigned()->nullable();
			// The current count of receipts generated
            $table->integer('current_rcount')->unsigned()->nullable();
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
        Schema::dropIfExists('terminalcount');
    }
}
