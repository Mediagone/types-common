<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_float;


/**
 * Represents a Latitude value (-90 <= value <= 90).
 */
final class Latitude implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private float $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(float $latitude)
    {
        if (! self::isValueValid($latitude)) {
            throw new InvalidArgumentException("The supplied latitude ($latitude) is invalid.");
        }
        
        $this->value = $latitude;
    }
    
    
    public static function fromFloat(float $latitude) : self
    {
        return new self($latitude);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Latitude.
     *
     * @param float $value
     */
    public static function isValueValid($value) : bool
    {
        if (! is_float($value)) {
            return false;
        }
        
        if ($value < -90. || $value > 90.) {
            return false;
        }
        
        // $digits = explode(".", (string)(float)$value);
        // var_dump($digits[1] ?? '');
        
        return true;
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
    
    
    public function toFloat() : float
    {
        return $this->value;
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Latitude $latitude) : bool
    {
        return $this->value === $latitude->value;
    }
    
    
    
}
