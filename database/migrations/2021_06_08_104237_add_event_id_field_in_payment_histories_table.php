<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventIdFieldInPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_histories', function (Blueprint $table) {
            $table->unsignedInteger('event_id')->default(0)->after('book_id');
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
            $table->dropColumn('event_id');
        });
    }
}
