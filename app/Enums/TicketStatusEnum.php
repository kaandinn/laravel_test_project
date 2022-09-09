<?php

namespace App\Enums;

class TicketStatusEnum{
    public const OPENED = 'opened';
    public const CLOSED = 'closed';
    public const FROZEN = 'frozen';

    public static $statuses = [self::OPENED, self::CLOSED, self::FROZEN];
}

?>