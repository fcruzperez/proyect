<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('client_fee');
            $table->integer('designer_fee');
            $table->integer('minimum_work_time');
            $table->integer('minimum_work_price');
            $table->integer('delta_time');
            $table->integer('claim_time');
            $table->integer('correction_time');
            $table->integer('payment_time_to_designer');
            $table->integer('minimum_withdrawal_amount');
            $table->integer('expiration_time');
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
        Schema::dropIfExists('settings');
    }
}
