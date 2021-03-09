<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\Address;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Geo\Address
 */
final class AddressTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_regex_constant() : void
    {
        self::assertTrue(defined(Address::class . '::REGEX'));
    }
    
    
    public function test_declares_regex_char_constant() : void
    {
        self::assertTrue(defined(Address::class . '::REGEX_CHAR'));
    }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString(''));
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_uppercase_letters() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_can_contain_spaces() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('A B C D'));
    }
    
    
    public function test_can_contain_hyphen() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('A-B-C-D'));
    }
    
    
    public function test_can_contain_apostrophe() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString("A'B'C'D"));
    }
    
    
    public function test_can_contain_diacritics_chars() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('áéíóúàèëïöüç'.'ÁÉÍÓÚÀÈËÏÖÜÇ'));
    }
    
    
    public function test_can_contain_digits() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('0123456789'));
    }
    
    
    public function test_can_contain_foreign_characters() : void
    {
        self::assertInstanceOf(Address::class, Address::fromString('モーニング娘。'));
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        foreach (range(1, Address::MAX_LENGTH) as $count) {
            self::assertInstanceOf(Address::class, Address::fromString(str_repeat('a', $count)));
        }
        
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Address::class, Address::fromString(str_repeat('a', (Address::MAX_LENGTH + 1))));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Valid address';
        $name = Address::fromString($value);
        
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Valid address';
        $name = Address::fromString($value);
        
        self::assertSame($value, (string)$name);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Address::isValueValid('Valid address'));
    }
    
    
    public function test_can_tell_non_printable_character_is_invalid() : void
    {
        self::assertFalse(Address::isValueValid("\r"));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Address::isValueValid(100));
        self::assertFalse(Address::isValueValid(true));
    }
    
    
    
}
