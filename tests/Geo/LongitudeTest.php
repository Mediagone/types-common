<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\Longitude;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\Longitude
 */
final class LongitudeTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    
    public function validValueProvider()
    {
        yield [-180.];
        yield [-20.123456];
        yield [0.];
        yield [20.123456];
        yield [180.];
    }
    
    /**
     * @dataProvider validValueProvider
     */
    public function test_can_be_created_from_valid_value($invalidValue) : void
    {
        $this->expectNotToPerformAssertions();
        Longitude::fromFloat($invalidValue);
    }
    
    
    
    public function invalidValueProvider()
    {
        yield [-180.01];
        yield [180.01];
    }
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Longitude::fromFloat($invalidValue);
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 20.123456;
        $name = Longitude::fromFloat($value);
        
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 20.123456;
        $name = Longitude::fromFloat($value);
        
        self::assertSame((string)$value, (string)$name);
    }
    
    
    public function test_can_be_get_as_float() : void
    {
        $value = 20.123456;
        $name = Longitude::fromFloat($value);
        
        self::assertSame($value, $name->toFloat());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    /**
     * @dataProvider validValueProvider
     */
    public function test_can_tell_value_is_valid($validValue) : void
    {
        self::assertTrue(Longitude::isValueValid($validValue));
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_can_tell_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(Longitude::isValueValid($invalidValue));
    }
    
    
    
    public function invalidValueTypeProvider()
    {
        yield [10];
        yield ['10'];
        yield [true];
        yield [false];
    }
    
    /**
     * @dataProvider invalidValueTypeProvider
     */
    public function test_can_tell_non_float_value_is_invalid($invalidValueType) : void
    {
        self::assertFalse(Longitude::isValueValid($invalidValueType));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_longitudes() : void
    {
        $l1 = Longitude::fromFloat(10.123456);
        $l2 = Longitude::fromFloat(10.123456);
        
        self::assertNotSame($l1, $l2);
        self::assertTrue($l1->equals($l2));
        self::assertTrue($l2->equals($l1));
    }
    
    public function test_inequality_between_longitudes() : void
    {
        $l1 = Longitude::fromFloat(10.123456);
        $l2 = Longitude::fromFloat(180);
        
        self::assertNotSame($l1, $l2);
        self::assertFalse($l1->equals($l2));
        self::assertFalse($l2->equals($l1));
    }
    
    
    
}
