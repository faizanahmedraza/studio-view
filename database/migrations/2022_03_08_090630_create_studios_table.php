<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('name')->nullable();
            $table->text('detail')->nullable();
            $table->integer('minimum_booking_hr')->nullable();
            $table->integer('max_occupancy_people')->nullable();
            $table->integer('studio_hr_status')->nullable();
            $table->string('hrs_from')->nullable();
            $table->string('hrs_to')->nullable();
            $table->integer('adv_booking_time_id')->nullable();
            $table->text('past_client')->nullable();
            $table->text('audio_sample')->nullable();
            $table->text('amenities')->nullable();
            $table->text('main_equipment')->nullable();
            $table->text('rules')->nullable();
            $table->text('cancelation_policy')->nullable();
            $table->boolean('status')->default(false);
            $table->string('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studios');
    }
}
