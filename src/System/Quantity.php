<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_int;


final class Quantity implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $quantity;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $quantity)
    {
        if (! self::isValueValid($quantity)) {
            throw new InvalidArgumentException("The supplied quantity ($quantity) is invalid.");
        }
        
        $this->quantity = $quantity;
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
    
    public function jsonSerialize()
    {
        return $this->quantity;
    }
    
    
    public function __toString() : string
    {
        return (string)$this->quantity;
    }
    
    
    public function toInteger() : int
    {
        return $this->quantity;
    }
    
    
    
}
