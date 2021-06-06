<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\System\Duration;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\System\Duration
 */
final class DurationTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created_from_seconds() : void
    {
        self::assertSame(20, Duration::fromSeconds(20)->toSeconds());
    }
    
    public function test_can_be_created_from_minutes() : void
    {
        self::assertSame(20*60, Duration::fromMinutes(20)->toSeconds());
    }
    
    public function test_can_be_created_from_hours() : void
    {
        self::assertSame(2*60*60, Duration::fromHours(2)->toSeconds());
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
        Duration::fromSeconds($invalidValue);
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $duration = Duration::fromSeconds(20);
        self::assertSame(20, $duration->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $duration = Duration::fromSeconds(20);
        self::assertSame('20', (string)$duration);
    }
    
    
    public function test_can_be_cast_to_integer() : void
    {
        $duration = Duration::fromSeconds(20);
        self::assertSame(20, $duration->toSeconds());
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Duration::isValueValid(0));
        self::assertTrue(Duration::isValueValid(1));
        self::assertTrue(Duration::isValueValid(20));
        self::assertTrue(Duration::isValueValid(PHP_INT_MAX));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Duration::isValueValid(PHP_INT_MIN));
        self::assertFalse(Duration::isValueValid(-1));
        self::assertFalse(Duration::isValueValid('20'));
        self::assertFalse(Duration::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_durations() : void
    {
        $q1 = Duration::fromSeconds(20);
        $q2 = Duration::fromSeconds(20);
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_durations() : void
    {
        $q1 = Duration::fromSeconds(20);
        $q2 = Duration::fromSeconds(13);
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    public function test_can_add_another_duration() : void
    {
        $q1 = Duration::fromSeconds(20);
        $q2 = Duration::fromSeconds(13);
        
        self::assertNotSame($q1, $q2);
        
        $qSum1 = $q1->add($q2);
        self::assertNotSame($qSum1, $q1);
        self::assertNotSame($qSum1, $q2);
        self::assertSame(33, $qSum1->toSeconds());
        
        $qSum2 = $q2->add($q1);
        self::assertNotSame($qSum2, $q1);
        self::assertNotSame($qSum2, $q2);
        self::assertNotSame($qSum1, $qSum2);
        self::assertSame(33, $qSum2->toSeconds());
    }
    
    
    public function test_can_subtract_another_duration() : void
    {
        $q1 = Duration::fromSeconds(20);
        $q2 = Duration::fromSeconds(13);
        
        self::assertNotSame($q1, $q2);
        
        $qSum = $q1->subtract($q2);
        
        self::assertNotSame($qSum, $q1);
        self::assertNotSame($qSum, $q2);
        self::assertSame(7, $qSum->toSeconds());
    }
    
    
    public function test_cannot_subtract_greater_duration() : void
    {
        $q1 = Duration::fromSeconds(20);
        $q2 = Duration::fromSeconds(13);
        
        self::assertNotSame($q1, $q2);
        
        $this->expectException(InvalidArgumentException::class);
        $q2->subtract($q1);
    }
    
    
    
}
