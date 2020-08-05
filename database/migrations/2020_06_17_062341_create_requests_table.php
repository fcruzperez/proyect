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
            $table->string('image1');
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->string('design_name');
            $table->string('unit')->default('mm')->comment('mm, in');
            $table->float('width');
            $table->float('height');
            $table->integer('deposit')->default(0);
            $table->string('status')->default('published')
                ->comment('published, accepted, delivered, in mediate, completed, canceled');
            $table->bigInteger('accepted_offer_id')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('mediated_at')->nullable();
            $table->dateTime('canceled_at')->nullable();
            $table->string('description')->nullable();
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
