<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackDayUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_day_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('track_day_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('card_id')->default(0);
            $table->tinyInteger('level')->default(0);
            $table->tinyInteger('plan')->default(0);
            $table->tinyInteger('instalment')->default(0);
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
        Schema::dropIfExists('track_day_user');
    }
}
