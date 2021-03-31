<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('listing_name');
            $table->string('location');
            $table->string('contact_email_or_url');
            $table->longText('description');
            $table->integer('listing_category');
            $table->integer('listing_region');
            $table->integer('listing_label')->nullable();
            $table->string('website');
            $table->string('phone');
            $table->string('video_url');
            $table->string('cover_img');
            $table->enum('status',[0,1])->default(1)->comment("0=inactive,1=active");
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
        Schema::dropIfExists('listings');
    }
}
