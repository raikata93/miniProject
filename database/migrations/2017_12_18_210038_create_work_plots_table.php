<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkPlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_plots', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('date');
            $table->double('area');
            $table->integer('traktor_id')->unsigned();
            $table->foreign('traktor_id')->references('id')->on('traktors')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('plot_id')->unsigned();
            $table->foreign('plot_id')->references('id')->on('plots')->onDelete('cascade')->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_plots');
    }
}
