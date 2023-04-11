<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Crypto;

use InvalidArgumentException;
use Mediagone\Types\Common\System\Hex;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function preg_match;


/**
 * Represents a random hexadecimal token (40 chars long).
 */
class RandomToken implements ValueObject
{
    //========================================================================================================
    // Constants & Properties
    //========================================================================================================
    
    public const LENGTH = 40;
    
    private Hex $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(Hex $hex)
    {
        if ($hex->getLength() !== self::LENGTH) {
            throw new InvalidArgumentException('The supplied value is not a valid Hex value.');
        }
        
        $this->value = $hex;
    }
    
    
    /**
     * @return static
     */
    public static function random() : self
    {
        return new self(Hex::random(self::LENGTH));
    }
    
    
    /**
     * @return static
     */
    final public static function fromHex(Hex $hex) : self
    {
        return new self($hex);
    }
    
    
    /**
     * @return static
     */
    public static function fromHexString(string $hexString) : self
    {
        if (! self::isValueValid($hexString)) {
            throw new InvalidArgumentException('The supplied value is not a valid Hex string.');
        }
        
        return new self(Hex::fromString($hexString));
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($token) : bool
    {
        if (! is_string($token)) {
            return false;
        }
        
        return preg_match('#^[0-9a-f]{'.self::LENGTH.'}$#i', $token) === 1;
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
        return (string)$this->value;
    }
    
    
    public function equals(RandomToken $token) : bool
    {
        return $this->value->equals($token->value);
    }
    
    
    
}
