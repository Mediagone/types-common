<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use function is_float;


/**
 * Represents a Fahrenheit Temperature value and provides easy conversion to Celsius and Kelvin temperatures.
 */
class TemperatureFahrenheit implements Temperature
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private float $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(float $degrees)
    {
        if (! self::isValueValid($degrees)) {
            throw new InvalidArgumentException("The supplied Fahrenheit temperature ($degrees) is invalid (it cannot be inferior to  -459.67°F).");
        }
        
        $this->value = $degrees;
    }
    
    
    /**
     * @return static
     */
    public static function fromFloat(float $fahrenheit) : self
    {
        return new self($fahrenheit);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Fahrenheit Temperature.
     *
     * @param float $value
     */
    public static function isValueValid($value) : bool
    {
        if (! is_float($value)) {
            return false;
        }
        
        return $value >= -459.67;
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
    
    public function toCelsius() : TemperatureCelsius
    {
        return TemperatureCelsius::fromFloat(($this->value - 32) * 5.0 / 9.0);
    }
    
    public function toFahrenheit() : TemperatureFahrenheit
    {
        return self::fromFloat($this->value);
    }
    
    public function toKelvin() : TemperatureKelvin
    {
        return TemperatureKelvin::fromFloat($this->value + 273.15);
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(TemperatureFahrenheit $temperature) : bool
    {
        return $this->value === $temperature->value;
    }
    
    
    
}
