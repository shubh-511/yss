<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrantAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grant_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site_url');
            $table->string('site_key');
            $table->enum('verified',[0,1])->default(0)->comment("0=Unverified,1=Verified");
            $table->enum('status',[0,1])->default(1)->comment("0=Inactive,1=Active");
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
        Schema::dropIfExists('grant_access_tokens');
    }
}
