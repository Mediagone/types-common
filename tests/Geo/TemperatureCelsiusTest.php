<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\TemperatureCelsius;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\TemperatureCelsius
 */
final class TemperatureCelsiusTest extends TestCase
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
    public function test_can_be_created_from_celsius_value($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureCelsius::fromFloat($celsius);
        self::assertSame($celsius, $temperature->toFloat());
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_can_tell_value_is_valid($celsius, $fahrenheit, $kelvin) : void
    {
        self::assertTrue(TemperatureCelsius::isValueValid($celsius));
    }
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidCelsius) : void
    {
        $this->expectException(InvalidArgumentException::class);
        TemperatureCelsius::fromFloat($invalidCelsius);
    }
    
    public function invalidValueProvider()
    {
        yield [-273.16];
        yield [-274];
        yield [-1300];
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     * @dataProvider invalidValueTypeProvider
     */
    public function test_can_tell_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(TemperatureCelsius::isValueValid($invalidValue));
    }
    
    public function invalidValueTypeProvider()
    {
        yield [-273.16];
        yield [-274];
        yield [-1300];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 20.123456;
        $name = TemperatureCelsius::fromFloat($value);
       
        self::assertIsFloat($name->jsonSerialize());
        self::assertSame($value, $name->jsonSerialize());
    }
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 20.123456;
        $name = TemperatureCelsius::fromFloat($value);
       
        self::assertSame((string)$value, (string)$name);
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_celsius_can_be_converted_to_kelvin($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureCelsius::fromFloat($celsius);
        self::assertSame((string)$kelvin, (string)$temperature->toKelvin());
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_celsius_can_be_converted_to_fahrenheit($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureCelsius::fromFloat($celsius);
        self::assertSame((string)$fahrenheit, (string)$temperature->toFahrenheit());
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_temperatures() : void
    {
        $t1 = TemperatureCelsius::fromFloat(10.123456);
        $t2 = TemperatureCelsius::fromFloat(10.123456);
       
        self::assertNotSame($t1, $t2);
        self::assertTrue($t1->equals($t2));
        self::assertTrue($t2->equals($t1));
    }
    
    public function test_inequality_between_temperatures() : void
    {
        $t1 = TemperatureCelsius::fromFloat(10.123456);
        $t2 = TemperatureCelsius::fromFloat(11.123456);
        
        self::assertNotSame($t1, $t2);
        self::assertFalse($t1->equals($t2));
        self::assertFalse($t2->equals($t1));
    }
    
    
    
}
