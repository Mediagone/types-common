<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Crypto;

use LogicException;
use Mediagone\Types\Common\ValueObject;


/**
 * Represents a generic encrypted hash.
 */
abstract class Hash implements ValueObject
{
    /**
     * @return static
     */
    public static function fromHash(string $hash) : self
    {
        $infos = password_get_info($hash);
        
        switch ($infos['algo']) {
            case '2y':
                return HashBcrypt::fromHash($hash);
            case 'argon2id':
                return HashArgon2id::fromHash($hash);
        }
        
        throw new LogicException('This hash algorithm('.$infos['algo'].') is not supported.');
    }
    
    /**
     * @return static
     */
    abstract public static function fromString(string $plainString, array $options = []) : self;
    
    abstract public function verifyString(string $plainString) : bool;
    
    abstract public function equals(Hash $hash) : bool;
}
