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
            $table->string('toUserName');
            $table->string('fromUserName');
            $table->time('createTime');
            $table->enum('event', ['subscribe', 'unsubscribe', 'scan', 'location', 'click', 'view']);
            // may contains eventKey or other event-sepcific data
            $table->json('data');
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
