<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('docable_id')->default(0);
            $table->string('docable_type')->default('');
            $table->string('type')->default('');
            $table->string('file')->default('');
            $table->boolean('is_signed')->default(false);
            $table->timestamp('date')->nullable();
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
        Schema::dropIfExists('docs');
    }
}
