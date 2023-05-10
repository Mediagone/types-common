<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function preg_match;
use function str_pad;
use function time;


/**
 * Represents an UTC DateTime in ATOM format.
 */
class DateTimeUTC implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private DateTimeImmutable $value;
    
    private static ?DateTimeZone $utc = null;
    
    /**
     * @note This method can be overloaded to create DateTime classes based on other timezones (eg. DateTimeBerlin)
     */
    protected static function getReferenceTimezone() : DateTimeZone
    {
        if (self::$utc === null) {
            self::$utc = new DateTimeZone('UTC');
        }
        
        return self::$utc;
    }
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(DateTimeImmutable $datetime)
    {
        if ($datetime->getTimezone()->getName() !== 'UTC') {
            $datetime = $datetime->setTimezone(static::getReferenceTimezone());
        }
        
        $this->value = $datetime;
    }
    
    
    /**
     * @return static
     */
    public static function now(?DateTimeZone $timezone = null)
    {
        $datetime = new DateTime('now', $timezone ?? static::getReferenceTimezone());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        $hours = (int)$datetime->format('H');
        $minutes = (int)$datetime->format('i');
        $seconds = (int)$datetime->format('s');
        
        return static::fromValues($year, $month, $day, $hours, $minutes, $seconds, static::getReferenceTimezone());
    }
    
    
    /**
     * @return static
     */
    public static function today(?DateTimeZone $timezone = null)
    {
        $datetime = new DateTime('today', $timezone ?? static::getReferenceTimezone());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        
        return static::fromValues($year, $month, $day, 0, 0, 0, static::getReferenceTimezone());
    }
    
    
    /**
     * @return static
     */
    public static function tomorrow(?DateTimeZone $timezone = null)
    {
        $datetime = new DateTime('tomorrow', $timezone ?? static::getReferenceTimezone());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
        
        return static::fromValues($year, $month, $day, 0, 0, 0, static::getReferenceTimezone());
    }
    
    
    /**
     * @return static
     */
    public static function yesterday(?DateTimeZone $timezone = null)
    {
        $datetime = new DateTime('yesterday', $timezone ?? static::getReferenceTimezone());
        $year = (int)$datetime->format('Y');
        $month = (int)$datetime->format('m');
        $day = (int)$datetime->format('d');
    
        return static::fromValues($year, $month, $day, 0, 0, 0, static::getReferenceTimezone());
    }
    
    
    /* Days of week */
    
    /**
     * @return static
     */
    public static function mondayThisWeek(?DateTimeZone $timezone = null)
    {
        return new static(new DateTimeImmutable('Monday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function tuesdayThisWeek(?DateTimeZone $timezone = null)
    {
        return new static(new DateTimeImmutable('Tuesday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function wednesdayThisWeek(?DateTimeZone $timezone = null)
    {
        
        return new static(new DateTimeImmutable('Wednesday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function thursdayThisWeek(?DateTimeZone $timezone = null)
    {
        return new static(new DateTimeImmutable('Thursday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function fridayThisWeek(?DateTimeZone $timezone = null)
    {
        return new static(new DateTimeImmutable('Friday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function saturdayThisWeek(?DateTimeZone $timezone = null)
    {
        return new static(new DateTimeImmutable('Saturday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function sundayThisWeek(?DateTimeZone $timezone = null)
    {
        return new static(new DateTimeImmutable('Sunday this week', $timezone ?? static::getReferenceTimezone()));
    }
    
    /**
     * @return static
     */
    public static function lastMonday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Monday');
    }
    
    /**
     * @return static
     */
    public static function lastTuesday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Tuesday');
    }
    
    /**
     * @return static
     */
    public static function lastWednesday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Wednesday');
    }
    
    /**
     * @return static
     */
    public static function lastThursday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Thursday');
    }
    
    /**
     * @return static
     */
    public static function lastFriday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Friday');
    }
    
    /**
     * @return static
     */
    public static function lastSaturday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Saturday');
    }
    
    /**
     * @return static
     */
    public static function lastSunday(?DateTimeZone $timezone = null)
    {
        return static::tomorrow($timezone)->modify('previous Sunday');
    }
    
    
    
    /**
     * @return static
     */
    public static function fromDateTime(DateTime $datetime)
    {
        return new static(DateTimeImmutable::createFromMutable($datetime));
    }
    
    
    /**
     * @return static
     */
    public static function fromDateTimeImmutable(DateTimeImmutable $datetime)
    {
        return new static($datetime);
    }
    
    
    /**
     * @return static
     */
    public static function fromString(string $value, ?DateTimeZone $timezone = null)
    {
        if ($timezone === null) {
            $timezone = static::getReferenceTimezone();
        }
    
        $datetime = DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $value, $timezone);
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid DateTimeUTC value ($value), it must follow ATOM format.");
        }
        
        return new static($datetime);
    }
    
    
    /**(
     * @return static
     */
    public static function fromTimestamp(int $timestamp)
    {
        $datetime = (new DateTimeImmutable('now', static::getReferenceTimezone()))->setTimestamp($timestamp);
        
        return new static($datetime);
    }
    
    
    /**(
     * @return static
     */
    public static function fromFormat(string $value, string $format, ?DateTimeZone $timezone = null)
    {
        // Ensure all datetime fields are reset if not specified in the format
        if ($format[0] !== '!') {
            $format = "!$format";
        }
        
        $datetime = DateTimeImmutable::createFromFormat($format, $value, $timezone ?? static::getReferenceTimezone());
        if (! $datetime) {
            throw new InvalidArgumentException("Invalid DateTimeUTC value ($value) or format ($format)");
        }
        
        return new static($datetime);
    }
    
    
    /**(
     * @return static
     */
    public static function fromValues(int $year, int $month, int $day, int $hours = 0, int $minutes = 0, int $seconds = 0, ?DateTimeZone $timezone = null)
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
        
        if ($hours < 0 || $hours > 23) {
            throw new InvalidArgumentException('Invalid date "hours" value ('.$hours.'), it must be between [0-23]');
        }
        $hours = str_pad((string)$hours, 2, '0', STR_PAD_LEFT);
        
        if ($minutes < 0 || $minutes > 59) {
            throw new InvalidArgumentException('Invalid date "minutes" value ('.$minutes.'), it must be between [0-59]');
        }
        $minutes = str_pad((string)$minutes, 2, '0', STR_PAD_LEFT);
        
        if ($seconds < 0 || $seconds > 59) {
            throw new InvalidArgumentException('Invalid date "seconds" value ('.$seconds.'), it must be between [0-59]');
        }
        $seconds = str_pad((string)$seconds, 2, '0', STR_PAD_LEFT);
        
        $datetime = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', "$year-$month-$day $hours:$minutes:$seconds", $timezone ?? static::getReferenceTimezone());
        
        return new static($datetime);
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
            . 'T(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23)' // hours
            . ':(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51|52|53|54|55|56|57|58|59)' // minutes
            . ':(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24|25|26|27|28|29|30|31|32|33|34|35|36|37|38|39|40|41|42|43|44|45|46|47|48|49|50|51|52|53|54|55|56|57|58|59)' // seconds
            . '(-|\+)[0-9]{2}:[0-9]{2}' // timezone
            . '$#';
        
        return preg_match($regex, $value) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize() : string
    {
        return $this->value->format(DateTimeInterface::ATOM);
    }
    
    
    public function __toString() : string
    {
        return $this->value->format(DateTimeInterface::ATOM);
    }
    
    
    public function toTimestamp() : int
    {
        return $this->value->getTimestamp();
    }
    
    
    public function toDatetime(?DateTimeZone $timezone = null) : DateTime
    {
        return DateTime::createFromImmutable($this->value)->setTimezone($timezone ?? static::getReferenceTimezone());
    }
    
    
    public function toDatetimeImmutable(?DateTimeZone $timezone = null) : DateTimeImmutable
    {
        return (clone $this->value)->setTimezone($timezone ?? static::getReferenceTimezone());
    }
    
    
    public function format(string $format, ?DateTimeZone $outputTimezone = null) : string
    {
        if ($outputTimezone) {
            return $this->value->setTimezone($outputTimezone)->format($format);
        }
        
        return $this->value->format($format);
    }
    
    
    /**(
     * @return static
     */
    public function modify(string $modify)
    {
        return new static($this->value->modify($modify));
    }
    
    //TODO: startOfDay ?
    
    /**(
     * @return static
     */
    public function midnight()
    {
        return new static($this->value->setTime(23,59,59,999999));
    }
    
    
    public function isBefore(DateTimeUTC $date) : bool
    {
        return $this->value->getTimestamp() < $date->toTimestamp();
    }
    
    
    public function isAfter(DateTimeUTC $date) : bool
    {
        return $this->value->getTimestamp() > $date->toTimestamp();
    }
    
    
    public function isPast() : bool
    {
        return $this->value->getTimestamp() < time();
    }
    
    
    public function isFuture() : bool
    {
        return $this->value->getTimestamp() > time();
    }
    
    
    public function isToday() : bool
    {
        $startOfDay = static::today();
        $endOfDay = $startOfDay->midnight();
        $timestamp = $this->toTimestamp();
        
        return $timestamp >= $startOfDay->toTimestamp() && $timestamp <= $endOfDay->toTimestamp();
    }
    
    
    public function getYear(?DateTimeZone $outputTimezone = null) : int
    {
        return (int)$this->value->setTimezone($outputTimezone ?? static::getReferenceTimezone())->format('Y');
    }
    
    
    public function getMonth(?DateTimeZone $outputTimezone = null) : int
    {
        return (int)$this->value->setTimezone($outputTimezone ?? static::getReferenceTimezone())->format('m');
    }
    
    
    public function getDay(?DateTimeZone $outputTimezone = null) : int
    {
        return (int)$this->value->setTimezone($outputTimezone ?? static::getReferenceTimezone())->format('d');
    }
    
    
    public function getHours(?DateTimeZone $outputTimezone = null) : int
    {
        return (int)$this->value->setTimezone($outputTimezone ?? static::getReferenceTimezone())->format('H');
    }
    
    
    public function getMinutes(?DateTimeZone $outputTimezone = null) : int
    {
        return (int)$this->value->setTimezone($outputTimezone ?? static::getReferenceTimezone())->format('i');
    }
    
    
    public function getSeconds(?DateTimeZone $outputTimezone = null) : int
    {
        return (int)$this->value->setTimezone($outputTimezone ?? static::getReferenceTimezone())->format('s');
    }
    
    
    public function getDayOfWeek(?DateTimeZone $timezone = null) : int
    {
        return (int)$this->value->setTimezone($timezone ?? static::getReferenceTimezone())->format('N');
    }
    
    
    public function getDayOfYear(?DateTimeZone $timezone = null) : int
    {
        return ((int)$this->value->setTimezone($timezone ?? static::getReferenceTimezone())->format('z') + 1);
    }
    
    
    public function getWeek(?DateTimeZone $timezone = null) : int
    {
        return (int)$this->value->setTimezone($timezone ?? static::getReferenceTimezone())->format('W');
    }
    
    
    public function getDate(?DateTimeZone $timezone = null) : Date
    {
        return Date::fromValues($this->getYear(), $this->getMonth(), $this->getDay());
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(DateTimeUTC $date) : bool
    {
        return (string)$this === (string)$date;
    }
    
    
    public function diff(DateTimeUTC $date) : DateInterval
    {
        return $this->value->diff($date->value);
    }
    
    
    /**(
     * @return static
     */
    public function add(DateInterval $interval)
    {
        return new static($this->value->add($interval));
    }
    
    
    
}
