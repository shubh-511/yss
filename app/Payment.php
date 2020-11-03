<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 
        'charge_id', 
        'amount',
        'amount_captured',
        'amount_refunded',
        'application',
        'application_fee',
        'application_fee_amount',
        'balance_transaction',
        'calculated_statement_descriptor',
        'captured',
        'created',
        'currency',
        'customer',
        'description',
        'destination',
        'dispute',
        'disputed',
        'failure_code',
        'failure_message',
        'invoice',


        'livemode', 
        'on_behalf_of', 
        'paid',
        'payment_intent',
        'payment_method',
        'receipt_email',
        'receipt_number',
        'receipt_url',
        'refunded',
        'review',
        'shipping',
        'source',
        'source_transfer',
        'statement_descriptor',
        'statement_descriptor_suffix',
        'status',
        'transfer',
        'transfer_amount',
        'transfer_destination',
        'transfer_group'
    ];
}
