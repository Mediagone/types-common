<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\Latitude;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\Latitude
 */
final class LatitudeTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    
    public function validValueProvider()
    {
        yield [-90.];
        yield [-20.123456];
        yield [0.];
        yield [20.123456];
        yield [90.];
    }
    
    /**
     * @dataProvider validValueProvider
     */
    public function test_can_be_created_from_valid_value($invalidValue) : void
    {
        $this->expectNotToPerformAssertions();
        Latitude::fromFloat($invalidValue);
    }
    
    
    
    public function invalidValueProvider()
    {
        yield [-90.01];
        yield [90.01];
    }
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Latitude::fromFloat($invalidValue);
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 20.123456;
        $name = Latitude::fromFloat($value);
        
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 20.123456;
        $name = Latitude::fromFloat($value);
        
        self::assertSame((string)$value, (string)$name);
    }
    
    
    public function test_can_be_get_as_float() : void
    {
        $value = 20.123456;
        $name = Latitude::fromFloat($value);
        
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
        self::assertTrue(Latitude::isValueValid($validValue));
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_can_tell_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(Latitude::isValueValid($invalidValue));
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
        self::assertFalse(Latitude::isValueValid($invalidValueType));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_latitudes() : void
    {
        $l1 = Latitude::fromFloat(10.123456);
        $l2 = Latitude::fromFloat(10.123456);
        
        self::assertNotSame($l1, $l2);
        self::assertTrue($l1->equals($l2));
        self::assertTrue($l2->equals($l1));
    }
    
    public function test_inequality_between_latitudes() : void
    {
        $l1 = Latitude::fromFloat(10.123456);
        $l2 = Latitude::fromFloat(90);
        
        self::assertNotSame($l1, $l2);
        self::assertFalse($l1->equals($l2));
        self::assertFalse($l2->equals($l1));
    }
    
    
    
}
