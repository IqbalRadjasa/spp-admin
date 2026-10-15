<?php

namespace App\Services;

use App\Models\Student;

class StudentService
{
    /**
     * Generate and assign a 10-digit NIS for the given student.
     * Format: Year (4 digits) + Padded ID (6 digits) -> e.g., 2026000042
     */
    public function generateNIS(Student $student): string
    {
        // Extract the 4-digit start year (e.g., "2026 / 2027" -> "2026")
        $yearPrefix = substr(trim($student->tahun_pendaftaran), 0, 4);

        // Fallback to current year if parsing fails
        if (!preg_match('/^\d{4}$/', $yearPrefix)) {
            $yearPrefix = now()->format('Y');
        }

        // Pad the auto-incremented database ID to 6 digits
        $paddedId = str_pad($student->id, 6, '0', STR_PAD_LEFT);

        // Combine to form the exact 10-digit NIS
        $nis = $yearPrefix . $paddedId;

        // Update the student record with the generated NIS
        $student->update([
            'nis' => $nis
        ]);

        return $nis;
    }
}
