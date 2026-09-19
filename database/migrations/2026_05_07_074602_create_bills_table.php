<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('billing_period');
            $table->unique(['student_id', 'billing_period']);
            $table->integer('amount');
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamp('last_reminded_at')->nullable();
            $table->unsignedTinyInteger('reminder_attempts')->default(0);
            $table->enum('escalation_status', ['normal', 'escalated', 'resolved'])->default('normal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
