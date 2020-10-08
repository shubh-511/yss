<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvailaibleHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availaible_hours', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('availability_id')->nullable();
            $table->string('from_time')->nullable();
            $table->string('to_time')->nullable();
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
        Schema::dropIfExists('availaible_hours');
    }
}
