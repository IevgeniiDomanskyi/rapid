<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackDayIdInPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_histories', function (Blueprint $table) {
            $table->unsignedInteger('track_day_id')->default(0)->after('event_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_histories', function (Blueprint $table) {
            $table->dropColumn('track_day_id');
        });
    }
}
