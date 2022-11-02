<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Business;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function preg_match;
use function strtoupper;


/**
 * Represents a BIC (Business Identifier Code).
 */
class Bic implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 34;
    
    public const REGEX = '@^'
                        .'[A-Z]{4}' // Bank code
                        .'[A-Z]{2}' // Country code
                        .'[0-9A-Z]{2}' // Location code
                        .'([0-9A-Z]{3})?' // Branch code (optional)
                        .'$@';
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $bic)
    {
        if (! self::isValueValid($bic)) {
            throw new InvalidArgumentException("The supplied BIC ($bic) is invalid.");
        }
        
        $this->value = $bic;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $value) : self
    {
        $normalizedBic = self::normalize($value);
        
        return new self($normalizedBic);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid Iban.
     *
     * @param string $value
     */
    public static function isValueValid($value) : bool
    {
        $value = self::normalize($value);
        
        return preg_match(self::REGEX, $value) === 1;
    }
    
    
    public static function normalize(string $value) : string
    {
        return strtoupper($value);
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
    
    public function equals(Bic $bic) : bool
    {
        return $this->value === $bic->value;
    }
    
    
    
}
