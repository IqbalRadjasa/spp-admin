<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'nis',
        'classroom_id',
        'parent_phone'
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
