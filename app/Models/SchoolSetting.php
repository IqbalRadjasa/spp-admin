<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name',
        'education_level',
        'phone',
        'email',
        'address',
        'academic_year',
        'logo'
    ];
}
