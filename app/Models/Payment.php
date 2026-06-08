<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_code',
        'bill_id',
        'payment_method_id',
        'paid_at',
        'amount_paid',
        'notes'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
