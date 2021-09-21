<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationTable extends Migration
{

    public function up()
    {
        Schema::create('invitation', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('hash')->unique();
            $table->boolean('valid');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('invitation');
    }
}
