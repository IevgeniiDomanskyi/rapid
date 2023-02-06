<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsInBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->tinyInteger('road')->default(0)->after('track_date_id');
            $table->dropColumn('road_date_1');
            $table->dropColumn('road_date_2');
            $table->dropColumn('road_date_3');
            $table->dropColumn('road_date_4');
            $table->dropColumn('road_date_5');
            $table->dropColumn('road_date_6');
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
            $table->dropColumn('road');
            $table->date('road_date_1')->nullable()->after('track_date_id');
            $table->date('road_date_2')->nullable()->after('road_date_1');
            $table->date('road_date_3')->nullable()->after('road_date_2');
            $table->date('road_date_4')->nullable()->after('road_date_3');
            $table->date('road_date_5')->nullable()->after('road_date_4');
            $table->date('road_date_6')->nullable()->after('road_date_5');
        });
    }
}
