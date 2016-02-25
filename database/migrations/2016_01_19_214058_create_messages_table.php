<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function(Blueprint $table) {
            $table->increments('id');
            $table->string('toUserName');
            $table->string('fromUserName');
            $table->timestamp('createTime');
            $table->enum('msgType', ['event', 'text', 'image', 'voice', 'video', 'shortvideo', 'location', 'link']);
            $table->integer('messageable_id');
            $table->string('messageable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
