<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\Text\TextMedium;
use PHPUnit\Framework\TestCase;
use function defined;
use function floor;
use function json_encode;
use function str_repeat;


/**
 * @covers \Mediagone\Types\Common\Text\TextMedium
 */
final class TextMediumTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_length_constant() : void
    {
        self::assertTrue(defined(TextMedium::class . '::MAX_LENGTH'));
    }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(TextMedium::class, TextMedium::fromString(''));
    }
    
    
    public function test_can_contain_letters() : void
    {
        self::assertInstanceOf(TextMedium::class, TextMedium::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    
    public function test_can_contain_max_length_chars() : void
    {
        self::assertInstanceOf(TextMedium::class, TextMedium::fromString(str_repeat('a', TextMedium::MAX_LENGTH)));
        self::assertInstanceOf(TextMedium::class, TextMedium::fromString(str_repeat('é', (int)floor(TextMedium::MAX_LENGTH / 2))));
    }
    
    
    public function test_cannot_contain_more_than_max_length_chars() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(TextMedium::class, TextMedium::fromString(str_repeat('a', TextMedium::MAX_LENGTH + 1)));
    }
    
    public function test_cannot_contain_more_than_max_length_utf8_chars() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(TextMedium::class, TextMedium::fromString(str_repeat('é', (int)floor(TextMedium::MAX_LENGTH / 2) + 1)));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Lorem ipsum...';
        $name = TextMedium::fromString($value);
        
        self::assertSame('"'.$value.'"', json_encode($name));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Lorem ipsum...';
        $name = TextMedium::fromString($value);
        
        self::assertSame($value, (string)$name);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(TextMedium::isValueValid('Valid text'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(TextMedium::isValueValid(100));
        self::assertFalse(TextMedium::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_texts() : void
    {
        $q1 = TextMedium::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $q2 = TextMedium::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_texts() : void
    {
        $q1 = TextMedium::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $q2 = TextMedium::fromString('abcdefghijklmnopqrstuvwxyz');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
