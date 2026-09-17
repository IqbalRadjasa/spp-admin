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
        'parent_phone',
        'status'
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function getInitialsAttribute(): string
    {
        return \Illuminate\Support\Str::of($this->name)
            ->explode(' ')
            ->map(fn($word) => $word[0] ?? '')
            ->take(2)
            ->join('');
    }
}
