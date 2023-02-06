<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlotFieldInBookDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_date', function (Blueprint $table) {
            $table->tinyInteger('slot')->default(0)->after('road');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_date', function (Blueprint $table) {
            $table->dropColumn('slot');
        });
    }
}
