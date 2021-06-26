<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Crypto;

use InvalidArgumentException;
use JsonSerializable;
use Mediagone\Types\Common\Crypto\RandomToken;
use Mediagone\Types\Common\Crypto\RandomTokenSha512;
use PHPUnit\Framework\TestCase;
use function json_encode;
use function strlen;


/**
 * @covers \Mediagone\Types\Common\Crypto\RandomTokenSha512
 */
final class RandomTokenSha512Test extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created_from_token() : void
    {
        $token = RandomToken::random();
        
        self::assertSame(RandomTokenSha512::HEX_LENGTH, strlen((string)RandomTokenSha512::fromToken($token)));
    }
    
    
    public function test_generate_the_same_hash_for_similar_tokens() : void
    {
        $token = RandomToken::random();
        $hash1 = RandomTokenSha512::fromToken($token);
        $hash2 = RandomTokenSha512::fromToken($token);
        
        self::assertNotSame($hash1, $hash2);
        self::assertSame((string)$hash1, (string)$hash2);
    }
    
    
    public function test_generate_different_hashes_for_different_tokens() : void
    {
        $hash1 = RandomTokenSha512::fromToken(RandomToken::random());
        $hash2 = RandomTokenSha512::fromToken(RandomToken::random());
        
        self::assertNotSame($hash1, $hash2);
        self::assertNotSame((string)$hash1, (string)$hash2);
    }
    
    
    /**
     * @dataProvider validHashValueProvider
     */
    public function test_can_be_created_from_hash_string(string $hashString) : void
    {
        $token = RandomTokenSha512::fromHash($hashString);
    
        self::assertSame($hashString, (string)$token);
        self::assertSame(RandomTokenSha512::HEX_LENGTH, strlen((string)$token));
    }
    
    public function validHashValueProvider()
    {
        yield ['b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c522'];
        yield ['6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e'];
    }
    
    
    /**
     * @dataProvider invalidHashValueProvider
     */
    public function test_cannot_be_created_from_invalid_hex_string(string $invalidHashString) : void
    {
        $this->expectException(InvalidArgumentException::class);
        RandomTokenSha512::fromHash($invalidHashString);
    }
    
    public function invalidHashValueProvider()
    {
        // Too short
        yield [''];
        yield ['b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c52'];
       
        // Invalid characters
        yield ['b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c52g'];
        yield ['b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c52/'];
        yield ['b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c52='];
    
        // Too long
        yield ['b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c522a'];
    }
    
    
    
    public function test_can_be_created_from_binary_string() : void
    {
        $hash = '6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e';
        $bin = hex2Bin($hash);
        
        $tokenHash = RandomTokenSha512::fromBinaryString($bin);
        
        self::assertSame($hash, (string)$tokenHash);
        self::assertSame($bin, $tokenHash->toBinaryString());
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_converted_to_json() : void
    {
        $token = RandomToken::fromHexString('2222222222222222222222222222222222222222');
        $hash = RandomTokenSha512::fromToken($token);
        
        self::assertInstanceOf(JsonSerializable::class, $hash);
        self::assertSame('"6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e"', json_encode($hash, JSON_THROW_ON_ERROR));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $token = RandomToken::fromHexString('2222222222222222222222222222222222222222');
        $hash = RandomTokenSha512::fromToken($token);
        
        self::assertSame('6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e', (string)$hash);
    }
    
    
    public function test_can_be_cast_to_binary_string() : void
    {
        $token = RandomToken::fromHexString('2222222222222222222222222222222222222222');
        $hash = RandomTokenSha512::fromToken($token);
        $bin = $hash->toBinaryString();
        
        self::assertSame(RandomTokenSha512::BINARY_LENGTH, strlen($bin));
        self::assertSame(hex2bin('6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e'), $bin);
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    /**
     * @dataProvider validHashValueProvider
     */
    public function test_can_tell_value_is_valid(string $hexString) : void
    {
        self::assertTrue(RandomTokenSha512::isValueValid($hexString));
    }
    
    
    /**
     * @dataProvider invalidHashValueProvider
     * @dataProvider invalidValueProvider
     */
    public function test_can_tell_non_string_value_is_invalid($invalidHexString) : void
    {
        self::assertFalse(RandomTokenSha512::isValueValid($invalidHexString));
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
    
    public function test_can_verify_tokens() : void
    {
        $token1 = RandomToken::random();
        $token2 = RandomToken::random();
        $hash = RandomTokenSha512::fromToken($token1);
        
        self::assertTrue($hash->verifyToken($token1));
        self::assertFalse($hash->verifyToken($token2));
    }
    
    
    public function test_equality() : void
    {
        $token1 = RandomTokenSha512::fromHash('6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e');
        $token2 = RandomTokenSha512::fromHash('6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e');
       
        self::assertNotSame($token1, $token2);
        self::assertTrue($token1->equals($token2));
        self::assertTrue($token2->equals($token1));
    }
    
    
    public function test_inequality() : void
    {
        $token1 = RandomTokenSha512::fromHash('6ec991de3f13a604d3eef9651b47f966d798b05b1e9f5c9fee940ead094e3579cc25a5cd9ad9f80a0caec92b95ff54d67180448080f2c40fae6605e1877f9c4e');
        $token2 = RandomTokenSha512::fromHash('b299d0ac5eb4189a2d504bb110be4f0743b08fb70b492b2b4eacf40db445a0e3537c0011253f86c9d4b3158849e8fa9246a7745e415ac04fb8f4e3eb0d88c522');
      
        self::assertNotSame($token1, $token2);
        self::assertFalse($token1->equals($token2));
        self::assertFalse($token2->equals($token1));
    }
    
    
    
}
