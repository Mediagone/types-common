<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_float;


/**
 * Represents a Longitude value (-180 <= value <= 180).
 */
class Longitude implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private float $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(float $longitude)
    {
        if (! self::isValueValid($longitude)) {
            throw new InvalidArgumentException("The supplied longitude ($longitude) is invalid.");
        }
        
        $this->value = $longitude;
    }
    
    
    public static function fromFloat(float $longitude) : self
    {
        return new self($longitude);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Longitude.
     *
     * @param float $value
     */
    public static function isValueValid($value) : bool
    {
        return is_float($value) && $value >= -180. && $value <= 180.;
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
    
    public function equals(Longitude $longitude) : bool
    {
        return $this->value === $longitude->value;
    }
    
    
    
}
