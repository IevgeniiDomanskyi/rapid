<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dialog_id')->default(0);
            $table->unsignedInteger('author_id')->default(0);
            $table->unsignedInteger('receiver_id')->default(0);
            $table->text('message')->nullable();
            $table->tinyInteger('type')->default(0);
            $table->timestamp('author_read')->nullable();
            $table->timestamp('receiver_read')->nullable();
            $table->timestamp('author_remove')->nullable();
            $table->timestamp('receiver_remove')->nullable();
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
        Schema::dropIfExists('messages');
    }
}
