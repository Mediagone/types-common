<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\Text\SlugSnake;
use PHPUnit\Framework\TestCase;
use function str_repeat;


/**
 * @covers \Mediagone\Types\Common\Text\SlugSnake
 */
final class SlugSnakeTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_cannot_be_empty() : void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString('');
    }
    
    
    public function test_can_contain_lowercase_letters() : void
    {
        self::assertInstanceOf(SlugSnake::class, SlugSnake::fromString('abcdefghijklmnopqrstuvwxyz'));
    }
    
    
    public function test_can_contain_digits() : void
    {
        self::assertInstanceOf(SlugSnake::class, SlugSnake::fromString('0123456789'));
    }
    
    
    /**
     * @dataProvider uppercaseProvider
     */
    public function test_cannot_contain_uppercase_letters(string $letter) : void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString($letter);
    }
    
    public function uppercaseProvider() : array
    {
        return array_map(function($l) { return [$l]; }, range('A', 'Z'));
    }
    
    
    public function test_can_contain_underscores() : void
    {
        self::assertInstanceOf(SlugSnake::class, SlugSnake::fromString('abc_def_ghi'));
    }
    
    
    public function test_cannot_contain_adjacent_underscores() : void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString('abc__def_ghi');
    }
    
    
    public function test_cannot_start_with_underscore() : void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString('_abc_def_ghi');
    }
    
    
    public function test_cannot_end_with_underscore() : void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString('abc_def_ghi_');
    }
    
    
    /**
     * @dataProvider accentProvider
     */
    public function test_cannot_contain_accents($letter) : void
    {
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString($letter);
    }
    
    public function accentProvider()
    {
        yield ['é'];
        yield ['è'];
        yield ['ê'];
        yield ['à'];
        yield ['ù'];
    }
    
    
    public function test_cannot_be_too_long() : void
    {
        $longSlug = str_repeat('a', SlugSnake::MAX_LENGTH);
        self::assertInstanceOf(SlugSnake::class, SlugSnake::fromString($longSlug));
        
        $this->expectException(InvalidArgumentException::class);
        SlugSnake::fromString($longSlug.'a');
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'valid_slug';
        $slug = SlugSnake::fromString($value);
        
        self::assertSame('valid_slug', $slug->jsonSerialize());
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'valid_slug';
        $slug = SlugSnake::fromString($value);
        self::assertSame($value, (string)$slug);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_value_is_valid() : void
    {
        self::assertTrue(SlugSnake::isValueValid('valid_slug'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(SlugSnake::isValueValid(100));
        self::assertFalse(SlugSnake::isValueValid(true));
        self::assertFalse(SlugSnake::isValueValid('invalid slug'));
        self::assertFalse(SlugSnake::isValueValid('invalid-slug'));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_slugs() : void
    {
        $q1 = SlugSnake::fromString('this_is_a_slug');
        $q2 = SlugSnake::fromString('this_is_a_slug');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_slugs() : void
    {
        $s1 = SlugSnake::fromString('this_is_a_slug');
        $s2 = SlugSnake::fromString('this_is_another_slug');
        
        self::assertNotSame($s1, $s2);
        self::assertFalse($s1->equals($s2));
        self::assertFalse($s2->equals($s1));
    }
    
    
}
