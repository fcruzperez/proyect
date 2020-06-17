<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id');
            $table->string('name');
            $table->float('width');
            $table->float('height');
            $table->integer('hours');
            $table->bigInteger('technic_id');
            $table->integer('deposit');
            $table->string('status')->default('published')->comment('published, in production, in mediation, completed, canceled');
            $table->bigInteger('accepted_offer_id');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('mediated_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
