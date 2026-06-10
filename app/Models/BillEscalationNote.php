<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillEscalationNote extends Model
{
    protected $fillable = [
        'bill_id',
        'user_id',
        'note',
    ];

    public function bill()
    {
        return $this->belongsTo(
            Bill::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
}
