<?php

namespace App\Http\Controllers\Api\Payment\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'payment_id';
    protected $fillable = [
        'paid_amount'
    ];
}
