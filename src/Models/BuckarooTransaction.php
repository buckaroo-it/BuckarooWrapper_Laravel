<?php

namespace Buckaroo\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuckarooTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'brq_amount',
        'brq_currency',
        'brq_customer_name',
        'brq_invoicenumber',
        'brq_issuing_country',
        'brq_mutationtype',
        'brq_ordernumber',
        'brq_payer_hash',
        'brq_payment',
        'brq_SERVICE_visa_Authentication',
        'brq_SERVICE_visa_CardExpirationDate',
        'brq_SERVICE_visa_CardNumberEnding',
        'brq_SERVICE_visa_Enrolled',
        'brq_SERVICE_visa_MaskedCreditcardNumber',
        'brq_SERVICE_visa_ThreeDsVersion',
        'brq_statuscode',
        'brq_statuscode_detail',
        'brq_statusmessage',
        'brq_test',
        'brq_timestamp',
        'brq_transaction_method',
        'brq_transaction_type',
        'brq_transactions',
        'brq_websitekey',
        'brq_signature',
        'updated_at',
        'created_at'
    ];
}

