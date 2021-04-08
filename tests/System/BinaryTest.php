<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\System;

use Mediagone\Types\Common\System\Binary;
use PHPUnit\Framework\TestCase;
use function file_get_contents;
use function filesize;
use function json_encode;


/**
 * @covers \Mediagone\Types\Common\System\Binary
 */
final class BinaryTest extends TestCase
{
    //========================================================================================================
    // Creation
    //========================================================================================================
    
    /**
     * @dataProvider binaryStringProvider
     */
    public function test_can_be_created_from_string(string $binaryString) : void
    {
        $binary = Binary::fromString($binaryString);
        self::assertSame($binaryString, (string)$binary);
        self::assertSame(strlen($binaryString), $binary->getSize());
    }
    
    
    public function binaryStringProvider()
    {
        yield [''];
        yield ['abcdef'];
        yield [file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'Binary.png')];
    }
    
    
    public function test_can_be_created_from_file() : void
    {
        $filename = __DIR__ . DIRECTORY_SEPARATOR . 'Binary.png';
        $fileContent = file_get_contents($filename);
        $fileSize = filesize($filename);
        
        $binary = Binary::fromFile($filename);
        self::assertSame($fileContent, (string)$binary);
        self::assertSame($fileSize, $binary->getSize());
    }
    
    
    public function test_can_be_created_from_binary() : void
    {
        $binaryA = Binary::fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'Binary.png');
        $binaryB = Binary::fromBinary($binaryA);
        
        self::assertSame((string)$binaryA, (string)$binaryB);
        self::assertSame($binaryA->getSize(), $binaryB->getSize());
    }
    
    
    
    //========================================================================================================
    // Conversion
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $binary = Binary::fromString('abcdef');
        
        self::assertSame('"abcdef"', json_encode($binary));
    }
    
    
    
    //========================================================================================================
    // Misc
    //========================================================================================================
    
    public function test_can_tell_string_value_is_valid() : void
    {
        self::assertTrue(Binary::isValueValid('this_is_a_string'));
    }
    
    
    public function test_can_tell_non_string_value_is_invalid() : void
    {
        self::assertFalse(Binary::isValueValid(100));
        self::assertFalse(Binary::isValueValid(true));
        self::assertFalse(Binary::isValueValid(null));
    }
    
    
    
}
