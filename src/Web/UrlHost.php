<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Web;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function strlen;


/**
 * Represents the host part of an Url (scheme+host without trailing slash, eg. https://domain.com )
 *
 * The value must match the following pattern: {scheme}://{domain}.{extension}
 *      - max length : 255 chars
 * 
 * Each part must match the following properties:
 *      {scheme}:
 *          - http or https
 *      {domain}:
 *          - can contain letters and digits
 *          - can have up to 10 subdomains + 1 extension (separated with a dot)
 *          - can contain hyphens (not consecutive)
 *          - must start and end with a letter or a digit
 */
class UrlHost implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    public const MAX_LENGTH = Url::SCHEME_MAX_LENGTH + Url::DOMAIN_MAX_LENGTH;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $url)
    {
        if (! self::isValueValid($url)) {
            throw new InvalidArgumentException('The supplied host url is invalid, got "' . $url . '".');
        }
        
        $this->value = $url;
    }
    
    
    /**
     * Creates a new instance from the given string.
     * @return static
     */
    public static function fromString(string $url) : self
    {
        return new self($url);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid URL's host.
     *
     * @param string $url
     */
    public static function isValueValid($url) : bool
    {
        if (! is_string($url)) {
            return false;
        }
        
        if (strlen($url) > self::MAX_LENGTH) {
            return false;
        }
        
        $regex = '#^'
            . Url::SCHEME_PATTERN
            . Url::DOMAIN_PATTERN
            . '$#i';
        
        return preg_match($regex, $url) === 1;
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
    
    public function equals(UrlHost $urlHost) : bool
    {
        return $this->value === $urlHost->value;
    }
    
    
    
}
