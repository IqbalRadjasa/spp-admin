<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParent extends Model
{
    protected $fillable = [
        'fullname',
        'nickname',
        'phone',
        'email',
        'address',
        'occupation_id',
        'occupation_custom',
        'relationship',
    ];
}
