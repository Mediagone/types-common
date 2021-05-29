<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\System\Quantity;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\System\Quantity
 */
final class QuantityTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    /**
     * @dataProvider validValues
     */
    public function test_can_be_created($validValue) : void
    {
        self::assertInstanceOf(Quantity::class, Quantity::fromInt($validValue));
    }
    
    /**
     * @dataProvider validValues
     */
    public function test_can_tell_value_is_valid($validValue) : void
    {
        self::assertTrue(Quantity::isValueValid($validValue));
    }
    
    public function validValues()
    {
        yield [0];
        yield [1];
        yield [2];
        yield [100];
        yield [10000];
        yield [PHP_INT_MAX];
    }
    
    
    
    /**
     * @dataProvider invalidValues
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Quantity::fromInt($invalidValue);
    }
    
    /**
     * @dataProvider invalidValues
     * @dataProvider invalidNonIntValues
     */
    public function test_can_tell_non_string_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(Quantity::isValueValid($invalidValue));
    }
    
    public function invalidValues()
    {
        yield [PHP_INT_MIN];
        yield [-20];
        yield [-1];
    }
    
    public function invalidNonIntValues()
    {
        yield [true];
        yield ['20'];
        yield [0.1234];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        self::assertSame(20, Quantity::fromInt(20)->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        self::assertSame('20', (string)Quantity::fromInt(20));
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        self::assertSame(20, Quantity::fromInt(20)->toInteger());
    }
    
    
    
}
