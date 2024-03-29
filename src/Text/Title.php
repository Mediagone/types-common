<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function mb_strlen;
use function preg_match;


/**
 * Represents a Title string.
 *
 * The value must match the following properties:
 *      - 0 to 250 chars long
 *      - can contain letters (with accents)
 *      - can contain hyphens
 *      - can contain spaces
 *      - can contain special characters
 */
class Title implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 250;
    
    public const REGEX_CHAR = "[[:print:]]";
    public const REGEX = '#^'.self::REGEX_CHAR.'{0,'.self::MAX_LENGTH.'}$#u';
    
    
    
    //========================================================================================================
    //
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $title)
    {
        if (mb_strlen($title) > self::MAX_LENGTH) {
            throw new InvalidArgumentException('The supplied title is too long ('.self::MAX_LENGTH.' chars max. allowed), got '.mb_strlen($title).' in "' . $title . '".');
        }
        
        if (! self::isValueValid($title)) {
            throw new InvalidArgumentException("The supplied title is invalid ($title).");
        }
        
        $this->value = $title;
    }
    
    
    /**
     * Creates a new instance from the given string.
     * @return static
     */
    public static function fromString(string $title) : self
    {
        return new self($title);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Title.
     */
    public static function isValueValid($title) : bool
    {
        if (! is_string($title)) {
            return false;
        }
        
        return preg_match(self::REGEX, $title) === 1;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    #[\ReturnTypeWillChange]
    public function jsonSerialize() : string
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
    
    public function equals(Title $title) : bool
    {
        return $this->value === $title->value;
    }
    
    
    
}
