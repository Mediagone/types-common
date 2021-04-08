<?php declare(strict_types=1);

namespace Mediagone\Types\Common\System;

use InvalidArgumentException;
use Mediagone\Types\Common\ValueObject;
use function file_exists;
use function file_get_contents;
use function is_file;
use function is_string;
use function strlen;


final class Binary implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $data;
    
    
    
    //========================================================================================================
    // Constructor & Factory methods
    //========================================================================================================
    
    private function __construct(string $data)
    {
        if (! self::isValueValid($data)) {
            throw new InvalidArgumentException('Invalid binary data');
        }
        
        $this->data = $data;
    }
    
    
    public static function fromString(string $binaryString) : self
    {
        return new self($binaryString);
    }
    
    
    public static function fromBinary(Binary $binary) : self
    {
        return new self($binary->data);
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
        
        return new self($content);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($value) : bool
    {
        return is_string($value);
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->data;
    }
    
    
    public function __toString() : string
    {
        return $this->data;
    }
    
    
    public function getSize() : int
    {
        return strlen($this->data);
    }
    
    
    
}
