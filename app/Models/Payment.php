<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'payment_code',
        'bill_id',
        'payment_method_id',
        'paid_at',
        'amount_paid',
        'notes',
        'status',
        'cash_payment_proof',
        'refund_amount',
        'refund_status',
        'refund_bank_details',
        'refunded_at',
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getStudentAttribute()
    {
        return $this->bill?->student;
    }

    // 2. Adjust your studentParent accessor to use the new accessor:
    public function getStudentParentAttribute()
    {
        if (!$this->student) {
            return null;
        }

        return $this->student->parents()->first();
    }

    // public function student()
    // {
    //     // A Payment belongs to a Bill, and that Bill belongs to a Student
    //     return $this->hasOneThrough(
    //         Student::class,
    //         Bill::class,
    //         'id',          // Foreign key on bills table (matching bill_id in payments)
    //         'id',          // Foreign key on students table (matching student_id in bills)
    //         'bill_id',     // Local key on payments table
    //         'student_id'   // Local key on bills table
    //     );
    // }

    // public function getStudentParentAttribute()
    // {
    //     // 1. Ensure the payment has a linked student
    //     if (!$this->student) {
    //         return null;
    //     }

    //     // 2. Fetch the first parent from the student's parents relationship
    //     return $this->student->parents()->first();
    // }
}
