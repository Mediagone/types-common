<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Geo;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function preg_match;
use function strlen;


/**
 * Represents a City name.
 *
 * The value must match the following properties:
 *      - 0 to 100 chars long
 *      - can contain letters (with accents)
 *      - can contain hyphens (between words only)
 *      - can contain spaces (between words only)
 *      - can contain apostrophe (between words only)
 */
class City implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 100;
    
    public const REGEX_CHAR = "[a-zA-ZÀ-ÖØ-öø-ÿ]";
    
    public const REGEX = '#^('.self::REGEX_CHAR.'+|'.self::REGEX_CHAR."+([-' ]".self::REGEX_CHAR.'+)*)?$#';
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $city)
    {
        if (! self::isValueValid($city)) {
            throw new InvalidArgumentException("The supplied city name ($city) is invalid.");
        }
        
        $this->value = $city;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $city) : self
    {
        return new self($city);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid City name.
     *
     * @param string $city
     */
    public static function isValueValid($city) : bool
    {
        if (! is_string($city)) {
            return false;
        }
        
        return preg_match(self::REGEX, $city) === 1 && strlen($city) <= self::MAX_LENGTH;
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
    
    public function equals(City $city) : bool
    {
        return $this->value === $city->value;
    }
    
    
    
}
