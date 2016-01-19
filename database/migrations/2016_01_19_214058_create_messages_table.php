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
        Schema::create('messages', function($table) {
            $table->increments('id');
            $table->string('toUserName');
            $table->string('fromUsernName');
            $table->time('createTime');
            $table->enum('type', ['text', 'image', 'voice', 'video', 'shortvideo', 'location', 'link']);
            $table->bigInteger('msgId');
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
