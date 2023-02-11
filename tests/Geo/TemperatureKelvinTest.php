<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\TemperatureKelvin;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\TemperatureKelvin
 */
final class TemperatureKelvinTest extends TestCase
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
    public function test_can_be_created_from_kelvin_value($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureKelvin::fromFloat($kelvin);
        self::assertSame($kelvin, $temperature->toFloat());
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_can_tell_value_is_valid($celsius, $fahrenheit, $kelvin) : void
    {
        self::assertTrue(TemperatureKelvin::isValueValid($kelvin));
    }
    
    
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidKelvin) : void
    {
        $this->expectException(InvalidArgumentException::class);
        TemperatureKelvin::fromFloat($invalidKelvin);
    }
    
    public function invalidValueProvider()
    {
        yield [-0.01];
        yield [-1];
        yield [-100];
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     * @dataProvider invalidValueTypeProvider
     */
    public function test_can_tell_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(TemperatureKelvin::isValueValid($invalidValue));
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
        $name = TemperatureKelvin::fromFloat($value);
       
        self::assertIsFloat($name->jsonSerialize());
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 20.123456;
        $name = TemperatureKelvin::fromFloat($value);
       
        self::assertSame((string)$value, (string)$name);
    }
    
    
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_celsius_can_be_converted_to_celsius($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureKelvin::fromFloat($kelvin);
        self::assertSame((string)$celsius, (string)$temperature->toCelsius());
    }
    
    /**
     * @dataProvider celsiusFahrenheitKelvinValueProvider
     */
    public function test_celsius_can_be_converted_to_fahrenheit($celsius, $fahrenheit, $kelvin) : void
    {
        $temperature = TemperatureKelvin::fromFloat($kelvin);
        self::assertSame((string)$fahrenheit, (string)$temperature->toFahrenheit());
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_temperatures() : void
    {
        $t1 = TemperatureKelvin::fromFloat(10.123456);
        $t2 = TemperatureKelvin::fromFloat(10.123456);
        
        self::assertNotSame($t1, $t2);
        self::assertTrue($t1->equals($t2));
        self::assertTrue($t2->equals($t1));
    }
    
    public function test_inequality_between_temperatures() : void
    {
        $t1 = TemperatureKelvin::fromFloat(10.123456);
        $t2 = TemperatureKelvin::fromFloat(11.123456);
        
        self::assertNotSame($t1, $t2);
        self::assertFalse($t1->equals($t2));
        self::assertFalse($t2->equals($t1));
    }
    
    
    
}
