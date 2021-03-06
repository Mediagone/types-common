<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\Text\Text;
use PHPUnit\Framework\TestCase;
use function json_encode;


/**
 * @covers \Mediagone\Types\Common\Text\Text
 */
final class TextTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_length_constant() : void
    {
        self::assertTrue(defined(Text::class . '::MAX_LENGTH'));
    }
    
    
    public function test_can_be_empty() : void
    {
        self::assertInstanceOf(Text::class, Text::fromString(''));
    }
    
    
    public function test_can_contain_letters() : void
    {
        self::assertInstanceOf(Text::class, Text::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
    }
    
    
    
    public function test_can_contain_max_length_chars() : void
    {
        self::assertInstanceOf(Text::class, Text::fromString(str_repeat('a', Text::MAX_LENGTH)));
        self::assertInstanceOf(Text::class, Text::fromString(str_repeat('é', (int)floor(Text::MAX_LENGTH / 2))));
    }
    
    
    public function test_cannot_contain_more_than_max_length_chars() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Text::class, Text::fromString(str_repeat('a', Text::MAX_LENGTH + 1)));
    }
    
    public function test_cannot_contain_more_than_max_length_utf8_chars() : void
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertInstanceOf(Text::class, Text::fromString(str_repeat('é', (int)floor(Text::MAX_LENGTH / 2) + 1)));
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'Lorem ipsum...';
        $name = Text::fromString($value);
        
        self::assertSame('"'.$value.'"', json_encode($name));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'Lorem ipsum...';
        $name = Text::fromString($value);
        
        self::assertSame($value, (string)$name);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(Text::isValueValid('Valid text'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Text::isValueValid(100));
        self::assertFalse(Text::isValueValid(true));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_texts() : void
    {
        $q1 = Text::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $q2 = Text::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_texts() : void
    {
        $q1 = Text::fromString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $q2 = Text::fromString('abcdefghijklmnopqrstuvwxyz');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
