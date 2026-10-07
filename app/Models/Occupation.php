<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_active'
    ];

    public function studentParents()
    {
        return $this->hasMany(StudentParent::class);
    }
}
