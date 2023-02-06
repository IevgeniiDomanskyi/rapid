<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsInPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('order_id')->default('')->after('status');
            $table->string('auth_code')->default('')->after('order_id');
            $table->string('transaction_id')->default('')->after('auth_code');
            $table->string('scheme_id')->default('')->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('auth_code');
            $table->dropColumn('transaction_id');
            $table->dropColumn('scheme_id');
        });
    }
}
