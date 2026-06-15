<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = [
        'major_id',
        'name',
        'level'
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function getDisplayNameAttribute()
    {
        $parts = [
            $this->level
        ];

        if ($this->major) {
            $parts[] = $this->major->code;
        }

        $parts[] = $this->name;

        return implode('-', $parts);
    }
}
