<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Text;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function is_string;
use function preg_match;
use function preg_replace;
use function strlen;
use function strtolower;
use function trim;


/**
 * Represents a Slug string, but with underscore as separator.
 *
 * The value must match the following properties:
 *      - 1 to 200 chars long
 *      - can contain lowercase letters and digits
 *      - can contain hyphens (not consecutive)
 */
class SlugSnake implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 200;
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $slug)
    {
        if (! self::isValueValid($slug)) {
            throw new InvalidArgumentException("The supplied SlugSnake ($slug) is invalid.");
        }
        
        $this->value = $slug;
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $slug) : self
    {
        return new self($slug);
    }
    
    
    /**
     * Slugify the given string and creates a new instance from it.
     */
    public static function slugify(string $string) : self
    {
        $string = strtolower($string);
        
        // Replace accentuated characters
        $string = Slug::removeAccents($string);
        
        // Replace whitespaces by underscores
        $string = preg_replace('@\s+@', '_', $string);
        
        // Replace everything (except letters and digits) by undercores
        $string = preg_replace('@[^a-z0-9]+@', '_', $string);
        
        // Replace adjacent underscores
        $string = preg_replace("@_+@", '_', $string);
        
        // Remove trailing and leading underscores
        $string = trim($string, '_');
        
        return new self($string);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid slug.
     *
     * @param string $slug
     */
    public static function isValueValid($slug) : bool
    {
        if (! is_string($slug)) {
            return false;
        }
        
        if (strlen($slug) > self::MAX_LENGTH) {
            return false;
        }
        
        $regex = '#^'
            . '[a-z0-9]+' // starts with a letter or digit
            . '(_?' // allows underscores (not alongside each other)
            . '[a-z0-9]+)*' // ends with letters or digits
            . '$#';
        
        return preg_match($regex, $slug) === 1;
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
    
    public function equals(SlugSnake $slug) : bool
    {
        return $this->value === $slug->value;
    }
    
    
    
}
