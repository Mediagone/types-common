<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\System\DayOfMonth;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\System\DayOfMonth
 */
final class DayOfMonthTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        foreach (range(1, 31) as $day) {
            self::assertInstanceOf(DayOfMonth::class, DayOfMonth::fromInt($day));
        }
    }
    
    
    /**
     * @dataProvider invalidValueProvider
     */
    public function test_cannot_be_created_from_invalid_value($invalidValue) : void
    {
        $this->expectException(InvalidArgumentException::class);
        DayOfMonth::fromInt($invalidValue);
    }
    
    public function invalidValueProvider()
    {
        yield [PHP_INT_MIN];
        yield [-100];
        yield [0];
        yield [32];
        yield [100];
        yield [PHP_INT_MAX];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $dayOfMonth = DayOfMonth::fromInt(20);
        self::assertSame(20, $dayOfMonth->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $dayOfMonth = DayOfMonth::fromInt(20);
        self::assertSame('20', (string)$dayOfMonth);
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        $dayOfMonth = DayOfMonth::fromInt(20);
        self::assertSame(20, $dayOfMonth->toInteger());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(DayOfMonth::isValueValid(1));
        self::assertTrue(DayOfMonth::isValueValid(31));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(DayOfMonth::isValueValid(PHP_INT_MIN));
        self::assertFalse(DayOfMonth::isValueValid(-1));
        self::assertFalse(DayOfMonth::isValueValid('20'));
        self::assertFalse(DayOfMonth::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_instances() : void
    {
        $d1 = DayOfMonth::fromInt(20);
        $d2 = DayOfMonth::fromInt(20);
        
        self::assertNotSame($d1, $d2);
        self::assertTrue($d1->equals($d2));
        self::assertTrue($d2->equals($d1));
    }
    
    public function test_inequality_between_instances() : void
    {
        $d1 = DayOfMonth::fromInt(20);
        $d2 = DayOfMonth::fromInt(13);
        
        self::assertNotSame($d1, $d2);
        self::assertFalse($d1->equals($d2));
        self::assertFalse($d2->equals($d1));
    }
    
    
    
}
