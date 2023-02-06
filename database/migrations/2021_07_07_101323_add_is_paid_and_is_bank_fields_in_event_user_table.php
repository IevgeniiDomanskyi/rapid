<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPaidAndIsBankFieldsInEventUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('instalment');
            $table->boolean('is_bank')->default(false)->after('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_user', function (Blueprint $table) {
            $table->dropColumn('is_paid');
            $table->dropColumn('is_bank');
        });
    }
}
