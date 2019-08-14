<?php
/**
 * This file is automatically @generated by {@link BuildMetadataPHPFromXml}.
 * Please don't modify it directly.
 */


return array (
  'generalDesc' => 
  array (
    'NationalNumberPattern' => '[2589]\\d{9}',
    'PossibleNumberPattern' => '\\d{7}(?:\\d{3})?',
  ),
  'fixedLine' => 
  array (
    'NationalNumberPattern' => '
          246(?:
              2(?:
                2[78]|
                7[0-4]
              )|
              4(?:
                1[024-6]|
                2\\d|
                3[2-9]
              )|
              5(?:
                20|
                [34]\\d|
                54|
                7[1-3]
              )|
              6(?:
                2\\d|
                38
              )|
              7(?:
                37|
                57
              )|
              9(?:
                1[89]|
                63
              )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{7}(?:\\d{3})?',
    'ExampleNumber' => '2464123456',
  ),
  'mobile' => 
  array (
    'NationalNumberPattern' => '
          246(?:
              2(?:
                [356]\\d|
                4[0-57-9]|
                8[0-79]
              )|
              45\\d|
              8(?:
                [2-5]\\d|
                83
              )
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '2462501234',
  ),
  'tollFree' => 
  array (
    'NationalNumberPattern' => '
          8(?:
            00|
            44|
            55|
            66|
            77|
            88
          )[2-9]\\d{6}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '8002123456',
  ),
  'premiumRate' => 
  array (
    'NationalNumberPattern' => '
          900\\d{7}|
          246976\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '9002123456',
  ),
  'sharedCost' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'personalNumber' => 
  array (
    'NationalNumberPattern' => '
          5(?:
            00|
            33|
            44|
            66|
            77
          )[2-9]\\d{3}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '5002345678',
  ),
  'voip' => 
  array (
    'NationalNumberPattern' => '24631\\d{5}',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '2463101234',
  ),
  'pager' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'uan' => 
  array (
    'NationalNumberPattern' => '
          246(?:
              292|
              41[7-9]|
              43[01]
          )\\d{4}
        ',
    'PossibleNumberPattern' => '\\d{10}',
    'ExampleNumber' => '2464301234',
  ),
  'emergency' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'voicemail' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'shortCode' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'standardRate' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'carrierSpecific' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'noInternationalDialling' => 
  array (
    'NationalNumberPattern' => 'NA',
    'PossibleNumberPattern' => 'NA',
  ),
  'id' => 'BB',
  'countryCode' => 1,
  'internationalPrefix' => '011',
  'nationalPrefix' => '1',
  'nationalPrefixForParsing' => '1',
  'sameMobileAndFixedLinePattern' => false,
  'numberFormat' => 
  array (
  ),
  'intlNumberFormat' => 
  array (
  ),
  'mainCountryForCode' => false,
  'leadingDigits' => '246',
  'leadingZeroPossible' => false,
  'mobileNumberPortableRegion' => false,
);
