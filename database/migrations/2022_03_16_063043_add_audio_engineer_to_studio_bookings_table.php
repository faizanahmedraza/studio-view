<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAudioEngineerToStudioBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->double('hourly_rate');
            $table->boolean('audio_eng_included')->default(false);
            $table->float('discount')->nullable();
            $table->double('audio_eng_rate_hr')->nullable();
            $table->boolean('audio_eng_discount')->default(false);
            $table->double('other_fees')->nullable();
            $table->double('mixing_services')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->dropColumn('hourly_rate');
            $table->dropColumn('audio_eng_included');
            $table->dropColumn('discount');
            $table->dropColumn('audio_eng_rate_hr');
            $table->dropColumn('audio_eng_discount');
            $table->dropColumn('other_fees');
            $table->dropColumn('mixing_services');

        });
    }
}
