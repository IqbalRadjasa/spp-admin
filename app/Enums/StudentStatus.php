<?php

namespace App\Enums;

enum StudentStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case GRADUATED = 'graduated';
    case TRANSFERRED = 'transferred';

    // Human-readable labels for UI display
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Tidak Aktif',
            self::GRADUATED => 'Lulus',
            self::TRANSFERRED => 'Pindah Sekolah',
        };
    }

    // Custom Tailwind color badges for Blade views
    public function colorClasses(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::INACTIVE => 'bg-stone-100 text-stone-700 border-stone-200',
            self::GRADUATED => 'bg-blue-100 text-blue-800 border-blue-200',
            self::TRANSFERRED => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    }
}
