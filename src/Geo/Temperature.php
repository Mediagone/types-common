<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use Mediagone\Types\Common\ValueObject;


/**
 * Base interface for Temperature value-objects.
 */
interface Temperature extends ValueObject
{
    /**
     * @return static
     */
    public static function fromFloat(float $celsius) : self;
    public function toFloat() : float;
    public function toCelsius() : TemperatureCelsius;
    public function toKelvin() : TemperatureKelvin;
    public function toFahrenheit() : TemperatureFahrenheit;
}
