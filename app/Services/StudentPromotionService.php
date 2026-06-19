<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Classroom;

class StudentPromotionService
{
    protected function findNextClassroom(Student $student)
    {
        $classroom = $student->classroom;

        if (!$classroom) {
            return null;
        }

        return Classroom::query()
            ->where(
                'major_id',
                $classroom->major_id
            )
            ->where(
                'name',
                $classroom->name
            )
            ->where(
                'level',
                $classroom->level + 1
            )
            ->first();
    }

    public function getPromotionPreview($search, $major, $classroom)
    {
        $query = Student::query()
            ->with('classroom.major')
            ->where('status', 'active');


        if ($search) {

            $query->where(
                'name',
                'like',
                '%' . $search . '%'
            );
        }

        if ($major) {
            $query->whereHas(
                'classroom.major',
                function ($q) use ($major) {
                    $q->where(
                        'id',
                        $major
                    );
                }
            );
        }

        if ($classroom) {
            $query->where('classroom_id', $classroom);
        }

        $students = $query
            ->paginate(5)
            ->withQueryString();

        $students->getCollection()->transform(
            function ($student) {

                $student->next_classroom =
                    $this->findNextClassroom(
                        $student
                    );

                $student->graduated =
                    !$student->next_classroom;

                return $student;
            }
        );

        return $students;
    }
}
