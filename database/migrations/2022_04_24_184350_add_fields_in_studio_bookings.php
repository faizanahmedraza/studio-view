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
            $table->boolean('on_arrival_to_bring_issued_id_agree')->default(0);
            $table->boolean('studio_cancellation_policy_agree')->default(0);
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
            $table->dropColumn('on_arrival_to_bring_issued_id_agree');
            $table->dropColumn('studio_cancellation_policy_agree');
        });
    }
}
