<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Classroom;
use App\Enums\StudentStatus;

class StudentPromotionService
{
    /**
     * Define the highest level in your school (e.g., 12 for SMA/SMK or 3 for 10-12 systems)
     */
    protected const MAX_LEVEL = 12;

    public function getPromotionPreview($search = null, $major = null, $level = null)
    {
        // 1. Base student query
        $query = Student::select('id', 'fullname', 'nis', 'classroom_id', 'status')
            ->with([
                'classroom' => function ($q) {
                    $q->select(['id', 'class_number', 'level', 'major_id']);
                },
                'classroom.major' => function ($q) {
                    $q->select(['id', 'name', 'code']);
                }
            ])
            ->where('status', StudentStatus::ACTIVE->value ?? 'active')
            ->when(filled($search), function ($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%')
                    ->orWhere('nis', 'like', '%' . $search . '%');
            })
            ->when(filled($major), function ($q) use ($major) {
                $q->whereHas('classroom.major', function ($sub) use ($major) {
                    $sub->where('id', $major);
                });
            })
            ->when(filled($level), function ($q) use ($level) {
                $q->whereHas('classroom', function ($sub) use ($level) {
                    $sub->where('level', $level);
                });
            });

        // 2. Retrieve all matching active students for calculating summary metrics
        $summaryStudents = (clone $query)->get();

        // 3. OPTIMIZATION: Pre-fetch next classrooms in 1 query and key them by "NEXT_LEVEL_MAJOR_NUMBER"
        // e.g., Key = "11_RPL_1"
        $targetLevels = $summaryStudents->pluck('classroom.level')->filter()->map(fn($l) => $l + 1)->unique();

        $nextClassroomsMap = Classroom::with('major:id,code')
            ->whereIn('level', $targetLevels)
            ->get()
            ->keyBy(function ($room) {
                // Creates key format matching your naming convention: "11_RPL_1"
                $majorCode = $room->major->code ?? '';
                return "{$room->level}_{$majorCode}_{$room->class_number}";
            });

        // 4. Calculate promotion vs graduation counts in-memory
        $promoteCount = 0;
        $graduateCount = 0;

        foreach ($summaryStudents as $student) {
            $classroom = $student->classroom;

            if (!$classroom) {
                continue;
            }

            $nextLevel = $classroom->level + 1;
            $majorCode = $classroom->major->code ?? '';
            $lookupKey = "{$nextLevel}_{$majorCode}_{$classroom->class_number}";

            $hasNextRoom = $nextClassroomsMap->has($lookupKey);

            if ($classroom->level >= self::MAX_LEVEL || !$hasNextRoom) {
                $graduateCount++;
            } else {
                $promoteCount++;
            }
        }

        // 5. Paginate current view dataset
        $students = $query->latest()
            ->paginate(5)
            ->withQueryString();

        // 6. Attach target classroom & graduation flags to paginated records
        $students->getCollection()->transform(function ($student) use ($nextClassroomsMap) {
            $classroom = $student->classroom;

            if ($classroom) {
                $nextLevel = $classroom->level + 1;
                $majorCode = $classroom->major->code ?? '';
                $lookupKey = "{$nextLevel}_{$majorCode}_{$classroom->class_number}";

                $nextRoom = $nextClassroomsMap->get($lookupKey);
                // dd($nextRoom);

                $student->next_classroom = $nextRoom;
                $student->is_graduating = $classroom->level >= self::MAX_LEVEL || !$nextRoom;
            } else {
                $student->next_classroom = null;
                $student->is_graduating = false;
            }

            return $student;
        });

        return [
            'students' => $students,
            'summary' => [
                'total_students' => $summaryStudents->count(),
                'promote_count' => $promoteCount,
                'graduate_count' => $graduateCount,
            ]
        ];
    }
}
