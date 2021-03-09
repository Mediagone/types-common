<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\City;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\City
 */
final class CityTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_regex_constant() : void
    {
        self::assertTrue(defined(City::class . '::REGEX'));
    }
    
    
    // public function test_declares_regex_char_constant() : void
    // {
    //     self::assertTrue(defined(City::class . '::REGEX_CHAR'));
    // }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(City::class, City::fromString(''));
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(City::class, City::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_uppercase_letters() : void
    {
        self::assertInstanceOf(City::class, City::fromString('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_can_contain_spaces() : void
    {
        self::assertInstanceOf(City::class, City::fromString('A B C D'));
    }
    
    
    public function test_can_contain_hyphen() : void
    {
        self::assertInstanceOf(City::class, City::fromString('A-B-C-D'));
    }
    
    
    public function test_can_contain_apostrophe() : void
    {
        self::assertInstanceOf(City::class, City::fromString("A'B'C'D"));
    }
    
    
    public function test_can_contain_diacritics_chars() : void
    {
        self::assertInstanceOf(City::class, City::fromString('áéíóúàèëïöüç'.'ÁÉÍÓÚÀÈËÏÖÜÇ'));
    }
    
    
    public function test_cannot_start_by_space() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(City::class, City::fromString(' abc'));
    }
    
    
    public function test_cannot_start_by_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(City::class, City::fromString('-abc'));
    }
    
    
    public function test_cannot_end_by_space() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(City::class, City::fromString('abc '));
    }
    
    
    public function test_cannot_end_by_hyphen() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(City::class, City::fromString('abc-'));
    }
    
    
    /**
     * @dataProvider digitsProvider
     */
    public function test_cannot_contain_digits($digit) : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(City::class, City::fromString((string)$digit));
    }
    
    public function digitsProvider()
    {
        yield [0];
        yield [1];
        yield [2];
        yield [3];
        yield [4];
        yield [5];
        yield [6];
        yield [7];
        yield [8];
        yield [9];
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        foreach (range(1, City::MAX_LENGTH) as $count) {
            self::assertInstanceOf(City::class, City::fromString(str_repeat('a', $count)));
        }
        
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(City::class, City::fromString(str_repeat('a', (City::MAX_LENGTH + 1))));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Valid city';
        $city = City::fromString($value);
        
        self::assertSame($value, $city->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Valid city';
        $city = City::fromString($value);
        
        self::assertSame($value, (string)$city);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(City::isValueValid('Valid city'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(City::isValueValid(100));
        self::assertFalse(City::isValueValid(true));
        self::assertFalse(City::isValueValid(1.2));
    }
    
    
    
}
