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

    public function getDisplayNameAttribute(): string
    {
        $level = trim((string) $this->level);
        $classNumber = trim((string) $this->class_number);

        if (!$this->major_id && !$this->relationLoaded('major')) {
            if ($classNumber !== '' && ctype_alpha($classNumber)) {
                return $level . strtoupper($classNumber);
            }
        }

        if ($this->major) {
            $parts = [$level];

            if (!empty($this->major->code)) {
                $parts[] = $this->major->code;
            }

            if ($classNumber !== '') {
                $parts[] = $classNumber;
            }

            return implode('-', $parts);
        }

        if ($classNumber !== '' && ctype_alpha($classNumber)) {
            return $level . strtoupper($classNumber);
        }

        $parts = array_filter([$level, $classNumber], fn($val) => $val !== '');

        return implode('-', $parts);
    }
}
