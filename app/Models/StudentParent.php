<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_parent_relations', 'parent_id', 'student_id')
            ->withPivot('relationship')
            ->withTimestamps();
    }
}
