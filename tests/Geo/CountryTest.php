<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\Country;
use PHPUnit\Framework\TestCase;
use TypeError;


/**
 * @covers \Mediagone\Types\Common\Geo\Country
 */
final class CountryTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created_from_alpha2() : void
    {
        $country = Country::fromAlpha2('FR');
        self::assertSame('FR', $country->getAlpha2());
        self::assertSame('FRA', $country->getAlpha3());
        self::assertSame('France', $country->getName());
    }
    
    public function test_can_be_created_from_alpha3() : void
    {
        $country = Country::fromAlpha3('FRA');
        self::assertSame('FR', $country->getAlpha2());
        self::assertSame('FRA', $country->getAlpha3());
        self::assertSame('France', $country->getName());
    }
    
    public function test_can_be_created_from_name() : void
    {
        $country = Country::fromName('France');
        self::assertSame('FR', $country->getAlpha2());
        self::assertSame('FRA', $country->getAlpha3());
        self::assertSame('France', $country->getName());
    }
    
    
    /**
     * @dataProvider invalidAlpha2Provider
     */
    public function test_cannot_be_created_from_invalid_alpha2($invalidValue, string $exceptionType) : void
    {
        $this->expectException($exceptionType);
        Country::fromAlpha2($invalidValue);
    }
    
    public function invalidAlpha2Provider()
    {
        yield [1, TypeError::class];
        yield [null, TypeError::class];
        yield ['F', InvalidArgumentException::class];
        yield ['ZZ', InvalidArgumentException::class];
        yield ['FRA', InvalidArgumentException::class];
    }
    
    
    /**
     * @dataProvider invalidAlpha3Provider
     */
    public function test_cannot_be_created_from_invalid_alpha3($invalidValue, string $exceptionType) : void
    {
        $this->expectException($exceptionType);
        Country::fromAlpha3($invalidValue);
    }
    
    public function invalidAlpha3Provider()
    {
        yield [1, TypeError::class];
        yield [null, TypeError::class];
        yield ['FR', InvalidArgumentException::class];
        yield ['ZZZ', InvalidArgumentException::class];
        yield ['FRZ', InvalidArgumentException::class];
    }
    
    
    /**
     * @dataProvider invalidNameProvider
     */
    public function test_cannot_be_created_from_invalid_name($invalidValue, string $exceptionType) : void
    {
        $this->expectException($exceptionType);
        Country::fromName($invalidValue);
    }
    
    public function invalidNameProvider()
    {
        yield [1, TypeError::class];
        yield [null, TypeError::class];
        yield ['FR', InvalidArgumentException::class];
        yield ['FRA', InvalidArgumentException::class];
        yield ['Unknown country', InvalidArgumentException::class];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        self::assertSame('FRA', Country::fromAlpha3('FRA')->jsonSerialize());
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_countries() : void
    {
        $q1 = Country::fromAlpha3('FRA');
        $q2 = Country::fromAlpha3('FRA');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_countries() : void
    {
        $q1 = Country::fromAlpha3('FRA');
        $q2 = Country::fromAlpha3('USA');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    // public function test_can_tell_value_is_valid() : void
    // {
    //     self::assertTrue(Count::isValueValid(0));
    //     self::assertTrue(Count::isValueValid(1));
    //     self::assertTrue(Count::isValueValid(20));
    //     self::assertTrue(Count::isValueValid(PHP_INT_MAX));
    // }
    
    
    // public function test_can_tell_non_string_value_is_invalid() : void
    // {
    //     self::assertFalse(Count::isValueValid(PHP_INT_MIN));
    //     self::assertFalse(Count::isValueValid(-1));
    //     self::assertFalse(Count::isValueValid('20'));
    //     self::assertFalse(Count::isValueValid(true));
    // }
    
    
    
}
