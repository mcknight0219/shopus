<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppSecretsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_secrets', function(Blueprint $table) {
            // Permanent information about application
            $table->string('appID');
            $table->string('appSecret');
            $table->string('weixinApiEndpoint');
            $table->string('token', 32);
            $table->string('encodingAESKey', 43);
            $table->string('ips');
            // This part is updated often
            $table->string('accessToken', 512);
            $table->time('expireAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('app_secrets');
    }
}
