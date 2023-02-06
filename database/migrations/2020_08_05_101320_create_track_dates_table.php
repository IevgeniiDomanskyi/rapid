<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('track_id')->default(0);
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->tinyInteger('riders')->default(0);
            $table->string('part1')->default('');
            $table->string('part2')->default('');
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
        Schema::dropIfExists('track_dates');
    }
}
