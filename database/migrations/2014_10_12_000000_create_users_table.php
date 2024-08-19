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

        // status = 0 --->> unapprove
        // status = 1 --->> approved
        // status = 2 --->> blocked

        // role = 1 --->> Admin
        // role = 2 --->> Preper
        // role = 3 --->> End_User
        // role = 4 --->> Driver


        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('role')->nullable();
            $table->string('answers')->nullable();
            $table->string('token')->nullable();
            $table->integer('status')->default(0);
            $table->string('otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('user');
    }
}
