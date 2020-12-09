<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPackageidAndBookingidInVideoChannels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('video_channels', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->after('id')->nullable();
            $table->unsignedBigInteger('booking_id')->after('package_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('video_channels', function (Blueprint $table) {
            $table->dropColumn('package_id');
            $table->dropColumn('booking_id');
        });
    }
}
