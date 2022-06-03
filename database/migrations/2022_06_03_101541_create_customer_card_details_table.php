<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_card_details', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->string('card_id', 120);
                $table->unsignedInteger('exp_month');
                $table->unsignedInteger('exp_year');
                $table->integer('last_digits');
                $table->string('brand', 20);
                $table->string('country', 5);
                $table->string('holder_name', 120);
                $table->boolean('is_primary')->default(false);
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('updated_by')->nullable();
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
        Schema::table('user_card_details', function (Blueprint $table) {
            Schema::dropIfExists('user_card_details');
        });
    }
}
