<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function(Blueprint $table) {
            $table->increments('id');
            $table->enum('event', ['subscribe', 'unsubscribe', 'scan', 'location', 'click', 'view']);
            $table->string('eventKey')->nullable();
            // only applies to subscribe/scan. Stores the ticket of qr code
            $table->string('ticket')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
