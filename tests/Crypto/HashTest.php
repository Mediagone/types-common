<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Crypto;

use Mediagone\Types\Common\Crypto\Hash;
use Mediagone\Types\Common\Crypto\HashArgon2id;
use Mediagone\Types\Common\Crypto\HashBcrypt;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\Types\Common\Crypto\Hash
 */
final class HashTest extends TestCase
{
    //========================================================================================================
    // Factory
    //========================================================================================================
    
    public function test_can_create_from_a_bcrypt_hash() : void
    {
        $hash = (string)HashBcrypt::fromString('p4ssword');
        self::assertInstanceOf(HashBcrypt::class, Hash::fromHash($hash));
    }
    
    
    public function test_can_create_from_a_argon2id_hash() : void
    {
        $hash = (string)HashArgon2id::fromString('p4ssword');
        self::assertInstanceOf(HashArgon2id::class, Hash::fromHash($hash));
    }
    
    
    
}
