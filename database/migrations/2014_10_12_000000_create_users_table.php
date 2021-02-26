<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_nicename')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->integer('otp')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('country_id')->nullable();
            $table->timestamp('registered_date')->nullable();
            $table->timestamp('last_login_date')->nullable();
            $table->string('password_hint')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('user_activation_key')->nullable();
            $table->string('display_name')->nullable();
            $table->enum('account_enabled',[0,1,2,3])->default(0)->comment("1=Active,0=Inactive,2=Expired,3=Not Verified");
            $table->enum('subscription_type',[0,1])->default(0)->comment("0=Trial,1=Subscription");
            $table->enum('social_user',[0,1])->default(0)->comment("0=No,1=Yes");
            $table->string('social_provider')->nullable();
            $table->string('social_provider_user_id')->nullable();
            $table->string('migrated_id')->nullable();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->string('user_type')->nullable();
            $table->integer('avatar_id')->nullable();
            $table->string('key')->comment("forgot key")->nullable();
            $table->integer('role_id')->nullable();
            $table->string('timezone')->nullable();
            $table->integer('profile_percentage')->default(25);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
