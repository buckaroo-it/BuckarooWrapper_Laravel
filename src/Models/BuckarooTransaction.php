<?php

namespace Buckaroo\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuckarooTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'brq_statuscode',
        'brq_statuscode_detail',
        'brq_statusmessage',
        'brq_transactions',
    ];
}

