<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserReactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_reaction', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_react_id')->unsigned();
            $table->bigInteger('user_reacted_id')->unsigned();
            $table->bigInteger('reaction_id')->unsigned();
            $table->foreign('user_react_id')->references('id')->on('users');
            $table->foreign('user_reacted_id')->references('id')->on('users');
            $table->foreign('reaction_id')->references('id')->on('reaction');
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
        Schema::dropIfExists('user_reaction');
    }
}
