<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInStudioBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('studio_bookings', function (Blueprint $table) {
            $table->double('total_hours_price')->nullable();
            $table->double('total_eng_hours_price')->nullable();
            $table->double('grand_total')->nullable();
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
            $table->dropColumn('total_hours_price');
            $table->dropColumn('total_eng_hours_price');
            $table->dropColumn('grand_total');
        });
    }
}
