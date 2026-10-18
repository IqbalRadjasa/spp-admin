<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $table = 'school_profile';

    protected $fillable = [
        'school_name',
        'education_level',
        'phone',
        'email',
        'address',
        'logo'
    ];
}
