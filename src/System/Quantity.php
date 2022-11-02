<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_int;


class Quantity implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $quantity)
    {
        if (! self::isValueValid($quantity)) {
            throw new InvalidArgumentException("The supplied quantity ($quantity) is invalid.");
        }
        
        $this->value = $quantity;
    }
    
    
    public static function fromInt(int $quantity) : self
    {
        return new self($quantity);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Quantity (a positive integer, or zero).
     *
     * @param int $quantity
     */
    public static function isValueValid($quantity) : bool
    {
        if (! is_int($quantity)) {
            return false;
        }
        
        return $quantity >= 0;
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
    
    public function equals(Quantity $quantity) : bool
    {
        return $this->value === $quantity->value;
    }
    
    
    public function add(Quantity $quantity) : Quantity
    {
        return self::fromInt($this->value + $quantity->value);
    }
    
    
    public function subtract(Quantity $quantity) : Quantity
    {
        return self::fromInt($this->value - $quantity->value);
    }
    
    
    
}
