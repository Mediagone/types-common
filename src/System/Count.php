<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_int;


/**
 * Represents a Count (must be an positive integer, or zero).
 */
final class Count implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $count)
    {
        if (! self::isValueValid($count)) {
            throw new InvalidArgumentException("The supplied count ($count) is invalid.");
        }
        
        $this->value = $count;
    }
    
    
    public static function fromInt(int $count) : self
    {
        return new self($count);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Count.
     *
     * @param int $count
     */
    public static function isValueValid($count) : bool
    {
        if (! is_int($count)) {
            return false;
        }
        
        return $count >= 0;
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
    
    public function equals(Count $count) : bool
    {
        return $this->value === $count->value;
    }
    
    
    public function add(Count $count) : Count
    {
        return self::fromInt($this->value + $count->value);
    }
    
    
    public function subtract(Count $count) : Count
    {
        return self::fromInt($this->value - $count->value);
    }
    
    
    
}
