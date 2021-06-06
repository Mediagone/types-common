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
    
    
    public function test_inequality_between_different_hashes() : void
    {
        $q1 = HashArgon2id::fromHash('$argon2id$v=19$m=65536,t=4,p=1$MGU4dnY2Lkw2bHpmTzV5Wg$u7LBqzixVlVzvWTcbxHGpGTj6FyStwInN67cTGZBNXI');
        $q2 = HashBcrypt::fromHash('$2y$12$11100000000000000000000000000000000000000000000000000');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
}
