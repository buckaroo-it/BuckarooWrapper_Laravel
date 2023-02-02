<?php

namespace Buckaroo\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class BuckarooTransaction extends Model
{
    protected $fillable = [
        'brq_statuscode',
        'brq_statuscode_detail',
        'brq_statusmessage',
        'brq_transactions',
    ];
}

