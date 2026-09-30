<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentParent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'fullname',
        'nickname',
        'phone',
        'address',
        'occupation_id',
        'occupation_custom',
        'relationship',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
