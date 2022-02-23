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
            $table->bigIncrements('id');
            $table->integer('role_id')->default(1);
            $table->integer('role_type')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->integer('email_verified')->default(1);
            $table->integer('sms_verified')->default(1);
            $table->integer('is_verified')->default(1);
            $table->integer('is_unblock')->default(1);
            $table->integer('is_notification')->default(1);
            $table->integer('is_active')->default(1);
            $table->integer('is_admin')->default(0);
            $table->string('address')->nullable();
            $table->string('remember_token')->nullable();
            $table->text('profile_picture')->nullable();
            $table->string('email_verified_at')->nullable();
            // $table->tinyInteger('status')->default('1');
            $table->softDeletes();
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
