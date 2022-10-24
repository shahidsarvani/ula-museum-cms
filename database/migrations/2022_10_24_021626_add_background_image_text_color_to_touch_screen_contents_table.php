<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackgroundImageTextColorToTouchScreenContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('touch_screen_contents', function (Blueprint $table) {
            $table->string('title')->nullable()->after('menu_id');
            $table->string('background_color')->default('#ffffff')->after('menu_id');
            $table->string('text_color')->default('#000000')->after('menu_id');
            $table->string('text_bg_image')->nullable()->after('menu_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('touch_screen_contents', function (Blueprint $table) {
        });
    }
}
