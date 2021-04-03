<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminal', function (Blueprint $table) {
            $table->id();
            $table->string('systemid');
            $table->string("ip_addr")->nullable();
			// This is actually server_ip, Oceania's IP
            $table->string("ip_addr")->nullable();
			// This is actually Oceania's MAC address
            $table->string("hw_addr")->nullable();
			// This is the client_ip, the browser's IP
            $table->string("client_ip")->nullable();

            $table->enum('mode',
                ['inclusive','exclusive'])->default('exclusive');
            $table->enum('status',
                ['pending','active','suspended'])->default('active');
            $table->enum('taxtype',
                ['gst','sst','vat'])->default('sst');
            $table->float('tax_percent')->unsigned()->default(6);
            $table->integer('servicecharge')->unsigned()->default(0);
            $table->string("local_logo");

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
        Schema::dropIfExists('terminal');
    }
}
