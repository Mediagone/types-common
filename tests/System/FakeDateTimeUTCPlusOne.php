<?php
namespace Tests\Mediagone\Types\Common\System;

use DateTimeZone;
use Mediagone\Types\Common\System\DateTimeUTC;


class FakeDateTimeUTCPlusOne extends DateTimeUTC
{
    private static ?DateTimeZone $tz = null;
    
    protected static function getReferenceTimezone() : DateTimeZone
    {
        if (self::$tz === null) {
            self::$tz = new DateTimeZone('+01:00');
        }
        
        return self::$tz;
    }
}
