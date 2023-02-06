<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesFieldsInLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->tinyInteger('track_dates')->default(0)->after('description');
            $table->tinyInteger('road_dates')->default(0)->after('track_dates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('track_dates');
            $table->dropColumn('road_dates');
        });
    }
}
