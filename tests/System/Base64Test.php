<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use Mediagone\Types\Common\System\Base64;
use PHPUnit\Framework\TestCase;
use function base64_encode;
use function file_get_contents;
use function json_encode;


/**
 * @covers \Mediagone\Types\Common\System\Base64
 */
final class Base64Test extends TestCase
{
    //========================================================================================================
    // Creation
    //========================================================================================================
    
    /**
     * @dataProvider stringProvider
     */
    public function test_can_be_created_from_string(string $string) : void
    {
        $base64 = Base64::fromString($string);
        self::assertSame(base64_encode($string), (string)$base64);
        self::assertSame($string, $base64->decode());
    }
    
    public function stringProvider()
    {
        yield ['this is a string'];
        yield ['this is another string !'];
        yield [file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '_file.png')];
    }
    
    
    public function test_can_be_created_from_file() : void
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . '_file.png';
        $fileContent = file_get_contents($filename);
       
        $binary = Base64::fromFile($filename);
        self::assertSame(base64_encode($fileContent), (string)$binary);
        self::assertSame($fileContent, $binary->decode());
    }
    
    
    public function test_can_be_created_from_base64_string() : void
    {
        $base64String = base64_encode('abcdef');
        $base64 = Base64::fromBase64String($base64String);
        
        self::assertSame($base64String, (string)$base64);
        self::assertSame('abcdef', $base64->decode());
    }
    
    
    
    //========================================================================================================
    // Conversion
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $base64 = Base64::fromString('abcdef');
        
        self::assertSame('"'.base64_encode('abcdef').'"', json_encode($base64));
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_string_value_is_valid() : void
    {
        self::assertTrue(Base64::isValueValid('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'));
        self::assertTrue(Base64::isValueValid('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890='));
        self::assertTrue(Base64::isValueValid('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890=='));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Base64::isValueValid(100));
        self::assertFalse(Base64::isValueValid(true));
        self::assertFalse(Base64::isValueValid(null));
        self::assertFalse(Base64::isValueValid(''));
        self::assertFalse(Base64::isValueValid('this_is_an_invalid_string'));
        self::assertFalse(Base64::isValueValid('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890==='));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    /**
     * @dataProvider stringProvider
     */
    public function test_equality_between_base64($value) : void
    {
        $q1 = Base64::fromString($value);
        $q2 = Base64::fromString($value);
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_base64() : void
    {
        $q1 = Base64::fromString('abcdef');
        $q2 = Base64::fromString('zkcd');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
