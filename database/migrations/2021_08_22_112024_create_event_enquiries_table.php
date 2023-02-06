<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_enquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('event_id')->default(0);
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
        Schema::dropIfExists('event_enquiries');
    }
}
