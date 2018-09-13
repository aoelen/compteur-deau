<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->nullable();
            $table->float('temp',8,2)->nullable();
            $table->float('tds',8,2)->nullable();
            $table->float('turbidity',8,2)->nullable();
            $table->float('ph',8,2)->nullable();
            $table->float('orp',8,2)->nullable();
            $table->float('x',15,10)->nullable();
            $table->float('y',15,10)->nullable();
            $table->float('gps',8,2)->nullable();
            $table->integer('ns')->nullable();
            $table->integer('battery-level')->nullable();
            $table->integer('battery-charging')->nullable();
            $table->timestamps();
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
