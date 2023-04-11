<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Crypto;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function hash;
use function hex2bin;
use function is_string;
use function preg_match;


/**
 * Represents a SHA512 hash representation.
 */
class Sha512 implements ValueObject
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
    
    
    /**
     * @return static
     */
    public static function fromHash(string $hash) : self
    {
        return new self($hash);
    }
    
    
    /**
     * @return static
     */
    public static function fromString(string $string) : self
    {
        return new self(self::hashString($string));
    }
    
    
    /**
     * @return static
     */
    public static function fromBinaryString(string $binary) : self
    {
        return new self(bin2hex($binary));
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
    
    #[\ReturnTypeWillChange]
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
    
    
    public function equals(Sha512 $hash) : bool
    {
        return $this->value === $hash->value;
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private static function hashString(string $string) : string
    {
        return hash('sha512', $string, false);
    }
    
    
    
}
