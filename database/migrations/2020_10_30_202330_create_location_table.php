<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location', function (Blueprint $table) {
            $table->id();
            $table->string('systemid');
            $table->string('name');
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            // Definition of Operation Hour
			$table->time("start_work")->default('00:00:00');
			$table->time("close_work")->default('23:59:59');

            // Attributes for OPOSsum Screen E
            // This is the color code for the screen E table header
            $table->string('e_table_header_color')->nullable();
            // This is the color code for the screen E bottom panel
            $table->string('e_bottom_panel_color')->nullable();
            // This is the color code for the screen E right panel
            $table->string('e_right_panel_color')->nullable();
            // This is the image on the screen E right panel
            // Path: public/image/location/{location_id}/screen_e_image
            $table->string('e_right_panel_image_file')->nullable();

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
        Schema::dropIfExists('location');
    }
}
