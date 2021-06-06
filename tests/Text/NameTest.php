<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\Text\Name;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Text\Name
 */
final class NameTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_regex_constant() : void
    {
        self::assertTrue(defined(Name::class . '::REGEX'));
    }
    
    
    public function test_declares_regex_char_constant() : void
    {
        self::assertTrue(defined(Name::class . '::REGEX_CHAR'));
    }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString(''));
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_uppercase_letters() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_can_contain_spaces() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString('A B C D'));
    }
    
    
    public function test_can_contain_hyphen() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString('A-B-C-D'));
    }
    
    
    public function test_can_contain_apostrophe() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString("A'B'C'D"));
    }
    
    
    public function test_can_contain_diacritics_chars() : void
    {
        self::assertInstanceOf(Name::class, Name::fromString('áéíóúàèëïöüç'.'ÁÉÍÓÚÀÈËÏÖÜÇ'));
    }
    
    
    /**
     * @dataProvider digitsProvider
     */
    public function test_cannot_contain_digits($digit) : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Name::class, Name::fromString((string)$digit));
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
        foreach (range(1, Name::MAX_LENGTH) as $count) {
            self::assertInstanceOf(Name::class, Name::fromString(str_repeat('a', $count)));
        }
        
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Name::class, Name::fromString(str_repeat('a', (Name::MAX_LENGTH + 1))));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Valid name';
        $name = Name::fromString($value);
        
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Valid name';
        $name = Name::fromString($value);
        
        self::assertSame($value, (string)$name);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Name::isValueValid('Valid name'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Name::isValueValid(100));
        self::assertFalse(Name::isValueValid(true));
        self::assertFalse(Name::isValueValid(1.2));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_names() : void
    {
        $q1 = Name::fromString('John');
        $q2 = Name::fromString('John');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_names() : void
    {
        $q1 = Name::fromString('John');
        $q2 = Name::fromString('Jack');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
