<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('counsellor_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->longText('notes')->nullable();
            $table->string('slot')->nullable();
            $table->date('booking_date')->nullable();
            $table->string('counsellor_timezone_slot')->nullable();
            $table->date('counsellor_booking_date')->nullable();
            $table->enum('created_by',[1,2])->default(1)->comment("1=created by user, 2=created by admin");
            $table->softDeletes();
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
        Schema::dropIfExists('bookings');
    }
}
