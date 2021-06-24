<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Crypto;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function hash;
use function hex2bin;
use function is_string;
use function preg_match;


/**
 * Represents a SHA512 hash representation of RandomToken.
 */
final class RandomTokenHash implements ValueObject
{
    //========================================================================================================
    // Constants & Properties
    //========================================================================================================
    
    public const BINARY_LENGTH = 64;
    
    public const HEX_LENGTH = 128;
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $hash)
    {
        if (! self::isValueValid($hash)) {
            throw new InvalidArgumentException('The supplied value is not a valid hash.');
        }
        
        $this->value = $hash;
    }
    
    
    public static function fromHash(string $hash) : self
    {
        return new self($hash);
    }
    
    
    public static function fromBinaryString(string $binary) : self
    {
        return new self(bin2hex($binary));
    }
    
    
    public static function fromToken(RandomToken $token) : self
    {
        return new self(self::hashToken($token));
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($hash) : bool
    {
        if (! is_string($hash)) {
            return false;
        }
        
        return preg_match('#^[0-9a-f]{'.self::HEX_LENGTH.'}$#i', $hash) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->value;
    }
    
    
    public function __toString() : string
    {
        return $this->value;
    }
    
    
    public function toBinaryString() : string
    {
        return hex2bin($this->value);
    }
    
    
    public function equals(RandomTokenHash $hash) : bool
    {
        return $this->value === $hash->value;
    }
    
    
    public function verifyToken(RandomToken $token) : bool
    {
        return $this->value === self::hashToken($token);
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private static function hashToken(RandomToken $token) : string
    {
        return hash('sha512', (string)$token, false);
    }
    
    
    
}
