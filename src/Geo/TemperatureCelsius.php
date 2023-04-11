<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use function is_float;


/**
 * Represents a Celsius Temperature value and provides easy conversion to Fahrenheit and Kelvin temperatures.
 */
class TemperatureCelsius implements Temperature
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
            throw new InvalidArgumentException("The supplied Celsius temperature ($degrees) is invalid (it cannot be inferior to -273.15°C).");
        }
        
        $this->value = $degrees;
    }
    
    
    /**
     * @return static
     */
    public static function fromFloat(float $celsius) : self
    {
        return new self($celsius);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Celsius Temperature.
     *
     * @param float $value
     */
    public static function isValueValid($value) : bool
    {
        if (! is_float($value)) {
            return false;
        }
    
        return $value >= -273.15;
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
        return self::fromFloat($this->value);
    }
    
    public function toKelvin() : TemperatureKelvin
    {
        return TemperatureKelvin::fromFloat($this->value + 273.15);
    }
    
    public function toFahrenheit() : TemperatureFahrenheit
    {
        return TemperatureFahrenheit::fromFloat($this->value * 9.0 / 5.0 + 32);
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(TemperatureCelsius $temperature) : bool
    {
        return $this->value === $temperature->value;
    }
    
    
    
}
