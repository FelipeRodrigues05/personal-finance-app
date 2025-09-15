<?php declare(strict_types = 1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN       = 'admin';
    case GROUP_ADMIN = "group_admin";
    case DEFAULT     = 'default'; // DEFAULT USER
}
