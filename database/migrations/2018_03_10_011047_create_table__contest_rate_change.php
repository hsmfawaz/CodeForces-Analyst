<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContestRateChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ContestRateChange', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ContestId')->unsigned();
            $table->string('handle');
            $table->integer('oldRating');
            $table->integer('newRating');
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
        Schema::dropIfExists('ContestRateChange');
    }
}
