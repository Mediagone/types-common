<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Crypto;

use InvalidArgumentException;
use JsonSerializable;
use Mediagone\Types\Common\Crypto\RandomToken;
use PHPUnit\Framework\TestCase;
use function json_encode;
use function strlen;


/**
 * @covers \Mediagone\Types\Common\Crypto\RandomToken
 */
final class RandomTokenTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created_randomly() : void
    {
        $token1 = RandomToken::random();
        $token2 = RandomToken::random();
        
        self::assertNotSame((string)$token1, (string)$token2);
    }
    
    
    /**
     * @dataProvider validHexValueProvider
     */
    public function test_can_be_created_from_hex_string(string $hexString) : void
    {
        $token = RandomToken::fromHexString($hexString);

        self::assertSame($hexString, (string)$token);
        self::assertSame(RandomToken::LENGTH, strlen((string)$token));
    }
    
    public function validHexValueProvider()
    {
        yield ['0000000000000000000000000000000000000000'];
        yield ['1111111111111111111111111111111111111111'];
        yield ['2222222222222222222222222222222222222222'];
        yield ['3333333333333333333333333333333333333333'];
        yield ['4444444444444444444444444444444444444444'];
        yield ['5555555555555555555555555555555555555555'];
        yield ['6666666666666666666666666666666666666666'];
        yield ['7777777777777777777777777777777777777777'];
        yield ['8888888888888888888888888888888888888888'];
        yield ['9999999999999999999999999999999999999999'];
        yield ['aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'];
        yield ['bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb'];
        yield ['cccccccccccccccccccccccccccccccccccccccc'];
        yield ['dddddddddddddddddddddddddddddddddddddddd'];
        yield ['eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee'];
        yield ['ffffffffffffffffffffffffffffffffffffffff'];
        yield ['570174682de90cf5431f570174682de90cf5431f'];
        yield ['ee201de6d1692527230eee201de6d1692527230e'];
        yield ['943e34e6338d3f6648518c0a398ac16de3d948bd'];
    }
    
    
    /**
     * @dataProvider invalidHexValueProvider
     */
    public function test_cannot_be_created_from_invalid_hex_string(string $invalidHexString) : void
    {
        $this->expectException(InvalidArgumentException::class);
        RandomToken::fromHexString($invalidHexString);
    }
    
    public function invalidHexValueProvider()
    {
        // Too short
        yield [''];
        yield ['000000000000000000000000000000000000000'];
        
        // Invalid characters
        yield ['000000000000000000000000000000000000000g'];
        yield ['000000000000000000000000000000000000000/'];
        yield ['000000000000000000000000000000000000000='];
    
        // Too long
        yield ['0000000000000000000000000000000000000000a'];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $token = RandomToken::fromHexString('1111111111111111111111111111111111111111');
        self::assertInstanceOf(JsonSerializable::class, $token);
        self::assertSame('"1111111111111111111111111111111111111111"', json_encode($token));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $token = RandomToken::fromHexString('1111111111111111111111111111111111111111');
        self::assertSame('1111111111111111111111111111111111111111', (string)$token);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    /**
     * @dataProvider validHexValueProvider
     */
    public function test_can_tell_value_is_valid(string $hexString) : void
    {
        self::assertTrue(RandomToken::isValueValid($hexString));
    }
    
    
    /**
     * @dataProvider invalidHexValueProvider
     * @dataProvider invalidValueProvider
     */
    public function test_can_tell_non_string_value_is_invalid($invalidHexString) : void
    {
        self::assertFalse(RandomToken::isValueValid($invalidHexString));
    }
    
    public function invalidValueProvider()
    {
        yield [0];
        yield [-1];
        yield [1.234];
        yield [true];
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality() : void
    {
        $token1 = RandomToken::fromHexString('1111111111111111111111111111111111111111');
        $token2 = RandomToken::fromHexString('1111111111111111111111111111111111111111');
        
        self::assertNotSame($token1, $token2);
        self::assertTrue($token1->equals($token2));
        self::assertTrue($token2->equals($token1));
    }
    
    
    public function test_inequality() : void
    {
        $token1 = RandomToken::fromHexString('1111111111111111111111111111111111111111');
        $token2 = RandomToken::fromHexString('0000000000000000000000000000000000000000');
        
        self::assertNotSame($token1, $token2);
        self::assertFalse($token1->equals($token2));
        self::assertFalse($token2->equals($token1));
    }
    
    
    
}
