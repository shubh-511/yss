<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->unsignedDecimal('admin_commission', 10, 2);
            $table->unsignedDecimal('counsellor_commission', 10, 2);
            $table->text('stripe_secret');
            $table->text('stripe_public');
            $table->string('logo_url');
            $table->string('fb_url')->nullable();
            $table->string('google_url')->nullable();
            $table->string('twitter_url')->nullable();
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
        Schema::dropIfExists('general_settings');
    }
}
