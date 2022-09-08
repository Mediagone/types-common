<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function preg_match;
use function strlen;


/**
 * Represents an Address line.
 *
 * The value must match the following properties:
 *      - 0 to 38 chars long (up to 152 bytes)
 *      - can contain any printable characters
 */
final class Address implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 152;
    
    public const REGEX_CHAR = "[[:print:]]";
    
    public const REGEX = '#^'.self::REGEX_CHAR.'{0,'.self::MAX_LENGTH.'}$#u';
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $address)
    {
        if (! self::isValueValid($address)) {
            throw new InvalidArgumentException("The supplied address ($address) is invalid.");
        }
        
        $this->value = $address;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $address) : self
    {
        return new self($address);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Address.
     *
     * @param string $address
     */
    public static function isValueValid($address) : bool
    {
        if (! is_string($address)) {
            return false;
        }
        
        return preg_match(self::REGEX, $address) === 1 && strlen($address) <= self::MAX_LENGTH;
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
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Address $address) : bool
    {
        return $this->value === $address->value;
    }
    
    
    
}
