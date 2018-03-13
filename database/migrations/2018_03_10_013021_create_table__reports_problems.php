<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReportsProblems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ReportsProblems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('report')->unsigned();
            $table->integer('problem')->unsigned();
            $table->foreign('problem')->references('id')->on('Problems')->onDelete('cascade');
            $table->integer('count')->unsigned();
            $table->timestamps();
            $table->foreign('report')->references('id')->on('Reports')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ReportsProblems');
    }
}
