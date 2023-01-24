<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use Mediagone\Types\Common\System\Date;
use PHPUnit\Framework\TestCase;
use function date_default_timezone_get;
use function date_default_timezone_set;
use function json_encode;
use function range;


/**
 * @covers \Mediagone\Types\Common\System\Date
 */
final class DateTest extends TestCase
{
    //========================================================================================================
    // Creation Tests
    //========================================================================================================
    
    public function test_can_generate_today() : void
    {
        $now = new DateTime('today', new DateTimeZone('UTC'));
        self::assertSame($now->format('Y-m-d'), (string)Date::today());
        self::assertSame($now->format('Y-m-d H:i:sP'), Date::today()->format('Y-m-d H:i:sP'));
    }
    
    public function test_can_generate_yesterday() : void
    {
        $now = new DateTime('yesterday', new DateTimeZone('UTC'));
        self::assertSame($now->format('Y-m-d'), (string)Date::yesterday());
        self::assertSame($now->format('Y-m-d H:i:sP'), Date::yesterday()->format('Y-m-d H:i:sP'));
    }
    
    public function test_can_generate_tomorrow() : void
    {
        $now = new DateTime('tomorrow', new DateTimeZone('UTC'));
        self::assertSame($now->format('Y-m-d'), (string)Date::tomorrow());
        self::assertSame($now->format('Y-m-d H:i:sP'), Date::tomorrow()->format('Y-m-d H:i:sP'));
    }
    
    
    public function test_can_be_created_from_datetime() : void
    {
        // Always ignores Time part
        $dt = new DateTime('2020-08-01 11:22:33');
        $date = Date::fromDateTime($dt);
        self::assertSame('2020-08-01', (string)$date);
        self::assertSame('2020-08-01 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // With explicit timezone argument
        $dt = new DateTime('2020-08-01 21:22:33', new DateTimeZone('America/Anchorage' /* UTC-9 */));
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('America/Anchorage', $dt->getTimezone()->getName());
        $date = Date::fromDateTime($dt);
        self::assertSame('2020-08-02', (string)$date);
        self::assertSame('2020-08-02 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // With timezone in datetime
        $dt = new DateTime('2020-08-01 21:22:33-10:00');
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('-10:00', $dt->getTimezone()->getName());
        $date = Date::fromDateTime($dt);
        self::assertSame('2020-08-02', (string)$date);
        self::assertSame('2020-08-02 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
    }
    
    public function test_can_be_created_from_datetime_ignoring_timezone() : void
    {
        // Ignores explicit timezone argument
        $dt = new DateTime('2020-08-01 11:22:33', new DateTimeZone('Asia/Seoul' /* UTC+9 */));
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('Asia/Seoul', $dt->getTimezone()->getName());
        $date = Date::fromDateTimeIgnoringTimezone($dt);
        self::assertSame('2020-08-01', (string)$date);
        self::assertSame('2020-08-01 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // Ignores timezone in datetime
        $dt = new DateTime('2020-08-01 11:22:33+10:00');
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('+10:00', $dt->getTimezone()->getName());
        $date = Date::fromDateTimeIgnoringTimezone($dt);
        self::assertSame('2020-08-01', (string)$date);
        self::assertSame('2020-08-01 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
    }
    
    
    public function test_can_be_created_from_datetimeimmutable() : void
    {
        // Ignores Time part
        $dt = new DateTimeImmutable('2020-08-01 11:22:33');
        $date = Date::fromDateTimeImmutable($dt);
        self::assertSame('2020-08-01', (string)$date);
        self::assertSame('2020-08-01 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // With explicit timezone argument
        $dt = new DateTimeImmutable('2020-08-01 21:22:33', new DateTimeZone('America/Anchorage' /* UTC-9 */));
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('America/Anchorage', $dt->getTimezone()->getName());
        $date = Date::fromDateTimeImmutable($dt);
        self::assertSame('2020-08-02', (string)$date);
        self::assertSame('2020-08-02 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // With timezone in datetime
        $dt = new DateTimeImmutable('2020-08-01 21:22:33-10:00');
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('-10:00', $dt->getTimezone()->getName());
        $date = Date::fromDateTimeImmutable($dt);
        self::assertSame('2020-08-02', (string)$date);
        self::assertSame('2020-08-02 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
    }
    
    public function test_can_be_created_from_datetimeimmutable_ignoring_timezone() : void
    {
        // Ignores explicit timezone argument
        $dt = new DateTimeImmutable('2020-08-01 11:22:33', new DateTimeZone('Asia/Seoul' /* UTC+9 */));
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('Asia/Seoul', $dt->getTimezone()->getName());
        $date = Date::fromDateTimeImmutableIgnoringTimezone($dt);
        self::assertSame('2020-08-01', (string)$date);
        self::assertSame('2020-08-01 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // Ignores timezone in datetime
        $dt = new DateTimeImmutable('2020-08-01 11:22:33+10:00');
        self::assertSame('2020-08-01', $dt->format('Y-m-d'));
        self::assertSame('+10:00', $dt->getTimezone()->getName());
        $date = Date::fromDateTimeImmutableIgnoringTimezone($dt);
        self::assertSame('2020-08-01', (string)$date);
        self::assertSame('2020-08-01 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
    }
    
    
    public function test_can_be_created_from_timestamp() : void
    {
        // Ignores Time part
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', '2020-01-12 11:22:33');
        self::assertSame('2020-01-12', $dt->format('Y-m-d'));
        self::assertSame('UTC', $dt->getTimezone()->getName());
        self::assertSame('2020-01-12 00:00:00+00:00', Date::fromTimestamp($dt->getTimestamp())->format('Y-m-d H:i:sP'));
        
        // Converts automatically timestamp's timezone to UTC
        $dt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-12 21:22:33', new DateTimeZone('America/Anchorage' /* UTC-9 */));
        self::assertSame('2020-01-12', $dt->format('Y-m-d'));
        self::assertSame('America/Anchorage', $dt->getTimezone()->getName());
        $date = Date::fromTimestamp($dt->getTimestamp())->format('Y-m-d H:i:sP');
        self::assertSame('2020-01-13 00:00:00+00:00', $date);
    }
    
    
    public function test_can_be_created_from_format() : void
    {
        // Ignores Time part
        $date = Date::fromFormat('2020-01-02 11:22:33', 'Y-m-d H:i:s');
        self::assertSame('2020-01-02 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // Ignores PHP default timezone
        $tz = date_default_timezone_get();
        date_default_timezone_set('America/Anchorage'); // UTC-9
        $date = Date::fromFormat('2020-01-02 23:22:33', 'Y-m-d H:i:s');
        self::assertSame('2020-01-02 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        date_default_timezone_set($tz);
    }
    
    public function test_can_be_created_from_format_with_source_timezone() : void
    {
        // Converts explicit timezone argument to UTC
        $date = Date::fromFormat('2020-01-02 23:22:33', 'Y-m-d H:i:s', new DateTimeZone('America/Anchorage')); // UTC-9
        self::assertSame('2020-01-03 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
        
        // Converts timezone in datetime to UTC
        $date = Date::fromFormat('2020-01-02 23:22:33-10:00', 'Y-m-d H:i:sP');
        self::assertSame('2020-01-03 00:00:00+00:00', $date->format('Y-m-d H:i:sP'));
    }
    
    public function test_can_be_created_from_format_without_time() : void
    {
        $date = Date::fromFormat('2020-01-02', 'Y-m-d');
        
        self::assertSame('2020-01-02 00:00:00.000000+00:00', $date->format('Y-m-d H:i:s.uP'));
    }
    
    public function test_cannot_be_created_from_string_not_matching_supplied_format() : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromFormat('2020-01-', 'Y-m-d');
    }
    
    
    public function test_can_be_created_from_string() : void
    {
        self::assertSame('2020-01-12 00:00:00+00:00', Date::fromString('2020-01-12')->format('Y-m-d H:i:sP'));
    
        // Ignores PHP default timezone
        $tz = date_default_timezone_get();
        date_default_timezone_set('America/Anchorage');
        self::assertSame('2020-01-12 00:00:00+00:00', Date::fromString('2020-01-12')->format('Y-m-d H:i:sP'));
        date_default_timezone_set($tz);
    }
    
    
    /**
     * @dataProvider invalidStringProvider
     */
    public function test_cannot_be_created_from_invalid_string(string $string) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromString('020-01-12:');
    }
    
    public function invalidStringProvider()
    {
        yield ['020-01-12:'];
        yield ['invalid date string'];
        yield ['2020-01-12 00:00:00']; // with time part
    }
    
    
    public function test_can_be_created_from_values() : void
    {
        $utcDate = Date::fromValues(2020, 1, 2);
        self::assertSame('2020-01-02', (string)$utcDate);
        
        foreach ([1,1000,2000,9999] as $year) {
            $utcDate = Date::fromValues($year, 1, 2);
            self::assertSame($year, $utcDate->getYear());
        }
        
        foreach (range(1,12) as $month) {
            $utcDate = Date::fromValues(2020, $month, 2);
            self::assertSame($month, $utcDate->getMonth());
        }
        
        foreach (range(1,31) as $day) {
            $utcDate = Date::fromValues(2020, 1, $day);
            self::assertSame($day, $utcDate->getDay());
        }
    }
    
    
    /**
     * @dataProvider invalidYearProvider
     */
    public function test_cannot_be_created_with_invalid_year($year) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromValues($year, 1, 2);
    }
    
    public function invalidYearProvider()
    {
        yield [-1];
        yield [0];
        yield [10000];
    }
    
    
    /**
     * @dataProvider invalidMonthProvider
     */
    public function test_cannot_be_created_with_invalid_month($month) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromValues(2000, $month, 2);
    }
    
    public function invalidMonthProvider()
    {
        yield [-1];
        yield [0];
        yield [13];
    }
    
    
    /**
     * @dataProvider invalidDayProvider
     */
    public function test_cannot_be_created_with_invalid_day($day) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Date::fromValues(2000, 1, $day);
    }
    
    public function invalidDayProvider()
    {
        yield [-1];
        yield [0];
        yield [32];
    }
    
    
    
    //========================================================================================================
    // Getters Tests
    //========================================================================================================
    
    public function test_can_tell_if_in_past() : void
    {
        self::assertTrue(Date::yesterday()->isInPast());
        self::assertFalse(Date::today()->isInPast());
        self::assertFalse(Date::tomorrow()->isInPast());
    }
    
    
    public function test_can_tell_if_in_future() : void
    {
        self::assertFalse(Date::yesterday()->isInFuture());
        self::assertFalse(Date::today()->isInFuture());
        self::assertTrue(Date::tomorrow()->isInFuture());
    }
    
    
    public function test_can_tell_if_today() : void
    {
        self::assertFalse(Date::yesterday()->isToday());
        self::assertTrue(Date::today()->isToday());
        self::assertFalse(Date::tomorrow()->isToday());
    }
    
    
    public function test_can_check_if_value_is_valid() : void
    {
        foreach (['0001','1000','2000','9999'] as $year) {
            $date = "$year-01-01";
            self::assertTrue(Date::isValueValid($date), "year: $year");
        }
        $base12 = ['01','02','03','04','05','06','07','08','09','10','11','12'];
        foreach ($base12 as $month) {
            $date = "2020-$month-01";
            self::assertTrue(Date::isValueValid($date), "month: $month");
        }
        $base31 = ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
        foreach ($base31 as $day) {
            $date = "2020-01-{$day}";
            self::assertTrue(Date::isValueValid($date), "day: $day");
        }
    }
    
    
    public function test_can_check_if_value_is_a_string() : void
    {
        foreach ([true,1,1.2] as $value) {
            self::assertFalse(Date::isValueValid($value));
        }
    }
    
    
    public function test_can_check_if_value_is_invalid() : void
    {
        foreach ($this->invalidYearProvider() as $year) {
            $dateString = "{$year[0]}-01-01";
            self::assertFalse(Date::isValueValid($dateString), "year: {$year[0]}");
        }
        foreach ($this->invalidMonthProvider() as $month) {
            $dateString = "2020-{$month[0]}-01";
            self::assertFalse(Date::isValueValid($dateString), "month: {$month[0]}");
        }
        foreach ($this->invalidDayProvider() as $day) {
            $dateString = "2020-01-{$day[0]}";
            self::assertFalse(Date::isValueValid($dateString), "day: {$day[0]}");
        }
    }
    
    
    public function test_can_return_year() : void
    {
        self::assertSame(2020, Date::fromValues(2020, 11, 29)->getYear());
    }
    
    public function test_can_return_month() : void
    {
        self::assertSame(11, Date::fromValues(2020, 11, 29)->getMonth());
    }
    
    public function test_can_return_day() : void
    {
        self::assertSame(29, Date::fromValues(2020, 11, 29)->getDay());
    }
    
    
    public function test_can_return_day_of_week() : void
    {
        self::assertSame(Date::MONDAY, Date::fromValues(2020, 1, 6)->getDayOfWeek());
        self::assertSame(Date::TUESDAY, Date::fromValues(2020, 1, 7)->getDayOfWeek());
        self::assertSame(Date::WEDNESDAY, Date::fromValues(2020, 1, 8)->getDayOfWeek());
        self::assertSame(Date::THURSDAY, Date::fromValues(2020, 1, 9)->getDayOfWeek());
        self::assertSame(Date::FRIDAY, Date::fromValues(2020, 1, 10)->getDayOfWeek());
        self::assertSame(Date::SATURDAY, Date::fromValues(2020, 1, 11)->getDayOfWeek());
        self::assertSame(Date::SUNDAY, Date::fromValues(2020, 1, 12)->getDayOfWeek());
    }
    
    
    public function test_can_return_day_of_year() : void
    {
        self::assertSame(12, Date::fromValues(2020, 1, 12)->getDayOfYear());
    }
    
    
    public function test_can_return_week_number() : void
    {
        self::assertSame(1, Date::fromValues(2020, 1, 1)->getWeek());
        self::assertSame(1, Date::fromValues(2020, 1, 5)->getWeek());
        self::assertSame(2, Date::fromValues(2020, 1, 6)->getWeek());
        self::assertSame(52, Date::fromValues(2020, 12, 27)->getWeek());
        self::assertSame(53, Date::fromValues(2020, 12, 28)->getWeek());
        self::assertSame(53, Date::fromValues(2020, 12, 31)->getWeek());
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_string() : void
    {
        self::assertSame('2020-01-12', (string)Date::fromString('2020-01-12'));
    }
    
    public function test_can_be_converted_to_timestamp() : void
    {
        self::assertSame(DateTime::createFromFormat('!Y-m-d', '2020-01-12')->getTimestamp(), Date::fromString('2020-01-12')->toTimestamp());
    }
    
    
    public function test_can_be_converted_to_datetime() : void
    {
        $date = Date::fromString('2020-01-12');
        $dateTime = $date->toDatetime();
        self::assertSame('2020-01-12 00:00:00+00:00', $dateTime->format('Y-m-d H:i:sP'));
        self::assertNotSame($dateTime, $date->toDatetime()); // check if it returns each time a new DateTime instance
    }
    
    
    public function test_can_be_converted_to_datetimeimmutable() : void
    {
        $date = Date::fromString('2020-01-12');
        $immutable = $date->toDatetimeImmutable();
        self::assertSame('2020-01-12 00:00:00+00:00', $immutable->format('Y-m-d H:i:sP'));
        self::assertNotSame($immutable, $date->toDatetimeImmutable()); // check if it returns each time a new DateTimeImmutable instance
    }
    
    public function test_can_serialize_to_json() : void
    {
        self::assertSame('"2020-01-12"', json_encode(Date::fromString('2020-01-12')));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_if_date_is_before() : void
    {
        $d1 = Date::fromString('2020-01-01');
        $d2 = Date::fromString('2020-01-02');
        
        self::assertTrue($d1->isBefore($d2));
        self::assertFalse($d1->isBefore($d1));
        self::assertFalse($d2->isBefore($d1));
    }
    
    public function test_if_date_is_after() : void
    {
        $d1 = Date::fromString('2020-01-01');
        $d2 = Date::fromString('2020-01-02');
        
        self::assertFalse($d1->isAfter($d2));
        self::assertFalse($d1->isAfter($d1));
        self::assertTrue($d2->isAfter($d1));
    }
    
    public function test_equality_between_dates() : void
    {
        $q1 = Date::fromString('2020-01-12');
        $q2 = Date::fromString('2020-01-12');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_dates() : void
    {
        $q1 = Date::fromString('2020-01-12');
        $q2 = Date::fromString('2020-12-24');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
