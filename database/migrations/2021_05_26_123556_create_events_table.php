<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('region_id')->default(0);
            $table->unsignedInteger('coach_id')->default(0);
            $table->string('name')->default('');
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->tinyInteger('riders')->default(0);
            $table->double('price', 8, 2)->default(0);
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
        Schema::dropIfExists('events');
    }
}
