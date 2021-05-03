<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('booking_id');
            $table->string('init_by');
            $table->string('pick_by');  
            $table->string('cut_by')->nullable();
            $table->enum('status',['connected','disconnected','interrupted','ringing','declined']);
            $table->string('call_duration');  
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
        Schema::dropIfExists('call_logs');
    }
}
