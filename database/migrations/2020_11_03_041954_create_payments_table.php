<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->string('charge_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('amount_captured')->nullable();
            $table->integer('amount_refunded')->nullable();
            $table->integer('application')->nullable();
            $table->integer('application_fee')->nullable();
            $table->integer('application_fee_amount')->nullable();

            $table->string('balance_transaction')->nullable();
            $table->string('calculated_statement_descriptor')->nullable();
            $table->string('captured')->nullable();
            $table->string('created')->nullable();
            $table->string('currency')->nullable();

            $table->string('customer')->nullable();
            $table->string('description')->nullable();
            $table->string('destination')->nullable();
            $table->string('dispute')->nullable();
            $table->string('disputed')->nullable();
            $table->string('failure_code')->nullable();
            $table->string('failure_message')->nullable();
            $table->string('invoice')->nullable();
            $table->string('livemode')->nullable();
            $table->string('on_behalf_of')->nullable();
            $table->string('paid')->nullable();
            $table->string('payment_intent')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('receipt_email')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('receipt_url')->nullable();
            $table->string('refunded')->nullable();

            $table->string('review')->nullable();
            $table->string('shipping')->nullable();
            $table->string('source')->nullable();
            $table->string('source_transfer')->nullable();
            $table->string('statement_descriptor')->nullable();
            $table->string('statement_descriptor_suffix')->nullable();
            $table->string('status')->nullable();
            $table->string('transfer')->nullable();
            $table->string('transfer_amount')->nullable();
            $table->string('transfer_destination')->nullable();
            $table->string('transfer_group')->nullable();


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
        Schema::dropIfExists('payments');
    }
}
