<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Crypto;

use InvalidArgumentException;
use LogicException;
use function is_string;
use function password_get_info;
use function password_hash;
use function password_verify;
use function preg_match;


/**
 * Represents an Argon2id encrypted hash.
 */
class HashArgon2id extends Hash
{
    //========================================================================================================
    // Constants & Properties
    //========================================================================================================
    
    public const DEFAULT_MEMORY_COST = 128 * 1024;
    public const DEFAULT_TIME_COST = 6;
    public const DEFAULT_THREADS = 2;
    
    private string $value;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $hash)
    {
        if (! self::isValueValid($hash)) {
            throw new InvalidArgumentException('The supplied value is not a valid Argon2id hash.');
        }
        
        $this->value = $hash;
    }
    
    
    /**
     * @return static
     */
    public static function fromHash(string $hash) : self
    {
        return new self($hash);
    }
    
    
    /**
     * Encrypts the given string and creates an argon2id hash from it.
     * @return static
     */
    public static function fromString(string $plainString, array $options = []) : self
    {
        if (! defined('PASSWORD_ARGON2ID')) {
            throw new LogicException('Argon2id hash algorithm is not available, check your PHP version.');
        }
        
        [
            'memory_cost' => $memoryCost,
            'time_cost' => $timeCost,
            'threads' => $threads,
        ] = $options + [
            'memory_cost' => self::DEFAULT_MEMORY_COST,
            'time_cost' => self::DEFAULT_TIME_COST,
            'threads' => self::DEFAULT_THREADS,
        ];
        
        
        if ($memoryCost <= 10 * 1024) {
            throw new InvalidArgumentException('For security reasons, memory cost must be greater than 10kb (6 recommended)');
        }
        if ($timeCost <= 3) {
            throw new InvalidArgumentException('For security reasons, time cost factor must be greater than 3.');
        }
        if ($threads <= 0) {
            throw new InvalidArgumentException('Threads count must be strictly positive (got '.$threads.').');
        }
        
        $hash = password_hash($plainString, PASSWORD_ARGON2ID, ['memory_cost' => $memoryCost, 'time_cost' => $timeCost, 'threads' => $threads]);
        
        return new self($hash);
    }
    
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($hash) : bool
    {
        if (! is_string($hash)) {
            return false;
        }
        
        $regex = '#^'
            . '\$argon2id\$' // hashing algorithm
            . 'v=[0-9]{2}\$' // version
            . 'm=[0-9]+,t=[0-9]{1,2},p=[123456789]{1}\$' // memory-time-threads parameters
            . '[0-9a-zA-Z+/]{22}\$' // a 22 characters long salt
            . '[0-9a-zA-Z+/]{43}' // a 43 characters long hash
            . '$#';
        
        return preg_match($regex, $hash) === 1;
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
    
    
    public function verifyString(string $plainString) : bool
    {
        return password_verify($plainString, $this->value);
    }
    
    
    public function getMemoryCost() : int
    {
        $infos = password_get_info($this->value);
        return (int)$infos['options']['memory_cost'];
    }
    
    
    public function getTimeCost() : int
    {
        $infos = password_get_info($this->value);
        return (int)$infos['options']['time_cost'];
    }
    
    
    public function getThreadsCount() : int
    {
        $infos = password_get_info($this->value);
        return (int)$infos['options']['threads'];
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Hash $hash) : bool
    {
        if ($hash instanceof HashArgon2id) {
            return $this->value === $hash->value;
        }
        
        return false;
    }
    
    
    
}
