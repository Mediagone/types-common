<?php declare(strict_types=1);

namespace Tests\Mediagone\Types\Common\Business;

use InvalidArgumentException;
use JsonSerializable;
use Mediagone\Types\Common\Business\Iban;
use PHPUnit\Framework\TestCase;
use function json_encode;


/**
 * @covers \Mediagone\Types\Common\Business\Iban
 */
final class IbanTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_declares_formats_constant() : void
    {
        self::assertTrue(defined(Iban::class . '::FORMATS'));
    }
    
    
    public function test_declares_max_length_constant() : void
    {
        self::assertTrue(defined(Iban::class . '::MAX_LENGTH'));
    }
    
    
    /**
     * @dataProvider validProvider
     */
    public function test_can_be_created_from_valid_format($value) : void
    {
        // self::assertInstanceOf(Iban::class, Iban::fromString($value));
        self::assertSame(Iban::normalize($value), (string)Iban::fromString($value));
    }
    
    /**
     * @dataProvider validProvider
     */
    public function test_can_tell_value_is_valid(string $value) : void
    {
        self::assertTrue(Iban::isValueValid($value));
    }
    
    public function validProvider()
    {
        yield ['CH9300762011623852957']; // Switzerland without spaces
        yield ['CH93  0076 2011 6238 5295 7']; // Switzerland with multiple spaces
        
        // Country list
        // http://www.rbs.co.uk/corporate/international/g0/guide-to-international-business/regulatory-information/iban/iban-example.ashx
        yield ['AL47 2121 1009 0000 0002 3569 8741']; //Albania
        yield ['AD12 0001 2030 2003 5910 0100']; //Andorra
        yield ['AT61 1904 3002 3457 3201']; //Austria
        yield ['AZ21 NABZ 0000 0000 1370 1000 1944']; //Azerbaijan
        yield ['BH67 BMAG 0000 1299 1234 56']; //Bahrain
        yield ['BE62 5100 0754 7061']; //Belgium
        yield ['BA39 1290 0794 0102 8494']; //Bosnia and Herzegovina
        yield ['BG80 BNBG 9661 1020 3456 78']; //Bulgaria
        yield ['BY 13 NBRB 3600 900000002Z00AB00']; //Belarus
        yield ['BY13 NBRB 3600 900000002Z00AB00']; //Belarus
        yield ['BY22NB23324232T78YR7823HR32U']; //Belarus
        yield ['HR12 1001 0051 8630 0016 0']; //Croatia
        yield ['CY17 0020 0128 0000 0012 0052 7600']; //Cyprus
        yield ['CZ65 0800 0000 1920 0014 5399']; //Czech Republic
        yield ['DK50 0040 0440 1162 43']; //Denmark
        yield ['EE38 2200 2210 2014 5685']; //Estonia
        yield ['FO97 5432 0388 8999 44']; //Faroe Islands
        yield ['FI21 1234 5600 0007 85']; //Finland
        yield ['FR14 2004 1010 0505 0001 3M02 606']; //France
        yield ['GE29 NB00 0000 0101 9049 17']; //Georgia
        yield ['DE89 3704 0044 0532 0130 00']; //Germany
        yield ['GI75 NWBK 0000 0000 7099 453']; //Gibraltar
        yield ['GR16 0110 1250 0000 0001 2300 695']; //Greece
        yield ['GL56 0444 9876 5432 10']; //Greenland
        yield ['HU42 1177 3016 1111 1018 0000 0000']; //Hungary
        yield ['IS14 0159 2600 7654 5510 7303 39']; //Iceland
        yield ['IE29 AIBK 9311 5212 3456 78']; //Ireland
        yield ['IL62 0108 0000 0009 9999 999']; //Israel
        yield ['IT40 S054 2811 1010 0000 0123 456']; //Italy
        yield ['LV80 BANK 0000 4351 9500 1']; //Latvia
        yield ['LB62 0999 0000 0001 0019 0122 9114']; //Lebanon
        yield ['LI21 0881 0000 2324 013A A']; //Liechtenstein
        yield ['LT12 1000 0111 0100 1000']; //Lithuania
        yield ['LU28 0019 4006 4475 0000']; //Luxembourg
        yield ['MK072 5012 0000 0589 84']; //Macedonia
        yield ['MT84 MALT 0110 0001 2345 MTLC AST0 01S']; //Malta
        yield ['MU17 BOMM 0101 1010 3030 0200 000M UR']; //Mauritius
        yield ['MD24 AG00 0225 1000 1310 4168']; //Moldova
        yield ['MC93 2005 2222 1001 1223 3M44 555']; //Monaco
        yield ['ME25 5050 0001 2345 6789 51']; //Montenegro
        yield ['NL39 RABO 0300 0652 64']; //Netherlands
        yield ['NO93 8601 1117 947']; //Norway
        yield ['PK36 SCBL 0000 0011 2345 6702']; //Pakistan
        yield ['PL60 1020 1026 0000 0422 7020 1111']; //Poland
        yield ['PT50 0002 0123 1234 5678 9015 4']; //Portugal
        yield ['RO49 AAAA 1B31 0075 9384 0000']; //Romania
        yield ['SM86 U032 2509 8000 0000 0270 100']; //San Marino
        yield ['SA03 8000 0000 6080 1016 7519']; //Saudi Arabia
        yield ['RS35 2600 0560 1001 6113 79']; //Serbia
        yield ['SK31 1200 0000 1987 4263 7541']; //Slovak Republic
        yield ['SI56 1910 0000 0123 438']; //Slovenia
        yield ['ES80 2310 0001 1800 0001 2345']; //Spain
        yield ['SE35 5000 0000 0549 1000 0003']; //Sweden
        yield ['CH93 0076 2011 6238 5295 7']; //Switzerland
        yield ['TN59 1000 6035 1835 9847 8831']; //Tunisia
        yield ['TR33 0006 1005 1978 6457 8413 26']; //Turkey
        yield ['AE07 0331 2345 6789 0123 456']; //UAE
        yield ['GB12 CPBK 0892 9965 0449 91']; //United Kingdom
        
        // Extended country list
        // http://www.nordea.com/Our+services/International+products+and+services/Cash+Management/IBAN+countries/908462.html
        // https://www.swift.com/sites/default/files/resources/iban_registry.pdf
       yield ['AO06000600000100037131174']; //Angola
       yield ['AZ21NABZ00000000137010001944']; //Azerbaijan
       yield ['BH29BMAG1299123456BH00']; //Bahrain
       yield ['BJ11B00610100400271101192591']; //Benin
       yield ['BR9700360305000010009795493P1']; // Brazil
       yield ['BR1800000000141455123924100C2']; // Brazil
       yield ['VG96VPVG0000012345678901']; //British Virgin Islands
       yield ['BF1030134020015400945000643']; //Burkina Faso
       yield ['BI43201011067444']; //Burundi
       yield ['CM2110003001000500000605306']; //Cameroon
       yield ['CV64000300004547069110176']; //Cape Verde
       yield ['FR7630007000110009970004942']; //Central African Republic
       yield ['CG5230011000202151234567890']; //Congo
       yield ['CR05015202001026284066']; //Costa Rica
       yield ['DO28BAGR00000001212453611324']; //Dominican Republic
       yield ['GT82TRAJ01020000001210029690']; //Guatemala
       yield ['IR580540105180021273113007']; //Iran
       yield ['IL620108000000099999999']; //Israel
       yield ['CI05A00060174100178530011852']; //Ivory Coast
       yield ['JO94CBJO0010000000000131000302']; // Jordan
       yield ['KZ176010251000042993']; //Kazakhstan
       yield ['KW74NBOK0000000000001000372151']; //Kuwait
       yield ['LB30099900000001001925579115']; //Lebanon
       yield ['MG4600005030010101914016056']; //Madagascar
       yield ['ML03D00890170001002120000447']; //Mali
       yield ['MR1300012000010000002037372']; //Mauritania
       yield ['MU17BOMM0101101030300200000MUR']; //Mauritius
       yield ['MZ59000100000011834194157']; //Mozambique
       yield ['PS92PALS000000000400123456702']; //Palestinian Territory
       yield ['QA58DOHB00001234567890ABCDEFG']; //Qatar
       yield ['XK051212012345678906']; //Republic of Kosovo
       yield ['PT50000200000163099310355']; //Sao Tome and Principe
       yield ['SA0380000000608010167519']; //Saudi Arabia
       yield ['SN12K00100152000025690007542']; //Senegal
       yield ['TL380080012345678910157']; //Timor-Leste
       yield ['TN5914207207100707129648']; //Tunisia
       yield ['TR330006100519786457841326']; //Turkey
       yield ['UA213223130000026007233566001']; //Ukraine
       yield ['AE260211000000230064016']; //United Arab Emirates
       yield ['VA59001123000012345678']; //Vatican City State
    }
    
    
    /**
     * @dataProvider invalidProvider
     */
    public function test_cannot_be_created_from_invalid_format(string $value) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Iban::fromString($value);
    }
    
    /**
     * @dataProvider invalidProvider
     */
    public function test_can_tell_value_is_invalid(string $value) : void
    {
        self::assertFalse(Iban::isValueValid($value));
    }
    
    public function invalidProvider()
    {
        yield ['']; //empty
        yield ['AL47 2121 1009 0000 0002 3569 874']; //Albania
        yield ['AD12 0001 2030 2003 5910 010']; //Andorra
        yield ['AT61 1904 3002 3457 320']; //Austria
        yield ['AZ21 NABZ 0000 0000 1370 1000 194']; //Azerbaijan
        yield ['AZ21 N1BZ 0000 0000 1370 1000 1944']; //Azerbaijan
        yield ['BH67 BMAG 0000 1299 1234 5']; //Bahrain
        yield ['BH67 B2AG 0000 1299 1234 56']; //Bahrain
        yield ['BE62 5100 0754 7061 2']; //Belgium
        yield ['BA39 1290 0794 0102 8494 4']; //Bosnia and Herzegovina
        yield ['BG80 BNBG 9661 1020 3456 7']; //Bulgaria
        yield ['BG80 B2BG 9661 1020 3456 78']; //Bulgaria
        yield ['BY 13 NBRB 3600 900000002Z00AB001']; //Belarus
        yield ['BY 13 NBRB 3600 900000002Z00AB0']; //Belarus
        yield ['BYRO NBRB 3600 900000002Z00AB0']; //Belarus
        yield ['BY 13 3600 NBRB 900000002Z00AB05']; //Belarus
        yield ['HR12 1001 0051 8630 0016 01']; //Croatia
        yield ['CY17 0020 0128 0000 0012 0052 7600 1']; //Cyprus
        yield ['CZ65 0800 0000 1920 0014 5399 1']; //Czech Republic
        yield ['DK50 0040 0440 1162 431']; //Denmark
        yield ['EE38 2200 2210 2014 5685 1']; //Estonia
        yield ['FO97 5432 0388 8999 441']; //Faroe Islands
        yield ['FI21 1234 5600 0007 851']; //Finland
        yield ['FR14 2004 1010 0505 0001 3M02 6061']; //France
        yield ['GE29 NB00 0000 0101 9049 171']; //Georgia
        yield ['DE89 3704 0044 0532 0130 001']; //Germany
        yield ['GI75 NWBK 0000 0000 7099 4531']; //Gibraltar
        yield ['GR16 0110 1250 0000 0001 2300 6951']; //Greece
        yield ['GL56 0444 9876 5432 101']; //Greenland
        yield ['HU42 1177 3016 1111 1018 0000 0000 1']; //Hungary
        yield ['IS14 0159 2600 7654 5510 7303 391']; //Iceland
        yield ['IE29 AIBK 9311 5212 3456 781']; //Ireland
        yield ['IL62 0108 0000 0009 9999 9991']; //Israel
        yield ['IT40 S054 2811 1010 0000 0123 4561']; //Italy
        yield ['LV80 BANK 0000 4351 9500 11']; //Latvia
        yield ['LB62 0999 0000 0001 0019 0122 9114 1']; //Lebanon
        yield ['LI21 0881 0000 2324 013A A1']; //Liechtenstein
        yield ['LT12 1000 0111 0100 1000 1']; //Lithuania
        yield ['LU28 0019 4006 4475 0000 1']; //Luxembourg
        yield ['MK072 5012 0000 0589 84 1']; //Macedonia
        yield ['MT84 MALT 0110 0001 2345 MTLC AST0 01SA']; //Malta
        yield ['MU17 BOMM 0101 1010 3030 0200 000M URA']; //Mauritius
        yield ['MD24 AG00 0225 1000 1310 4168 1']; //Moldova
        yield ['MC93 2005 2222 1001 1223 3M44 5551']; //Monaco
        yield ['ME25 5050 0001 2345 6789 511']; //Montenegro
        yield ['NL39 RABO 0300 0652 641']; //Netherlands
        yield ['NO93 8601 1117 9471']; //Norway
        yield ['PK36 SCBL 0000 0011 2345 6702 1']; //Pakistan
        yield ['PL60 1020 1026 0000 0422 7020 1111 1']; //Poland
        yield ['PT50 0002 0123 1234 5678 9015 41']; //Portugal
        yield ['RO49 AAAA 1B31 0075 9384 0000 1']; //Romania
        yield ['SM86 U032 2509 8000 0000 0270 1001']; //San Marino
        yield ['SA03 8000 0000 6080 1016 7519 1']; //Saudi Arabia
        yield ['RS35 2600 0560 1001 6113 791']; //Serbia
        yield ['SK31 1200 0000 1987 4263 7541 1']; //Slovak Republic
        yield ['SI56 1910 0000 0123 4381']; //Slovenia
        yield ['ES80 2310 0001 1800 0001 2345 1']; //Spain
        yield ['SE35 5000 0000 0549 1000 0003 1']; //Sweden
        yield ['CH93 0076 2011 6238 5295 71']; //Switzerland
        yield ['TN59 1000 6035 1835 9847 8831 1']; //Tunisia
        yield ['TR33 0006 1005 1978 6457 8413 261']; //Turkey
        yield ['AE07 0331 2345 6789 0123 4561']; //UAE
        yield ['GB12 CPBK 0892 9965 0449 911']; //United Kingdom

        //Extended country list
        yield ['AO060006000001000371311741']; //Angola
        yield ['AZ21NABZ000000001370100019441']; //Azerbaijan
        yield ['BH29BMAG1299123456BH001']; //Bahrain
        yield ['BJ11B006101004002711011925911']; //Benin
        yield ['BR9700360305000010009795493P11']; // Brazil
        yield ['BR1800000000141455123924100C21']; // Brazil
        yield ['VG96VPVG00000123456789011']; //British Virgin Islands
        yield ['BF10301340200154009450006431']; //Burkina Faso
        yield ['BI432010110674441']; //Burundi
        yield ['CM21100030010005000006053061']; //Cameroon
        yield ['CV640003000045470691101761']; //Cape Verde
        yield ['FR76300070001100099700049421']; //Central African Republic
        yield ['CG52300110002021512345678901']; //Congo
        yield ['CR05152020010262840661']; //Costa Rica
        yield ['CR0515202001026284066']; //Costa Rica
        yield ['DO28BAGR000000012124536113241']; //Dominican Republic
        yield ['GT82TRAJ010200000012100296901']; //Guatemala
        yield ['IR5805401051800212731130071']; //Iran
        yield ['IL6201080000000999999991']; //Israel
        yield ['CI05A000601741001785300118521']; //Ivory Coast
        yield ['JO94CBJO00100000000001310003021']; // Jordan
        yield ['KZ1760102510000429931']; //Kazakhstan
        yield ['KW74NBOK00000000000010003721511']; //Kuwait
        yield ['LB300999000000010019255791151']; //Lebanon
        yield ['MG46000050300101019140160561']; //Madagascar
        yield ['ML03D008901700010021200004471']; //Mali
        yield ['MR13000120000100000020373721']; //Mauritania
        yield ['MU17BOMM0101101030300200000MUR1']; //Mauritius
        yield ['MZ590001000000118341941571']; //Mozambique
        yield ['PS92PALS0000000004001234567021']; //Palestinian Territory
        yield ['QA58DOHB00001234567890ABCDEFG1']; //Qatar
        yield ['XK0512120123456789061']; //Republic of Kosovo
        yield ['PT500002000001630993103551']; //Sao Tome and Principe
        yield ['SA03800000006080101675191']; //Saudi Arabia
        yield ['SN12K001001520000256900075421']; //Senegal
        yield ['TL3800800123456789101571']; //Timor-Leste
        yield ['TN59142072071007071296481']; //Tunisia
        yield ['TR3300061005197864578413261']; //Turkey
        yield ['UA21AAAA1300000260072335660012']; //Ukraine
        yield ['AE2602110000002300640161']; //United Arab Emirates
        yield ['VA590011230000123456781']; //Vatican City State
    }
    
    
    /**
     * @dataProvider invalidChecksumProvider
     */
    public function test_cannot_be_created_with_invalid_checksum(string $value) : void
    {
        $this->expectException(InvalidArgumentException::class);
        Iban::fromString($value);
    }
    
    public function invalidChecksumProvider()
    {
        yield ['AL47 2121 1009 0000 0002 3569 8742']; //Albania
        yield ['AD12 0001 2030 2003 5910 0101']; //Andorra
        yield ['AT61 1904 3002 3457 3202']; //Austria
        yield ['AZ21 NABZ 0000 0000 1370 1000 1945']; //Azerbaijan
        yield ['BH67 BMAG 0000 1299 1234 57']; //Bahrain
        yield ['BE62 5100 0754 7062']; //Belgium
        yield ['BA39 1290 0794 0102 8495']; //Bosnia and Herzegovina
        yield ['BG80 BNBG 9661 1020 3456 79']; //Bulgaria
        yield ['BY90 NBRB 3600 900000002Z00AB00']; //Belarus
        yield ['HR12 1001 0051 8630 0016 1']; //Croatia
        yield ['CY17 0020 0128 0000 0012 0052 7601']; //Cyprus
        yield ['CZ65 0800 0000 1920 0014 5398']; //Czech Republic
        yield ['DK50 0040 0440 1162 44']; //Denmark
        yield ['EE38 2200 2210 2014 5684']; //Estonia
        yield ['FO97 5432 0388 8999 43']; //Faroe Islands
        yield ['FI21 1234 5600 0007 84']; //Finland
        yield ['FR14 2004 1010 0505 0001 3M02 605']; //France
        yield ['GE29 NB00 0000 0101 9049 16']; //Georgia
        yield ['DE89 3704 0044 0532 0130 01']; //Germany
        yield ['GI75 NWBK 0000 0000 7099 452']; //Gibraltar
        yield ['GR16 0110 1250 0000 0001 2300 694']; //Greece
        yield ['GL56 0444 9876 5432 11']; //Greenland
        yield ['HU42 1177 3016 1111 1018 0000 0001']; //Hungary
        yield ['IS14 0159 2600 7654 5510 7303 38']; //Iceland
        yield ['IE29 AIBK 9311 5212 3456 79']; //Ireland
        yield ['IL62 0108 0000 0009 9999 998']; //Israel
        yield ['IT40 S054 2811 1010 0000 0123 457']; //Italy
        yield ['LV80 BANK 0000 4351 9500 2']; //Latvia
        yield ['LB62 0999 0000 0001 0019 0122 9115']; //Lebanon
        yield ['LI21 0881 0000 2324 013A B']; //Liechtenstein
        yield ['LT12 1000 0111 0100 1001']; //Lithuania
        yield ['LU28 0019 4006 4475 0001']; //Luxembourg
        yield ['MK072 5012 0000 0589 85']; //Macedonia
        yield ['MT84 MALT 0110 0001 2345 MTLC AST0 01T']; //Malta
        yield ['MU17 BOMM 0101 1010 3030 0200 000M UP']; //Mauritius
        yield ['MD24 AG00 0225 1000 1310 4169']; //Moldova
        yield ['MC93 2005 2222 1001 1223 3M44 554']; //Monaco
        yield ['ME25 5050 0001 2345 6789 52']; //Montenegro
        yield ['NL39 RABO 0300 0652 65']; //Netherlands
        yield ['NO93 8601 1117 948']; //Norway
        yield ['PK36 SCBL 0000 0011 2345 6703']; //Pakistan
        yield ['PL60 1020 1026 0000 0422 7020 1112']; //Poland
        yield ['PT50 0002 0123 1234 5678 9015 5']; //Portugal
        yield ['RO49 AAAA 1B31 0075 9384 0001']; //Romania
        yield ['SM86 U032 2509 8000 0000 0270 101']; //San Marino
        yield ['SA03 8000 0000 6080 1016 7518']; //Saudi Arabia
        yield ['RS35 2600 0560 1001 6113 78']; //Serbia
        yield ['SK31 1200 0000 1987 4263 7542']; //Slovak Republic
        yield ['SI56 1910 0000 0123 439']; //Slovenia
        yield ['ES80 2310 0001 1800 0001 2346']; //Spain
        yield ['SE35 5000 0000 0549 1000 0004']; //Sweden
        yield ['CH93 0076 2011 6238 5295 8']; //Switzerland
        yield ['TN59 1000 6035 1835 9847 8832']; //Tunisia
        yield ['TR33 0006 1005 1978 6457 8413 27']; //Turkey
        yield ['AE07 0331 2345 6789 0123 457']; //UAE
        yield ['GB12 CPBK 0892 9965 0449 92']; //United Kingdom

        //Extended country list
        yield ['AO06000600000100037131175']; //Angola
        yield ['AZ21NABZ00000000137010001945']; //Azerbaijan
        yield ['BH29BMAG1299123456BH01']; //Bahrain
        yield ['BJ11B00610100400271101192592']; //Benin
        yield ['BR9700360305000010009795493P2']; // Brazil
        yield ['BR1800000000141455123924100C3']; // Brazil
        yield ['VG96VPVG0000012345678902']; //British Virgin Islands
        yield ['BF1030134020015400945000644']; //Burkina Faso
        yield ['BI43201011067445']; //Burundi
        yield ['CM2110003001000500000605307']; //Cameroon
        yield ['CV64000300004547069110177']; //Cape Verde
        yield ['FR7630007000110009970004943']; //Central African Republic
        yield ['CG5230011000202151234567891']; //Congo
        yield ['CR96042332432534543564']; //Costa Rica
        yield ['DO28BAGR00000001212453611325']; //Dominican Republic
        yield ['GT82TRAJ01020000001210029691']; //Guatemala
        yield ['IR580540105180021273113008']; //Iran
        yield ['IL620108000000099999998']; //Israel
        yield ['CI05A00060174100178530011853']; //Ivory Coast
        yield ['JO94CBJO0010000000000131000303']; // Jordan
        yield ['KZ176010251000042994']; //Kazakhstan
        yield ['KW74NBOK0000000000001000372152']; //Kuwait
        yield ['LB30099900000001001925579116']; //Lebanon
        yield ['MG4600005030010101914016057']; //Madagascar
        yield ['ML03D00890170001002120000448']; //Mali
        yield ['MR1300012000010000002037373']; //Mauritania
        yield ['MU17BOMM0101101030300200000MUP']; //Mauritius
        yield ['MZ59000100000011834194158']; //Mozambique
        yield ['PS92PALS000000000400123456703']; //Palestinian Territory
        yield ['QA58DOHB00001234567890ABCDEFH']; //Qatar
        yield ['XK051212012345678907']; //Republic of Kosovo
        yield ['PT50000200000163099310356']; //Sao Tome and Principe
        yield ['SA0380000000608010167518']; //Saudi Arabia
        yield ['SN12K00100152000025690007543']; //Senegal
        yield ['TL380080012345678910158']; //Timor-Leste
        yield ['TN5914207207100707129649']; //Tunisia
        yield ['TR330006100519786457841327']; //Turkey
        yield ['UA213223130000026007233566002']; //Ukraine
        yield ['AE260211000000230064017']; //United Arab Emirates
        yield ['VA59001123000012345671']; //Vatican City State
    }
    
    
    
    //========================================================================================================
    // Conversion tests
    //========================================================================================================
    
    public function test_can_be_encoded_to_json() : void
    {
        $value = 'CH9300762011623852957';
        $iban = Iban::fromString($value);
        
        self::assertInstanceOf(JsonSerializable::class, $iban);
        self::assertJson(json_encode($iban->jsonSerialize()));
    }
    
    
    public function test_can_be_cast_to_string() : void
    {
        $value = 'CH9300762011623852957';
        $iban = Iban::fromString($value);
        
        self::assertSame($value, (string)$iban);
    }
    
    
    public function test_can_normalize_string() : void
    {
        self::assertSame('CH9300762011623852957', (string)Iban::fromString('CH93 0076 2011 6238 5295 7'));
    }
    
    
    public function test_can_get_country() : void
    {
        $value = 'CH9300762011623852957';
        $iban = Iban::fromString($value);
        
        self::assertSame('CH', $iban->getCountry()->getAlpha2());
    }
    
    
    public function test_can_get_bban() : void
    {
        $value = 'CH9300762011623852957';
        $iban = Iban::fromString($value);
        
        self::assertSame('00762011623852957', $iban->getBasicBankAccountNumber());
    }
    
    
    public function test_can_get_masked() : void
    {
        $value = 'CH9300762011623852957';
        $iban = Iban::fromString($value);
        
        self::assertSame('CH930_____________957', $iban->toMasked());
    }
    
    
    
    //========================================================================================================
    // Operations tests
    //========================================================================================================
    
    public function test_equality_between_ibans() : void
    {
        $q1 = Iban::fromString('CH9300762011623852957');
        $q2 = Iban::fromString('CH93 0076 2011 6238 5295 7');
        
        self::assertNotSame($q1, $q2);
        self::assertTrue($q1->equals($q2));
        self::assertTrue($q2->equals($q1));
    }
    
    public function test_inequality_between_ibans() : void
    {
        $q1 = Iban::fromString('CH9300762011623852957');
        $q2 = Iban::fromString('AL47212110090000000235698741');
        
        self::assertNotSame($q1, $q2);
        self::assertFalse($q1->equals($q2));
        self::assertFalse($q2->equals($q1));
    }
    
    
    
}
