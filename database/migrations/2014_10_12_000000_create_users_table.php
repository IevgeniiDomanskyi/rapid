<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('address_id')->default(0);
            $table->tinyInteger('exp_id')->default(0);
            $table->tinyInteger('role')->default(0)->comment = '0 - customer, 1 - coach, 2 - admin';
            $table->string('email')->unique();
            $table->string('password')->default('');
            $table->string('firstname')->default('');
            $table->string('lastname')->default('');
            $table->string('phone')->nullable();
            $table->timestamp('dob')->nullable();
            $table->boolean('is_active')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
