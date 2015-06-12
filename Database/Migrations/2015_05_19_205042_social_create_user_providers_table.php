<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocialCreateUserProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('provider');
            $table->string('provider_id');
            $table->string('avatar')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_providers', function (Blueprint $table) {
            $table->dropForeign('user_providers_user_id_foreign');
        });
        Schema::drop('user_providers');
    }

}
