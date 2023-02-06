<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedInteger('coach_id')->default(0)->after('user_id');
            $table->tinyInteger('status')->default(0)->after('card_id');
            $table->date('track_date')->nullable()->after('county');
            $table->date('road_date_1')->nullable()->after('track_date');
            $table->date('road_date_2')->nullable()->after('road_date_1');
            $table->date('road_date_3')->nullable()->after('road_date_2');
            $table->date('road_date_4')->nullable()->after('road_date_3');
            $table->date('road_date_5')->nullable()->after('road_date_4');
            $table->date('road_date_6')->nullable()->after('road_date_5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['coach_id', 'status', 'track_date', 'road_date_1', 'road_date_2', 'road_date_3', 'road_date_4', 'road_date_5', 'road_date_6']);
        });
    }
}
