<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso')->nullable();
            $table->string('name')->nullable();
            $table->string('nicename')->nullable();
            $table->string('iso3')->nullable();
            $table->integer('numcode')->nullable();
            $table->string('phonecode')->nullable();
            $table->enum('display_status',[0,1,2])->default(1)->comment("1=Active,0=Inactive,2=Delete");
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
        Schema::dropIfExists('countries');
    }
}
