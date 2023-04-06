<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function str_pad;


/**
 * Represents a Date in YYYY-MM-DD format, ignores any time part.
 */
class Date implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MONDAY = 1;
    
    public const TUESDAY = 2;
    
    public const WEDNESDAY = 3;
    
    public const THURSDAY = 4;
    
    public const FRIDAY = 5;
    
    public const SATURDAY = 6;
    
    public const SUNDAY = 7;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private static ?DateTimeZone $utc = null;
    
    private static function getUTC() : DateTimeZone
    {
        if (self::$utc === null) {
            self::$utc = new DateTimeZone('UTC');
        }
        
        return self::$utc;
    }
    
    
    private DateTimeImmutable $value;
    
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(DateTimeImmutable $datetime)
    {
        if ($datetime->getTimezone()->getName() !== 'UTC') {
            $datetime = $datetime->setTimezone(self::getUTC());
        }
        
        $this->value = $datetime->setTime(0, 0, 0, 0);
    }
    
    
    public static function today() : self
    {
        $datetime = new DateTime('today', self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    
    public static function tomorrow() : self
    {
        $datetime = new DateTime('tomorrow', self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    
    public static function yesterday() : self
    {
        $datetime = new DateTime('yesterday', self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    public static function mondayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Monday this week', self::getUTC()));
    }
    
    public static function tuesdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Tuesday this week', self::getUTC()));
    }
    
    public static function wednesdayThisWeek() : self
    {
        
        return new self(new DateTimeImmutable('Wednesday this week', self::getUTC()));
    }
    
    public static function thursdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Thursday this week', self::getUTC()));
    }
    
    public static function fridayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Friday this week', self::getUTC()));
    }
    
    public static function saturdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Saturday this week', self::getUTC()));
    }
    
    public static function sundayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Sunday this week', self::getUTC()));
    }
    
    public static function lastMonday() : self
    {
        return self::tomorrow()->modify('previous Monday');
    }
    
    public static function lastTuesday() : self
    {
        return self::tomorrow()->modify('previous Tuesday');
    }
    
    public static function lastWednesday() : self
    {
        return self::tomorrow()->modify('previous Wednesday');
    }
    
    public static function lastThursday() : self
    {
        return self::tomorrow()->modify('previous Thursday');
    }
    
    public static function lastFriday() : self
    {
        return self::tomorrow()->modify('previous Friday');
    }
    
    public static function lastSaturday() : self
    {
        return self::tomorrow()->modify('previous Saturday');
    }
    
    public static function lastSunday() : self
    {
        return self::tomorrow()->modify('previous Sunday');
    }
    
    
    public static function fromDateTime(DateTime $datetime) : self
    {
        return new self(DateTimeImmutable::createFromMutable($datetime));
    }
    
    public static function fromDateTimeIgnoringTimezone(DateTime $datetime) : self
    {
        return new self(new DateTimeImmutable($datetime->format('Y-m-d'), self::getUTC()));
    }
    
    
    public static function fromDateTimeImmutable(DateTimeImmutable $datetime) : self
    {
        return new self($datetime);
    }
    
    public static function fromDateTimeImmutableIgnoringTimezone(DateTimeImmutable $datetime) : self
    {
        return new self(new DateTimeImmutable($datetime->format('Y-m-d'), self::getUTC()));
    }
    
    
    public static function fromTimestamp(int $timestamp) : self
    {
        return new self((new DateTimeImmutable())->setTimestamp($timestamp));
    }
    
    
    public static function fromFormat(string $value, string $format, ?DateTimeZone $sourceTimezone = null) : self
    {
        // Ensure all datetime fields are reset if not specified in the format
        if ($format[0] !== '!') {
            $format = "!$format";
        }
        
        $date = DateTimeImmutable::createFromFormat($format, $value, $sourceTimezone ?? static::getUTC());
        if (! $date) {
            throw new InvalidArgumentException("Invalid Date value ($value) or format ($format)");
        }
        
        return new self($date);
    }
    
    
    public static function fromString(string $value, ?DateTimeZone $sourceTimezone = null) : self
    {
        $datetime = DateTimeImmutable::createFromFormat('!Y-m-d', $value, $sourceTimezone ?? self::getUTC());
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid Date value ($value), it must follow 'Y-m-d' format.");
        }
        
        return new self($datetime);
    }
    
    
    public static function fromValues(int $year, int $month, int $day) : self
    {
        if ($year < 1 || $year > 9999) {
            throw new InvalidArgumentException('Invalid date "year" value ('.$year.'), it must be between [1-9999]');
        }
        $year = str_pad((string)$year, 4, '0', STR_PAD_LEFT);
        
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException('Invalid date "month" value ('.$month.'), it must be between [1-12]');
        }
        $month = str_pad((string)$month, 2, '0', STR_PAD_LEFT);
    
        if ($day < 1 || $day > 31) {
            throw new InvalidArgumentException('Invalid date "day" value ('.$day.'), it must be between [1-31]');
        }
        $day = str_pad((string)$day, 2, '0', STR_PAD_LEFT);
        
        return new self(DateTimeImmutable::createFromFormat('!Y-m-d', "$year-$month-$day"));
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * @param string $value
     */
    public static function isValueValid($value) : bool
    {
        if (! is_string($value)) {
            return false;
        }
        
        $regex = '#^'
            . '[0-9]{4}' // year
            . '-(01|02|03|04|05|06|07|08|09|10|11|12)' // month
            . '-(01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31)' // day
            . '$#';
        
        return preg_match($regex, $value) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->value->format('Y-m-d');
    }
    
    
    public function __toString() : string
    {
        return $this->value->format('Y-m-d');
    }
    
    
    public function toTimestamp() : int
    {
        return $this->value->getTimestamp();
    }
    
    
    public function toDatetime(?DateTimeZone $timezone = null) : DateTime
    {
        return DateTime::createFromImmutable($this->value->setTimezone($timezone ?? static::getUTC()));
    }
    
    
    public function toDatetimeImmutable(?DateTimeZone $timezone = null) : DateTimeImmutable
    {
        return (clone $this->value)->setTimezone($timezone ?? static::getUTC());
    }
    
    public function toDatetimeUTC() : DateTimeUTC
    {
        return DateTimeUTC::fromFormat($this->value->format('Y-m-d'), '!Y-m-d');
    }
    
    
    public function format(string $format) : string
    {
        return $this->value->format($format);
    }
    
    
    public function modify(string $modify) : self
    {
        return new self($this->value->modify($modify));
    }
    
    
    public function isBefore(Date $date) : bool
    {
        return $this->value->getTimestamp() < $date->toTimestamp();
    }
    
    
    public function isAfter(Date $date) : bool
    {
        return $this->value->getTimestamp() > $date->toTimestamp();
    }
    
    
    public function isInPast() : bool
    {
        return $this->value->getTimestamp() < self::today()->toTimestamp();
    }
    
    
    public function isInFuture() : bool
    {
        return $this->value->getTimestamp() > self::today()->toTimestamp();
    }
    
    
    public function isToday() : bool
    {
        return $this->value->getTimestamp() === self::today()->toTimestamp();
    }
    
    
    public function getYear() : int
    {
        return (int)$this->value->format('Y');
    }
    
    
    public function getMonth() : int
    {
        return (int)$this->value->format('m');
    }
    
    
    public function getDay() : int
    {
        return (int)$this->value->format('d');
    }
    
    
    public function getDayOfWeek() : int
    {
        return (int)$this->value->format('N');
    }
    
    
    public function getDayOfYear() : int
    {
        return ((int)$this->value->format('z') + 1);
    }
    
    
    public function getWeek() : int
    {
        return (int)$this->value->format('W');
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Date $date) : bool
    {
        return (string)$this === (string)$date;
    }
    
    
    
}
