<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function base64_decode;
use function base64_encode;
use function file_exists;
use function file_get_contents;
use function is_file;
use function is_string;
use function preg_match;


class Base64 implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor & Factory methods
    //========================================================================================================
    
    private function __construct(string $base64)
    {
        if (! self::isValueValid($base64)) {
            throw new InvalidArgumentException("Invalid Base64 string ($base64)");
        }
        
        $this->value = $base64;
    }
    
    
    public static function fromString(string $string) : self
    {
        return new self(base64_encode($string));
    }
    
    
    public static function fromBase64String(string $base64String) : self
    {
        return new self($base64String);
    }
    
    
    public static function fromFile(string $filename) : self
    {
        if (! file_exists($filename) || ! is_file($filename)) {
            throw new InvalidArgumentException("Impossible to read file because it doesn't exist ($filename).");
        }
        
        $content = file_get_contents($filename);
        if ($content === false) {
            throw new InvalidArgumentException("An error occurred while reading file ($filename).");
        }
        
        return self::fromString($content);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($value) : bool
    {
        if (! is_string($value)) {
            return false;
        }
        
        return preg_match('#^[a-z0-9+/]+={0,2}$#i', $value) === 1;
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
    
    
    public function decode() : string
    {
        return base64_decode($this->value);
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Base64 $base64) : bool
    {
        return $this->value === $base64->value;
    }
    
    
    
}
