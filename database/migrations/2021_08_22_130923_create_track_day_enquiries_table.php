<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackDayEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_day_enquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('track_day_id')->default(0);
            $table->text('message')->nullable();
            $table->boolean('is_guest')->default(false);
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
        Schema::dropIfExists('track_day_enquiries');
    }
}
