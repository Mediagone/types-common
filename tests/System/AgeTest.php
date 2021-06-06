<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\System\Age;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\System\Age
 */
final class AgeTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        self::assertInstanceOf(Age::class, Age::fromInt(20));
    }
    
    
    
    public function invalidValueProvider()
    {
        yield [PHP_INT_MIN];
        yield [-20];
        yield [-1];
    }
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Age::fromInt($invalidValue);
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $age = Age::fromInt(20);
        self::assertSame(20, $age->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $age = Age::fromInt(20);
        self::assertSame('20', (string)$age);
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        $age = Age::fromInt(20);
        self::assertSame(20, $age->toInteger());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Age::isValueValid(0));
        self::assertTrue(Age::isValueValid(1));
        self::assertTrue(Age::isValueValid(20));
        self::assertTrue(Age::isValueValid(PHP_INT_MAX));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Age::isValueValid(PHP_INT_MIN));
        self::assertFalse(Age::isValueValid(-1));
        self::assertFalse(Age::isValueValid('20'));
        self::assertFalse(Age::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_ages() : void
    {
        $q1 = Age::fromInt(20);
        $q2 = Age::fromInt(20);
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_ages() : void
    {
        $q1 = Age::fromInt(20);
        $q2 = Age::fromInt(13);
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    public function test_can_add_another_age() : void
    {
        $q1 = Age::fromInt(20);
        $q2 = Age::fromInt(13);
        
        self::assertNotSame($q1, $q2);
        
        $qSum1 = $q1->add($q2);
        self::assertNotSame($qSum1, $q1);
        self::assertNotSame($qSum1, $q2);
        self::assertSame(33, $qSum1->toInteger());
        
        $qSum2 = $q2->add($q1);
        self::assertNotSame($qSum2, $q1);
        self::assertNotSame($qSum2, $q2);
        self::assertNotSame($qSum1, $qSum2);
        self::assertSame(33, $qSum2->toInteger());
    }
    
    
    public function test_can_subtract_another_age() : void
    {
        $q1 = Age::fromInt(20);
        $q2 = Age::fromInt(13);
        
        self::assertNotSame($q1, $q2);
        
        $qSum = $q1->subtract($q2);
        
        self::assertNotSame($qSum, $q1);
        self::assertNotSame($qSum, $q2);
        self::assertSame(7, $qSum->toInteger());
    }
    
    
    public function test_cannot_subtract_greater_age() : void
    {
        $q1 = Age::fromInt(20);
        $q2 = Age::fromInt(13);
        
        self::assertNotSame($q1, $q2);
        
        $this->expectException(InvalidArgumentException::class);
        $q2->subtract($q1);
    }
    
    
    
}
