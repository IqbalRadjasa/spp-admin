<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TU_STAFF = 'tu_staff';
    case PARENT = 'parent';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::TU_STAFF => 'Staf Tata Usaha (TU)',
            self::PARENT => 'Orang Tua (Parent)',
        };
    }
}
