<?php

namespace App\Enums;

class UserRoleEnum{
    public const ADMIN = 'admin';
    public const USER = 'user';

    public static $roles = [self::ADMIN, self::USER];
}

?>