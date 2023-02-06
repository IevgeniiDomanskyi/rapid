<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseFieldInTrackDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('track_dates', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->tinyInteger('course')->default(0)->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('track_dates', function (Blueprint $table) {
            $table->dropColumn('course');
            $table->time('time')->nullable()->after('date');
        });
    }
}
