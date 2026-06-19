<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'billing_period',
        'amount',
        'status',
        'last_reminded_at',
        'escalation_status',
        'escalation_notes'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class)
            ->withTrashed();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function escalationNotes()
    {
        return $this->hasMany(BillEscalationNote::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
