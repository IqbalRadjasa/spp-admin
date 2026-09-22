<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'major_id',
        'class_number',
        'level',
        'is_active'
    ];

    protected $appends = [
        'display_name'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function getDisplayNameAttribute()
    {
        $parts = [
            $this->level
        ];

        if ($this->major) {
            $parts[] = $this->major->code;
        }

        $parts[] = $this->class_number;

        return implode('-', $parts);
    }
}
