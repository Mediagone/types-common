<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\TemperatureFahrenheit;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\TemperatureFahrenheit
 */
final class TemperatureFahrenheitTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function celsiusFahrenheitKelvinValueProvider()
    {
        yield [-273.15, -459.66999999999996, 0.0];
        yield [0.0, 32.0, 273.15];
        yield [-10.0, 14.0, 263.15];
        yield [100.0, 212.0, 373.15];
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_can_be_created_from_fahrenheit_value($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureFahrenheit::fromFloat($fahrenheit);
        self::assertSame($fahrenheit, $temperature->toFloat());
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_can_tell_value_is_valid($celsius, $fahrenheit, $kelvin) : void
    {
        self::assertTrue(TemperatureFahrenheit::isValueValid($celsius));
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidFahrenheit) : void
    {
        $this->expectException(InvalidArgumentException::class);
        TemperatureFahrenheit::fromFloat($invalidFahrenheit);
    }
    
    public function invalidValueProvider()
    {
        yield [-459.68];
        yield [-460];
        yield [-1300];
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     * @dataProvider invalidValueTypeProvider
     */
    public function test_can_tell_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(TemperatureFahrenheit::isValueValid($invalidValue));
    }
    
    public function invalidValueTypeProvider()
    {
        yield ['10'];
        yield [true];
        yield [false];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 20.123456;
        $name = TemperatureFahrenheit::fromFloat($value);
       
        self::assertIsFloat($name->jsonSerialize());
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 20.123456;
        $name = TemperatureFahrenheit::fromFloat($value);
       
        self::assertSame((string)$value, (string)$name);
    }
    
    
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_celsius_can_be_converted_to_celsius($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureFahrenheit::fromFloat($fahrenheit);
        self::assertSame((string)$celsius, (string)$temperature->toCelsius());
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_celsius_can_be_converted_to_kelvin($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureFahrenheit::fromFloat($celsius);
        self::assertSame((string)$kelvin, (string)$temperature->toKelvin());
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_temperatures() : void
    {
        $t1 = TemperatureFahrenheit::fromFloat(10.123456);
        $t2 = TemperatureFahrenheit::fromFloat(10.123456);
        
        self::assertNotSame($t1, $t2);
        self::assertTrue($t1->equals($t2));
        self::assertTrue($t2->equals($t1));
    }
    
    public function test_inequality_between_temperatures() : void
    {
        $t1 = TemperatureFahrenheit::fromFloat(10.123456);
        $t2 = TemperatureFahrenheit::fromFloat(11.123456);
        
        self::assertNotSame($t1, $t2);
        self::assertFalse($t1->equals($t2));
        self::assertFalse($t2->equals($t1));
    }
    
    
    
}
