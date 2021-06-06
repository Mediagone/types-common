<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Web;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function strlen;


/**
 * Represents the path part of an Url (eg. /some/path/to/resource.txt)
 * 
 * The value must match the following properties:
 *      - Max length : 600 chars
 *      - The value must start with a slash "/"
 *      - Can contain letters and digits and the following chars: @'!$&()[]*+-_~,.=;:/?%#
 */
final class UrlPath implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    public const MAX_LENGTH = 600;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $path)
    {
        if (! self::isValueValid($path)) {
            throw new InvalidArgumentException('The supplied host path is invalid, got "' . $path . '".');
        }
        
        $this->value = $path;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $url) : self
    {
        return new self($url);
    }
    
    
    public static function fromSegments(... $segments) : self
    {
        return new self('/'. implode('/', $segments));
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid URL's path.
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
        
        $regex = '#^' . Url::PATH_PATTERN . '$#i';
        
        return preg_match($regex, $url) === 1;
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
    
    public function equals(UrlPath $urlPath) : bool
    {
        return $this->value === $urlPath->value;
    }
    
    
    
}
