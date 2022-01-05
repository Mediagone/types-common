<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Business;

use InvalidArgumentException;
use JsonSerializable;
use Mediagone\Types\Common\Business\Bic;
use PHPUnit\Framework\TestCase;
use function json_encode;


/**
 * @covers \Mediagone\Types\Common\Business\Bic
 */
final class BicTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_max_length_constant() : void
    {
        self::assertTrue(defined(Bic::class . '::MAX_LENGTH'));
    }
    
    
    /**
     * @dataProvider validProvider
     */
    public function test_can_be_created_from_valid_format($value) : void
    {
        // self::assertInstanceOf(Iban::class, Iban::fromString($value));
        self::assertSame(Bic::normalize($value), (string)Bic::fromString($value));
    }
    
    /**
     * @dataProvider validProvider
     */
    public function test_can_tell_value_is_valid(string $value) : void
    {
        self::assertTrue(Bic::isValueValid($value));
    }
    
    public function validProvider()
    {
        yield ['SOGEFRPP']; // without branch code
        yield ['SOGEFRP1']; // digit in location code
        yield ['SOGEFR1P'];
        yield ['SOGEFRPPPAR']; // with branch code
        yield ['SOGEFRPP1AR']; // digit in branch code
        yield ['SOGEFRPPP1R'];
        yield ['SOGEFRPPPA1'];
    }
    
    
    /**
     * @dataProvider invalidProvider
     */
    public function test_cannot_be_created_from_invalid_format(string $value) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Bic::fromString($value);
    }
    
    /**
     * @dataProvider invalidProvider
     */
    public function test_can_tell_value_is_invalid(string $value) : void
    {
        self::assertFalse(Bic::isValueValid($value));
    }
    
    public function invalidProvider()
    {
        yield [''];
        yield ['1OGEFRPP']; // digit in bank code
        yield ['S1GEFRPP'];
        yield ['SO1EFRPP'];
        yield ['SOG1FRPP'];
        yield ['SOGE1RPP']; // digit in country code
        yield ['SOGEF1PP'];
        yield ['SOGEFRP']; // location code too short
        yield ['SOGEFRPPPA']; // branch code too short
        yield ['SOGEFRPPP'];
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'SOGEFRPPPAR';
        $iban = Bic::fromString($value);
       
        self::assertInstanceOf(JsonSerializable::class, $iban);
        self::assertJson(json_encode($iban->jsonSerialize()));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'SOGEFRPPPAR';
        $iban = Bic::fromString($value);
       
        self::assertSame($value, (string)$iban);
    }
    
    
    public function test_can_normalize_string() : void
    {
        self::assertSame('SOGEFRPPPAR', (string)BIC::fromString('sogefrpppar'));
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_ibans() : void
    {
        $q1 = Bic::fromString('SOGEFRPPPAR');
        $q2 = Bic::fromString('SOGEFRPPPAR');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_ibans() : void
    {
        $q1 = Bic::fromString('SOGEFRPPPAR');
        $q2 = Bic::fromString('SOGEFRPPLYO');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
