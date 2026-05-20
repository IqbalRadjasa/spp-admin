<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'nis',
        'class',
        'parent_phone'
    ];
    
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
