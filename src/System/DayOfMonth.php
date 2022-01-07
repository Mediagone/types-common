<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_int;


/**
 * Represents a Day of a month (1-31 range).
 */
final class DayOfMonth implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $dayOfMonth)
    {
        if (! self::isValueValid($dayOfMonth)) {
            throw new InvalidArgumentException("The supplied day of month ($dayOfMonth) is invalid (must be in 1-31 range).");
        }
        
        $this->value = $dayOfMonth;
    }
    
    
    public static function fromInt(int $dayOfMonth) : self
    {
        return new self($dayOfMonth);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Day of month.
     *
     * @param int $dayOfMonth
     */
    public static function isValueValid($dayOfMonth) : bool
    {
        if (! is_int($dayOfMonth)) {
            return false;
        }
        
        return $dayOfMonth >= 1 && $dayOfMonth <= 31;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->value;
    }
    
    
    public function __toString() : string
    {
        return (string)$this->value;
    }
    
    
    public function toInteger() : int
    {
        return $this->value;
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(DayOfMonth $dayOfMonth) : bool
    {
        return $this->value === $dayOfMonth->value;
    }
    
    
    
}
