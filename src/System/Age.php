<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_int;


/**
 * Represents an Age value (must be a positive integer, or zero).
 */
final class Age implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $age)
    {
        if (! self::isValueValid($age)) {
            throw new InvalidArgumentException("The supplied age ($age) is invalid.");
        }
        
        $this->value = $age;
    }
    
    
    public static function fromInt(int $age) : self
    {
        return new self($age);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Age.
     *
     * @param int $age
     */
    public static function isValueValid($age) : bool
    {
        if (! is_int($age)) {
            return false;
        }
        
        return $age >= 0;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
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
    
    public function equals(Age $age) : bool
    {
        return $this->value === $age->value;
    }
    
    
    public function add(Age $age) : Age
    {
        return self::fromInt($this->value + $age->value);
    }
    
    
    public function subtract(Age $age) : Age
    {
        return self::fromInt($this->value - $age->value);
    }
    
    
    
}
