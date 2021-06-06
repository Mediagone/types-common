<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Graphics;

use Generator;
use InvalidArgumentException;
use Mediagone\Types\Common\Graphics\Color;
use PHPUnit\Framework\TestCase;
use function json_encode;
use function range;


/**
 * @covers \Mediagone\Types\Common\Graphics\Color
 */
final class ColorTest extends TestCase
{
    //========================================================================================================
    // From Hex string creation
    //========================================================================================================
    
    /**
     * @dataProvider validStringProvider
     */
    public function test_can_be_created_from_valid_chars_strings($hex, $fullHex) : void
    {
        self::assertSame($fullHex, (string)Color::fromString($hex));
    }
    
    /**
     * @dataProvider validStringProvider
     */
    public function test_can_tell_value_is_valid($hex) : void
    {
        self::assertTrue(Color::isValueValid($hex));
    }
    
    public function validStringProvider() : Generator
    {
        // 6 chars
        yield ['#000000', '#000000'];
        yield ['#112233', '#112233'];
        yield ['#AAAAAA', '#aaaaaa'];
        yield ['#aaaaaa', '#aaaaaa'];
        yield ['#BBBBBB', '#bbbbbb'];
        yield ['#CCCCCC', '#cccccc'];
        yield ['#DDDDDD', '#dddddd'];
        yield ['#EEEEEE', '#eeeeee'];
        yield ['#FFFFFF', '#ffffff'];
        // 3 chars
        yield ['#000', '#000000'];
        yield ['#123', '#112233'];
        yield ['#AAA', '#aaaaaa'];
        yield ['#aaa', '#aaaaaa'];
        yield ['#BBB', '#bbbbbb'];
        yield ['#CCC', '#cccccc'];
        yield ['#DDD', '#dddddd'];
        yield ['#EEE', '#eeeeee'];
        yield ['#FFF', '#ffffff'];
    }
    
    
    /**
     * @dataProvider invalidColorStringProvider
     */
    public function test_invalid_hex_color_strings($hex) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::fromString($hex);
    }
    
    /**
     * @dataProvider invalidColorStringProvider
     */
    public function test_can_tell_value_is_invalid($hex) : void
    {
        self::assertFalse(Color::isValueValid($hex));
    }
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Color::isValueValid(100));
        self::assertFalse(Color::isValueValid(true));
    }
    
    public function invalidColorStringProvider() : Generator
    {
        yield ['']; // empty
        yield ['#'];
        yield ['000000']; // missing #
        yield ['0'];
        yield ['00'];
        yield ['0000'];
        yield ['00000'];
        yield ['#0'];
        yield ['#00'];
        yield ['#0000'];
        yield ['#00000'];
        yield ['#0000000'];
        yield ['#G00000']; // invalid 'G' char
    }
    
    
    
    //========================================================================================================
    // From RGB values creation
    //========================================================================================================
    
    /**
     * @dataProvider validStringProvider
     */
    public function test_can_be_created_from_valid_rgb_values() : void
    {
        foreach (range(0, 255, 2) as $r) {
            foreach (range(0, 255, 51) as $g) {
                foreach (range(0, 255, 51) as $b) {
                    $color = Color::fromRgb($r, $g, $b);
                    self::assertSame($r, $color->getR());
                    self::assertSame($g, $color->getG());
                    self::assertSame($b, $color->getB());
                }
            }
        }
    }
    
    /**
     * @dataProvider invalidRGBProvider
     */
    public function test_invalid_rgb_values($r, $g, $b) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::fromRgb($r, $g, $b);
    }
    
    public function invalidRGBProvider() : Generator
    {
        yield [-1, 0, 0];
        yield [0, -1, 0];
        yield [0, 0, -1];
        yield [256, 0, 0];
        yield [0, 256, 0];
        yield [0, 0, 256];
    }
    
    
    //========================================================================================================
    // From another Color instance
    //========================================================================================================
    
    public function test_can_be_created_from_another_color() : void
    {
        $color = Color::fromString('#112233');
        $colorNew = Color::fromColor($color);
        
        self::assertNotSame($color, $colorNew);
        self::assertSame((string)$color, (string)$colorNew);
    }
    
    
    
    //========================================================================================================
    // Json conversion
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $color = Color::fromString('#123456');
        self::assertSame('"#123456"', json_encode($color, JSON_THROW_ON_ERROR));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_colors() : void
    {
        $q1 = Color::fromString('#ffcc00');
        $q2 = Color::fromString('#ffcc00');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_colors() : void
    {
        $q1 = Color::fromString('#ffcc00');
        $q2 = Color::fromString('#ffcccc');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
