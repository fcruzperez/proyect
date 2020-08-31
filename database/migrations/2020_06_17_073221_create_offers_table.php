<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('designer_id');
            $table->bigInteger('request_id');
            $table->bigInteger('withdraw_id')->nullable();
            $table->integer('price');
            $table->float('fee')->default(0);
            $table->float('paid')->default(0);
            $table->integer('hours');
            $table->string('status')->default('sent')
                ->comment('sent, accepted, delivered, mediated, canceled, completed, paid');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('mediated_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->dateTime('paid_at')->nullable();
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
        Schema::dropIfExists('offers');
    }
}
