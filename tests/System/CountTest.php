<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\System\Count;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\System\Count
 */
final class CountTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    /**
     * @dataProvider validValues
     */
    public function test_can_be_created($validValue) : void
    {
        self::assertInstanceOf(Count::class, Count::fromInt($validValue));
    }
    
    /**
     * @dataProvider validValues
     */
    public function test_can_tell_value_is_valid($validValue) : void
    {
        self::assertTrue(Count::isValueValid($validValue));
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
        Count::fromInt($invalidValue);
    }
    
    /**
     * @dataProvider invalidValues
     * @dataProvider invalidNonIntValues
     */
    public function test_can_tell_non_string_value_is_invalid($invalidValue) : void
    {
        self::assertFalse(Count::isValueValid($invalidValue));
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
        self::assertSame(20, Count::fromInt(20)->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        self::assertSame('20', (string)Count::fromInt(20));
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        self::assertSame(20, Count::fromInt(20)->toInteger());
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_counts() : void
    {
        $q1 = Count::fromInt(20);
        $q2 = Count::fromInt(20);
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_counts() : void
    {
        $q1 = Count::fromInt(20);
        $q2 = Count::fromInt(13);
       
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    public function test_can_add_another_count() : void
    {
        $q1 = Count::fromInt(20);
        $q2 = Count::fromInt(13);
       
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
    
    
    public function test_can_subtract_another_count() : void
    {
        $q1 = Count::fromInt(20);
        $q2 = Count::fromInt(13);
       
        self::assertNotSame($q1, $q2);
       
        $qSum = $q1->subtract($q2);
       
        self::assertNotSame($qSum, $q1);
        self::assertNotSame($qSum, $q2);
        self::assertSame(7, $qSum->toInteger());
    }
    
    
    public function test_cannot_subtract_greater_count() : void
    {
        $q1 = Count::fromInt(20);
        $q2 = Count::fromInt(13);
       
        self::assertNotSame($q1, $q2);
       
        $this->expectException(InvalidArgumentException::class);
        $q2->subtract($q1);
    }
    
    
    
}
