<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Web;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;


/**
 * Represents an Email Address, but DOESN'T strictly follow RFC 5322 and RFC 6854.
 * 
 * The value must match the following pattern: {local}@{domain}.{extension}
 * 
 * Each part must match the following properties:
 *      {local}:
 *          - 1 to 30 chars long
 *          - can contain letters and digits
 *          - can contain dots and hyphens (not consecutive)
 *          - must start and end with a letter or a digit
 *      {domain}:
 *          - 1 to 30 chars long
 *          - can contain letters and digits
 *          - can contain hyphens (not consecutive)
 *          - must start and end with a letter or a digit
 *      {extension}:
 *          - 2 to 8 chars long
 *          - only letters
 */
class EmailAddress implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_ATOM_LENGTH = 30;
    
    public const MAX_DOMAIN_LENGTH = 30;
    
    public const MIN_EXTENSION_LENGTH = 2;
    public const MAX_EXTENSION_LENGTH = 8;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $address)
    {
        if (! self::isValueValid($address)) {
            throw new InvalidArgumentException("The supplied email address is invalid ($address).");
        }
        
        $this->value = $address;
    }
    
    
    /**
     * Creates a new instance from the given string.
     * @return static
     */
    public static function fromString(string $address) : self
    {
        return new self($address);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid email address.
     *
     * @param string $email
     */
    public static function isValueValid($email) : bool
    {
        if (! is_string($email)) {
            return false;
        }
        
        return self::checkLength($email) && self::checkPattern($email);
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
    
    public function equals(EmailAddress $email) : bool
    {
        return $this->value === $email->value;
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private static function checkLength(string $email) : bool
    {
        $regex = '#^'
            . '[^@]{1,'.self::MAX_ATOM_LENGTH.'}'
            . '@'
            . '.{1,'.self::MAX_DOMAIN_LENGTH.'}'
            . '\.'
            . '.{'.self::MIN_EXTENSION_LENGTH.','.self::MAX_EXTENSION_LENGTH.'}'
            . '$#i';
        
        return preg_match($regex, $email) === 1;
    }
    
    
    private static function checkPattern(string $email) : bool
    {
        $regex = '#^'
            // local part
            . '('
            . '[a-z0-9]+' // starts with a letter or digit
            . '([-_.]?' // allows hyphens, underscores or dots (not alongside each other)
            . '[a-z0-9]+)*' // ends with letters or digits
            . ')'
            // domain part
            . '@('
            . '[a-z0-9]+' // starts with a letter or digit
            . '([-.]?[a-z0-9]+)*' // allows hyphens (not alongside each other)
            . ')'
            // extension part
            . '\.'
            . '[a-z]+' // allows letters only
            . '$#i';
        
        return preg_match($regex, $email) === 1;
    }
    
    
    
}
