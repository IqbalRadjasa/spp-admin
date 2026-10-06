<?php

namespace App\Models;

use App\Enums\Religion;
use App\Enums\StudentStatus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fullname',
        'nickname',
        'nis',
        'nisn',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'phone',
        'enrollment_year',
        'status',
        'classroom_id',
        'address',
        'avatar',
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

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_parent_relations', 'student_id', 'parent_id')
            ->withPivot('relationship')
            ->withTimestamps();
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
