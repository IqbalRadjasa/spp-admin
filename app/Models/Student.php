<?php

namespace App\Models;

use App\Enums\Religion;
use App\Enums\StudentStatus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'classroom_id',
        'nis',
        'nisn',
        'fullname',
        'nickname',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'address',
        'phone',
        'avatar',
        'status',
        'enrollment_year',
    ];

    protected function casts(): array
    {
        return [
            'religion' => Religion::class,
            'status' => StudentStatus::class,
        ];
    }

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
