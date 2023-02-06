<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('track_date_id')->default(0);
            $table->unsignedInteger('coach_id')->default(0);
            $table->string('name')->default('');
            $table->tinyInteger('riders1')->default(0);
            $table->tinyInteger('riders2')->default(0);
            $table->tinyInteger('riders3')->default(0);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('fee')->default(0);
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
        Schema::dropIfExists('track_days');
    }
}
