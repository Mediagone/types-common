<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use function is_float;


/**
 * Represents a Kelvin Temperature value and provides easy conversion to Celsius and Fahrenheit temperatures.
 */
class TemperatureKelvin implements Temperature
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
            throw new InvalidArgumentException("The supplied Kelvin temperature ($degrees) is invalid (it cannot be inferior to 0°K).");
        }
        
        $this->value = $degrees;
    }
    
    
    /**
     * @return static
     */
    public static function fromFloat(float $kelvin) : self
    {
        return new self($kelvin);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Kelvin Temperature.
     *
     * @param float $value
     */
    public static function isValueValid($value) : bool
    {
        if (! is_float($value)) {
            return false;
        }
        
        return $value >= 0;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize() : float
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
        return TemperatureCelsius::fromFloat($this->value - 273.15);
    }
    
    public function toFahrenheit() : TemperatureFahrenheit
    {
        return TemperatureFahrenheit::fromFloat(($this->value - 273.15) * 9.0 / 5.0 + 32);
    }
    
    public function toKelvin() : TemperatureKelvin
    {
        return self::fromFloat($this->value);
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(TemperatureKelvin $temperature) : bool
    {
        return $this->value === $temperature->value;
    }
    
    
    
}
