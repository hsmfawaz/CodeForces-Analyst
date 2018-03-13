<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRateToReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Reports', function ($table) {
            $table->integer('rate');
        });
    }

    public function down()
    {
        Schema::table('Reports', function ($table) {
            $table->dropColumn('rate');
        });
    }
}
