<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Graphics;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function dechex;
use function hexdec;
use function is_string;
use function preg_match;
use function str_pad;
use function str_split;


final class Color implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const REGEX = '@^#(?:'
        .'(?<color3>[a-f0-9]{3})'
        .'|'
        .'(?<color6>[a-f0-9]{6})'
        .')$@i';
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private int $r;
    
    public function getR() : int
    {
        return $this->r;
    }
    
    
    private int $g;
    
    public function getG() : int
    {
        return $this->g;
    }
    
    
    private int $b;
    
    public function getB() : int
    {
        return $this->b;
    }
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(int $r, int $g, int $b)
    {
        if ($r < 0 || $r > 255) {
            throw new InvalidArgumentException("The supplied Red value for Color must be in [0,255] range, got '$r'.");
        }
        if ($g < 0 || $g > 255) {
            throw new InvalidArgumentException("The supplied Green value for Color must be in [0,255] range, got '$g'.");
        }
        if ($b < 0 || $b > 255) {
            throw new InvalidArgumentException("The supplied Blue value for Color must be in [0,255] range, got '$b'.");
        }
        
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }
    
    
    /**
     * Accepts #RGB and #RRGGBB formats.
     */
    public static function fromString(string $hex) : self
    {
        //if (! preg_match('@^#(?:'. '(?<color>[a-f0-9]{3}|[a-f0-9]{4}|[a-f0-9]{6}|[a-f0-9]{8})' .')$@i', $hex, $matches))
        if (! preg_match(self::REGEX, $hex, $matches)
        )
        {
            throw new InvalidArgumentException("Unexpected Color's hexadecimal format ($hex)");
        }
        
        if (isset($matches['color6'])) {
            [$r, $g, $b] = str_split($matches['color6'], 2);
            return new self(hexdec($r), hexdec($g), hexdec($b));
        }
        
        [$r, $g, $b] = str_split($matches['color3'], 1);
        return new self(hexdec($r.$r), hexdec($g.$g), hexdec($b.$b));
    }
    
    
    public static function fromColor(Color $color) : self
    {
        return new self($color->r, $color->g, $color->b);
    }
    
    
    public static function fromRgb(int $red, int $green, int $blue) : self
    {
        return new self($red, $green, $blue);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    /**
     * Returns whether the given value is a valid hex Color.
     */
    public static function isValueValid($color) : bool
    {
        if (! is_string($color)) {
            return false;
        }
        
        return preg_match(self::REGEX, $color) === 1;
    }
    
    
    
    //========================================================================================================
    // Serialization Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return (string)$this;
    }
    
    
    /**
     * @return string A color hex string, always in #RRGGBB format.
     */
    public function __toString() : string
    {
        $r = dechex($this->r);
        $g = dechex($this->g);
        $b = dechex($this->b);
        
        $r = str_pad($r, 2, '0', STR_PAD_LEFT);
        $g = str_pad($g, 2, '0', STR_PAD_LEFT);
        $b = str_pad($b, 2, '0', STR_PAD_LEFT);
        
        return '#'.$r.$g.$b;
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Color $color) : bool
    {
        return (string)$this === (string)$color;
    }
    
    
    
}
