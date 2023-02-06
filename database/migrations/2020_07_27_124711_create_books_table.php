<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('exp_id')->default(0);
            $table->unsignedInteger('course_id')->default(0);
            $table->unsignedInteger('level_id')->default(0);
            $table->unsignedInteger('card_id')->default(0);
            $table->tinyInteger('plan')->default(0);
            $table->tinyInteger('instalment')->default(0);
            $table->string('county')->default('');
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
        Schema::dropIfExists('books');
    }
}
