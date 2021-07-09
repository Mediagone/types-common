<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\Text\NameDigit;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Text\NameDigit
 */
final class NameDigitTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_regex_constant() : void
    {
        self::assertTrue(defined(NameDigit::class . '::REGEX'));
    }
    
    
    public function test_declares_regex_char_constant() : void
    {
        self::assertTrue(defined(NameDigit::class . '::REGEX_CHAR'));
    }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString(''));
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_uppercase_letters() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_can_contain_spaces() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString('A B C D'));
    }
    
    
    public function test_can_contain_hyphen() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString('A-B-C-D'));
    }
    
    
    public function test_can_contain_apostrophe() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString("A'B'C'D"));
    }
    
    
    public function test_can_contain_diacritics_chars() : void
    {
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString('áéíóúàèëïöüç'.'ÁÉÍÓÚÀÈËÏÖÜÇ'));
    }
    
    
    /**
     * @dataProvider digitsProvider
     */
    public function test_cannot_contain_digits($digit) : void
    {
        //$this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString((string)$digit));
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
        foreach (range(1, NameDigit::MAX_LENGTH) as $count) {
            self::assertInstanceOf(NameDigit::class, NameDigit::fromString(str_repeat('a', $count)));
        }
        
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(NameDigit::class, NameDigit::fromString(str_repeat('a', (NameDigit::MAX_LENGTH + 1))));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Valid name';
        $name = NameDigit::fromString($value);
        
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Valid name';
        $name = NameDigit::fromString($value);
        
        self::assertSame($value, (string)$name);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(NameDigit::isValueValid('Valid name'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(NameDigit::isValueValid(100));
        self::assertFalse(NameDigit::isValueValid(true));
        self::assertFalse(NameDigit::isValueValid(1.2));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_names() : void
    {
        $q1 = NameDigit::fromString('John');
        $q2 = NameDigit::fromString('John');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_names() : void
    {
        $q1 = NameDigit::fromString('John');
        $q2 = NameDigit::fromString('Jack');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
