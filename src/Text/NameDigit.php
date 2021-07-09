<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function preg_match;


/**
 * Represents a Name string that can contain digits.
 *
 * The value must match the following properties:
 *      - 0 to 50 chars long
 *      - can contain letters (with accents)
 *      - can contain digits
 *      - can contain hyphens
 *      - can contain spaces
 *      - can contain apostrophe
 */
final class NameDigit implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 50;
    
    public const REGEX_CHAR = "[- 'a-zA-ZÀ-ÖØ-öø-ÿ0-9]";
    
    public const REGEX = '#^'.self::REGEX_CHAR.'{0,'.self::MAX_LENGTH.'}$#';
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $name)
    {
        if (! self::isValueValid($name)) {
            throw new InvalidArgumentException("The supplied name ($name) is invalid.");
        }
        
        $this->value = $name;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $name) : self
    {
        return new self($name);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Name.
     *
     * @param string $name
     */
    public static function isValueValid($name) : bool
    {
        if (! is_string($name)) {
            return false;
        }
        
        return preg_match(self::REGEX, $name) === 1;
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
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(NameDigit $name) : bool
    {
        return $this->value === $name->value;
    }
    
    
    
}
