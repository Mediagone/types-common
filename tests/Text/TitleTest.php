<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\Text\Title;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Text\Title
 */
final class TitleTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_regex_constant() : void
    {
        self::assertTrue(defined(Title::class . '::REGEX'));
    }
    
    
    public function test_declares_regex_char_constant() : void
    {
        self::assertTrue(defined(Title::class . '::REGEX_CHAR'));
    }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString(''));
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_uppercase_letters() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    public function test_can_contain_spaces() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('A B C D'));
    }
    
    
    public function test_can_contain_hyphen() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('A-B-C-D'));
    }
    
    
    public function test_can_contain_apostrophe() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString("A'B'C'D"));
    }
    
    
    public function test_can_contain_diacritics_chars() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('áéíóúàèëïöüç'.'ÁÉÍÓÚÀÈËÏÖÜÇ'));
    }
    
    
    public function test_can_contain_digits() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('0123456789'));
    }
    
    
    public function test_can_contain_foreign_characters() : void
    {
        self::assertInstanceOf(Title::class, Title::fromString('モーニング娘。 コンサートツアー'));
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        foreach (range(1, Title::MAX_LENGTH) as $count) {
            self::assertInstanceOf(Title::class, Title::fromString(str_repeat('a', $count)));
        }
        
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Title::class, Title::fromString(str_repeat('a', (Title::MAX_LENGTH + 1))));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Valid title';
        $name = Title::fromString($value);
        
        self::assertSame($value, $name->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Valid title';
        $name = Title::fromString($value);
        
        self::assertSame($value, (string)$name);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Title::isValueValid('Valid title'));
    }
    
    
    public function test_can_tell_non_printable_character_is_invalid() : void
    {
        self::assertFalse(Title::isValueValid("\r"));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Title::isValueValid(100));
        self::assertFalse(Title::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_titles() : void
    {
        $q1 = Title::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $q2 = Title::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_titles() : void
    {
        $q1 = Title::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $q2 = Title::fromString('abcdefghijklmnopqrstuvwxyz');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
