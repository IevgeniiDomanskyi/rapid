<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(0)->comment = '0 - percent, 1 - amount';
            $table->smallInteger('amount')->default(0);
            $table->string('code')->default('');
            $table->tinyInteger('coupon_limit')->default(0);
            $table->tinyInteger('user_limit')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
