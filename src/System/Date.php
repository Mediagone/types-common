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
    
    /**
     * @return static
     */
    public static function today() : self
    {
        $datetime = new DateTime('today', self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    
    /**
     * @return static
     */
    public static function tomorrow() : self
    {
        $datetime = new DateTime('tomorrow', self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    
    /**
     * @return static
     */
    public static function yesterday() : self
    {
        $datetime = new DateTime('yesterday', self::getUTC());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return self::fromValues($year, $month, $day);
    }
    
    /**
     * @return static
     */
    public static function mondayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Monday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function tuesdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Tuesday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function wednesdayThisWeek() : self
    {
        
        return new self(new DateTimeImmutable('Wednesday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function thursdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Thursday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function fridayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Friday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function saturdayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Saturday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function sundayThisWeek() : self
    {
        return new self(new DateTimeImmutable('Sunday this week', self::getUTC()));
    }
    
    /**
     * @return static
     */
    public static function lastMonday() : self
    {
        return self::tomorrow()->modify('previous Monday');
    }
    
    /**
     * @return static
     */
    public static function lastTuesday() : self
    {
        return self::tomorrow()->modify('previous Tuesday');
    }
    
    /**
     * @return static
     */
    public static function lastWednesday() : self
    {
        return self::tomorrow()->modify('previous Wednesday');
    }
    
    /**
     * @return static
     */
    public static function lastThursday() : self
    {
        return self::tomorrow()->modify('previous Thursday');
    }
    
    /**
     * @return static
     */
    public static function lastFriday() : self
    {
        return self::tomorrow()->modify('previous Friday');
    }
    
    /**
     * @return static
     */
    public static function lastSaturday() : self
    {
        return self::tomorrow()->modify('previous Saturday');
    }
    
    /**
     * @return static
     */
    public static function lastSunday() : self
    {
        return self::tomorrow()->modify('previous Sunday');
    }
    
    
    /**
     * @return static
     */
    public static function fromDateTime(DateTime $datetime) : self
    {
        return new self(DateTimeImmutable::createFromMutable($datetime));
    }
    
    /**
     * @return static
     */
    public static function fromDateTimeIgnoringTimezone(DateTime $datetime) : self
    {
        return new self(new DateTimeImmutable($datetime->format('Y-m-d'), self::getUTC()));
    }
    
    
    /**
     * @return static
     */
    public static function fromDateTimeImmutable(DateTimeImmutable $datetime) : self
    {
        return new self($datetime);
    }
    
    /**
     * @return static
     */
    public static function fromDateTimeImmutableIgnoringTimezone(DateTimeImmutable $datetime) : self
    {
        return new self(new DateTimeImmutable($datetime->format('Y-m-d'), self::getUTC()));
    }
    
    
    /**
     * @return static
     */
    public static function fromTimestamp(int $timestamp) : self
    {
        return new self((new DateTimeImmutable())->setTimestamp($timestamp));
    }
    
    
    /**
     * @return static
     */
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
    
    
    /**
     * @return static
     */
    public static function fromString(string $value, ?DateTimeZone $sourceTimezone = null) : self
    {
        $datetime = DateTimeImmutable::createFromFormat('!Y-m-d', $value, $sourceTimezone ?? self::getUTC());
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid Date value ($value), it must follow 'Y-m-d' format.");
        }
        
        return new self($datetime);
    }
    
    
    /**
     * @return static
     */
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
    // Conversion methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize() : string
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
    
    
    /**
     * @return static
     */
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
    // Modifier methods
    //========================================================================================================
    
    public function startOfWeek() : self
    {
        if ($this->getDayOfWeek() === 1) {
            return new self($this->value);
        }
        
        return new self($this->value->modify('last monday'));
    }
    
    public function endOfWeek() : self
    {
        if ($this->getDayOfWeek() === 7) {
            return new self($this->value);
        }
        
        return new self($this->value->modify('next sunday'));
    }
    
    public function startOfMonth() : self
    {
        return new self($this->value->modify('first day of this month'));
    }
    
    public function endOfMonth() : self
    {
        return new self($this->value->modify('last day of this month'));
    }
    
    public function startOfYear() : self
    {
        return new self($this->value->modify('first day of january this year'));
    }
    
    public function endOfYear() : self
    {
        return new self($this->value->modify('last day of december this year'));
    }
    
    /**
     * Return the same day of previous month, or the last day of previous month if it has fewer days than necessary.
     */
    public function previousMonth() : self
    {
        $previousMonthDate = $this->value->modify('last month');
        
        // If the current day number is greater than the previous month's number of days, PHP cycles up automatically to
        // the following month. In this case, return the last day of the previous month instead.
        if ($this->getMonth() === (int)$previousMonthDate->format('m')) {
            $previousMonthDate = $this->value->modify('last day of last month');
        }
        
        return new self($previousMonthDate);
    }
    
    /**
     * Return the same day of next month, or the last day of next month if it has fewer days than necessary.
     */
    public function nextMonth() : self
    {
        $nextMonthDate = $this->value->modify('next month');
        $firstDayOfNextMonthDate = $this->value->modify('last day of this month')->modify('+1 day');
        
        // If the current day number is greater than the next month's number of days, PHP cycles up automatically to
        // the following month. In this case, return the last day of the next month instead.
        if ((int)$firstDayOfNextMonthDate->format('m') !== (int)$nextMonthDate->format('m')) {
            $nextMonthDate = $firstDayOfNextMonthDate->modify('last day of this month');
        }
        
        return new self($nextMonthDate);
    }
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Date $date) : bool
    {
        return (string)$this === (string)$date;
    }
    
    
    
}
