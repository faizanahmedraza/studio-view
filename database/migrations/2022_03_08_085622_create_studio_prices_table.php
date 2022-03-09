<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudioPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studio_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('studio_id');
            $table->double('hourly_rate');
            $table->boolean('audio_eng_included')->default(false);
            $table->float('discount')->nullable();
            $table->double('audio_eng_rate_hr')->nullable();
            $table->boolean('audio_eng_discount')->default(false);
            $table->double('other_fees')->nullable();
            $table->double('mixing_services')->nullable();
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
        Schema::dropIfExists('studio_prices');
    }
}
