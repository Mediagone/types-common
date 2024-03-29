<?php declare(strict_types=1);

namespace Mediagone\Types\Common\Business;

use InvalidArgumentException;
use Mediagone\Types\Common\Geo\Country;
use Mediagone\Types\Common\ValueObject;
use function ctype_alnum;
use function ctype_upper;
use function ord;
use function preg_match;
use function str_repeat;
use function str_replace;
use function str_split;
use function strlen;
use function strtoupper;
use function substr;


/**
 * Represents an Iban number.
 * 
 * @note Based on Symfony\Component\Validator\Constraints\IbanValidator
 */
class Iban implements ValueObject
{
    //========================================================================================================
    // Constants
    //========================================================================================================
    
    public const MAX_LENGTH = 34;
    
    public const FORMATS = [
        'AD' => 'AD\d{2}\d{4}\d{4}[\dA-Z]{12}', // Andorra
        'AE' => 'AE\d{2}\d{3}\d{16}', // United Arab Emirates
        'AL' => 'AL\d{2}\d{8}[\dA-Z]{16}', // Albania
        'AO' => 'AO\d{2}\d{21}', // Angola
        'AT' => 'AT\d{2}\d{5}\d{11}', // Austria
        'AX' => 'FI\d{2}\d{6}\d{7}\d{1}', // Aland Islands
        'AZ' => 'AZ\d{2}[A-Z]{4}[\dA-Z]{20}', // Azerbaijan
        'BA' => 'BA\d{2}\d{3}\d{3}\d{8}\d{2}', // Bosnia and Herzegovina
        'BE' => 'BE\d{2}\d{3}\d{7}\d{2}', // Belgium
        'BF' => 'BF\d{2}\d{23}', // Burkina Faso
        'BG' => 'BG\d{2}[A-Z]{4}\d{4}\d{2}[\dA-Z]{8}', // Bulgaria
        'BH' => 'BH\d{2}[A-Z]{4}[\dA-Z]{14}', // Bahrain
        'BI' => 'BI\d{2}\d{12}', // Burundi
        'BJ' => 'BJ\d{2}[A-Z]{1}\d{23}', // Benin
        'BY' => 'BY\d{2}[\dA-Z]{4}\d{4}[\dA-Z]{16}', // Belarus - https://bank.codes/iban/structure/belarus/
        'BL' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Saint Barthelemy
        'BR' => 'BR\d{2}\d{8}\d{5}\d{10}[A-Z][\dA-Z]', // Brazil
        'CG' => 'CG\d{2}\d{23}', // Congo
        'CH' => 'CH\d{2}\d{5}[\dA-Z]{12}', // Switzerland
        'CI' => 'CI\d{2}[A-Z]{1}\d{23}', // Ivory Coast
        'CM' => 'CM\d{2}\d{23}', // Cameron
        'CR' => 'CR\d{2}0\d{3}\d{14}', // Costa Rica
        'CV' => 'CV\d{2}\d{21}', // Cape Verde
        'CY' => 'CY\d{2}\d{3}\d{5}[\dA-Z]{16}', // Cyprus
        'CZ' => 'CZ\d{2}\d{20}', // Czech Republic
        'DE' => 'DE\d{2}\d{8}\d{10}', // Germany
        'DO' => 'DO\d{2}[\dA-Z]{4}\d{20}', // Dominican Republic
        'DK' => 'DK\d{2}\d{4}\d{10}', // Denmark
        'DZ' => 'DZ\d{2}\d{20}', // Algeria
        'EE' => 'EE\d{2}\d{2}\d{2}\d{11}\d{1}', // Estonia
        'ES' => 'ES\d{2}\d{4}\d{4}\d{1}\d{1}\d{10}', // Spain (also includes Canary Islands, Ceuta and Melilla)
        'FI' => 'FI\d{2}\d{6}\d{7}\d{1}', // Finland
        'FO' => 'FO\d{2}\d{4}\d{9}\d{1}', // Faroe Islands
        'FR' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // France
        'GF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // French Guyana
        'GB' => 'GB\d{2}[A-Z]{4}\d{6}\d{8}', // United Kingdom of Great Britain and Northern Ireland
        'GE' => 'GE\d{2}[A-Z]{2}\d{16}', // Georgia
        'GI' => 'GI\d{2}[A-Z]{4}[\dA-Z]{15}', // Gibraltar
        'GL' => 'GL\d{2}\d{4}\d{9}\d{1}', // Greenland
        'GP' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Guadeloupe
        'GR' => 'GR\d{2}\d{3}\d{4}[\dA-Z]{16}', // Greece
        'GT' => 'GT\d{2}[\dA-Z]{4}[\dA-Z]{20}', // Guatemala
        'HR' => 'HR\d{2}\d{7}\d{10}', // Croatia
        'HU' => 'HU\d{2}\d{3}\d{4}\d{1}\d{15}\d{1}', // Hungary
        'IE' => 'IE\d{2}[A-Z]{4}\d{6}\d{8}', // Ireland
        'IL' => 'IL\d{2}\d{3}\d{3}\d{13}', // Israel
        'IR' => 'IR\d{2}\d{22}', // Iran
        'IS' => 'IS\d{2}\d{4}\d{2}\d{6}\d{10}', // Iceland
        'IT' => 'IT\d{2}[A-Z]{1}\d{5}\d{5}[\dA-Z]{12}', // Italy
        'JO' => 'JO\d{2}[A-Z]{4}\d{4}[\dA-Z]{18}', // Jordan
        'KW' => 'KW\d{2}[A-Z]{4}\d{22}', // KUWAIT
        'KZ' => 'KZ\d{2}\d{3}[\dA-Z]{13}', // Kazakhstan
        'LB' => 'LB\d{2}\d{4}[\dA-Z]{20}', // LEBANON
        'LI' => 'LI\d{2}\d{5}[\dA-Z]{12}', // Liechtenstein (Principality of)
        'LT' => 'LT\d{2}\d{5}\d{11}', // Lithuania
        'LU' => 'LU\d{2}\d{3}[\dA-Z]{13}', // Luxembourg
        'LV' => 'LV\d{2}[A-Z]{4}[\dA-Z]{13}', // Latvia
        'MC' => 'MC\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Monaco
        'MD' => 'MD\d{2}[\dA-Z]{2}[\dA-Z]{18}', // Moldova
        'ME' => 'ME\d{2}\d{3}\d{13}\d{2}', // Montenegro
        'MF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Saint Martin (French part)
        'MG' => 'MG\d{2}\d{23}', // Madagascar
        'MK' => 'MK\d{2}\d{3}[\dA-Z]{10}\d{2}', // Macedonia, Former Yugoslav Republic of
        'ML' => 'ML\d{2}[A-Z]{1}\d{23}', // Mali
        'MQ' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Martinique
        'MR' => 'MR13\d{5}\d{5}\d{11}\d{2}', // Mauritania
        'MT' => 'MT\d{2}[A-Z]{4}\d{5}[\dA-Z]{18}', // Malta
        'MU' => 'MU\d{2}[A-Z]{4}\d{2}\d{2}\d{12}\d{3}[A-Z]{3}', // Mauritius
        'MZ' => 'MZ\d{2}\d{21}', // Mozambique
        'NC' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // New Caledonia
        'NL' => 'NL\d{2}[A-Z]{4}\d{10}', // The Netherlands
        'NO' => 'NO\d{2}\d{4}\d{6}\d{1}', // Norway
        'PF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // French Polynesia
        'PK' => 'PK\d{2}[A-Z]{4}[\dA-Z]{16}', // Pakistan
        'PL' => 'PL\d{2}\d{8}\d{16}', // Poland
        'PM' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Saint Pierre et Miquelon
        'PS' => 'PS\d{2}[A-Z]{4}[\dA-Z]{21}', // Palestine, State of
        'PT' => 'PT\d{2}\d{4}\d{4}\d{11}\d{2}', // Portugal (plus Azores and Madeira)
        'QA' => 'QA\d{2}[A-Z]{4}[\dA-Z]{21}', // Qatar
        'RE' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Reunion
        'RO' => 'RO\d{2}[A-Z]{4}[\dA-Z]{16}', // Romania
        'RS' => 'RS\d{2}\d{3}\d{13}\d{2}', // Serbia
        'SA' => 'SA\d{2}\d{2}[\dA-Z]{18}', // Saudi Arabia
        'SE' => 'SE\d{2}\d{3}\d{16}\d{1}', // Sweden
        'SI' => 'SI\d{2}\d{5}\d{8}\d{2}', // Slovenia
        'SK' => 'SK\d{2}\d{4}\d{6}\d{10}', // Slovak Republic
        'SM' => 'SM\d{2}[A-Z]{1}\d{5}\d{5}[\dA-Z]{12}', // San Marino
        'SN' => 'SN\d{2}[A-Z]{1}\d{23}', // Senegal
        'TF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // French Southern Territories
        'TL' => 'TL\d{2}\d{3}\d{14}\d{2}', // Timor-Leste
        'TN' => 'TN59\d{2}\d{3}\d{13}\d{2}', // Tunisia
        'TR' => 'TR\d{2}\d{5}[\dA-Z]{1}[\dA-Z]{16}', // Turkey
        'UA' => 'UA\d{2}\d{6}[\dA-Z]{19}', // Ukraine
        'VA' => 'VA\d{2}\d{3}\d{15}', // Vatican City State
        'VG' => 'VG\d{2}[A-Z]{4}\d{16}', // Virgin Islands, British
        'WF' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Wallis and Futuna Islands
        'XK' => 'XK\d{2}\d{4}\d{10}\d{2}', // Republic of Kosovo
        'YT' => 'FR\d{2}\d{5}\d{5}[\dA-Z]{11}\d{2}', // Mayotte
    ];
    
    
    
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $value;
    
    private Country $country;
    
    public function getCountry() : Country
    {
        return $this->country;
    }
    
    
    private string $bban;
    
    public function getBasicBankAccountNumber() : string
    {
        return $this->bban;
    }
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    private function __construct(string $iban)
    {
        if (! self::isValueValid($iban)) {
            throw new InvalidArgumentException("The supplied IBAN ($iban) is invalid.");
        }
        
        $this->value = $iban;
        $this->country = Country::fromAlpha2(substr($iban, 0, 2));
        $this->bban = substr($iban, 4);
    }
    
    
    /**
     * Creates a new instance from the given string.
     */
    public static function fromString(string $value) : self
    {
        $normalizedIban = self::normalize($value);
        
        return new self($normalizedIban);
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
        
        if (! ctype_alnum($value)) {
            return false;
        }
        
        $countryCode = substr($value, 0, 2);
        if (! isset(self::FORMATS[$countryCode])) {
            return false;
        }
        
        if (! self::checksumIsValid($value)) {
            return false;
        }
        
        return preg_match('@^'.self::FORMATS[$countryCode].'$@', $value) === 1;
    }
    
    
    public static function normalize(string $value) : string
    {
        return str_replace(' ', '', strtoupper($value));
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
    
    
    public function toMasked() : string
    {
        return substr($this->value, 0, 5)
              .str_repeat('_', strlen($this->value) - 8)
              .substr($this->value, -3);
    }
    
    
    
    //========================================================================================================
    // Operations methods
    //========================================================================================================
    
    public function equals(Iban $iban) : bool
    {
        return $this->value === $iban->value;
    }
    
    
    
    //========================================================================================================
    // Helper methods
    //========================================================================================================
    
    private static function checksumIsValid(string $value) : bool
    {
        // Move the first four characters to the end
        // e.g. CH93 0076 2011 6238 5295 7
        //   -> 0076 2011 6238 5295 7 CH93
        $value = substr($value, 4).substr($value, 0, 4);
        
        // Convert all remaining letters to their ordinals
        // The result is an integer, which is too large for PHP's int
        // data type, so we store it in a string instead.
        // e.g. 0076 2011 6238 5295 7 CH93
        //   -> 0076 2011 6238 5295 7 121893
        $checkSum = self::toBigInt($value);
        
        // Do a modulo-97 operation on the large integer
        // We cannot use PHP's modulo operator, so we calculate the
        // modulo step-wisely instead
        return self::bigModulo97($checkSum) === 1;
    }
    
    
    private static function toBigInt(string $string) : string
    {
        $chars = str_split($string);
        $bigInt = '';
        
        foreach ($chars as $char) {
            // Convert uppercase characters to ordinals, starting with 10 for "A"
            if (ctype_upper($char)) {
                $bigInt .= (ord($char) - 55);
                
                continue;
            }
            
            // Simply append digits
            $bigInt .= $char;
        }
        
        return $bigInt;
    }
    
    
    private static function bigModulo97(string $bigInt) : int
    {
        $parts = str_split($bigInt, 7);
        $rest = 0;
        
        foreach ($parts as $part) {
            $rest = ($rest.$part) % 97;
        }
        
        return $rest;
    }
    
    
    
}
