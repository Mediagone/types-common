<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use DateInterval;
use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function floor;
use function is_int;


/**
 * Class Duration
 */
class Duration implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $value;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(int $duration)
    {
        if (! self::isValueValid($duration)) {
            throw new InvalidArgumentException("The supplied duration ($duration) is invalid.");
        }
        
        $this->value = $duration;
    }
    
    
    /**
     * @return static
     */
    public static function fromSeconds(int $duration) : self
    {
        return new self($duration);
    }
    
    
    /**
     * @return static
     */
    public static function fromMinutes(int $minutes) : self
    {
        return new self($minutes * 60);
    }
    
    
    public static function fromHours(int $hours) : self
    {
        return new self($hours * 60 * 60);
    }
    
    
    public static function fromDateInterval(DateInterval $dateInterval) : self
    {
        if ($dateInterval->y > 0 || $dateInterval->m > 0) {
            throw new InvalidArgumentException("Duration doesn't support monthly or yearly ranges.");
        }
        
        return new self(
            $dateInterval->d * 86400
            + $dateInterval->h * 3600
            + $dateInterval->i * 60
            + $dateInterval->s
        );
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Duration.
     *
     * @param int $duration
     */
    public static function isValueValid($duration) : bool
    {
        if (! is_int($duration)) {
            return false;
        }
        
        return $duration >= 0;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize() : int
    {
        return $this->value;
    }
    
    
    public function __toString() : string
    {
        return (string)$this->value;
    }
    
    
    public function toSeconds() : int
    {
        return $this->value;
    }
    
    
    public function format(string $separator = ':') : string
    {
        $hours = floor($this->value / 60);
        $minutes = $this->value % 60;
        
        return $hours.$separator.$minutes;
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Duration $age) : bool
    {
        return $this->value === $age->value;
    }
    
    
    public function add(Duration $age) : Duration
    {
        return self::fromSeconds($this->value + $age->value);
    }
    
    
    public function subtract(Duration $age) : Duration
    {
        return self::fromSeconds($this->value - $age->value);
    }
    
    
    
}
