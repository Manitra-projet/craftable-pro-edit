<?php

namespace CustomPackages\CustomApp\Translations\Scanners\Internal;

use CustomPackages\CustomApp\Translations\Scanners\Contracts\ScannerInterface;

class PhpScanner extends BaseScanner implements ScannerInterface
{
    public function setUpScanPatterns()
    {
        $defaultFunctions = [
            '__',
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice',
        ];

        $customFunctions = [
            '___',
            '___ch',
        ];

        // See https://regex101.com/r/2EfItR/2
        $this
            ->addScanPattern('[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(' . implode('|', $customFunctions) . ')' .// Must start with one of the functions
            '\(' .// Match opening parentheses
            '[\"]' .// Match "
            '(' .// Start a new group to match:
            '[^"]+' . //Can have everything except "
            ')' .// Close group
            '[\"]' .// Closing quote
            '(,\s*)' . // Second parameter
            '[\"]' . // Match "
            '([^"]+)' . //Can have everything except "
            '[\"]')
            ->addScanPattern("[^\w](?<!->)(" . implode('|', $customFunctions) . ")\([']([^']+)['](,\s*)[\']([^']+)[\']") // same as above but with ''
            ->addScanPattern('[^\w](?<!->)(' . implode('|', $customFunctions) . ')\(["]([^"]+)["]' . "(,\s*)[\']([^']+)[\']") // same as above but with combination of '' and ""
            ->addScanPattern("[^\w](?<!->)(" . implode('|', $customFunctions) . ")\([']([^']+)[']" . '(,\s*)[\"]([^"]+)[\"]')  // same as above but with combination of "" and ''
            ->addScanPattern(
                '[^\w]' . // Must not start with any alphanum or _
                '(?<!->)' . // Must not start with ->
                '(' . implode('|', $defaultFunctions) . ')' .// Must start with one of the functions
                '\(' .// Match opening parentheses

                '[\"]' .// Match "
                '(' .// Start a new group to match:
                '[^"]+' . //Can have everything except "
//            '(?:[^"]|\\")+' . //Can have everything except " or can have escaped " like \", however it is not working as expected
                ')' .// Close group
                '[\"]' // Closing quote
//                '[\)]'   Close parentheses or new parameter
                ,
                2,
                null
            )->addScanPattern(            // See https://regex101.com/r/VaPQ7A/2
                '[^\w]' . // Must not start with any alphanum or _
                '(?<!->)' . // Must not start with ->
                '(' . implode('|', $defaultFunctions) . ')' .// Must start with one of the functions
                '\(' .// Match opening parentheses

                '[\']' .// Match '
                '(' .// Start a new group to match:
                "[^']+" . //Can have everything except '
//            "(?:[^']|\\')+" . //Can have everything except 'or can have escaped ' like \', however it is not working as expected
                ')' .// Close group
                '[\']' // Closing quote

//                '[\)]'   Close parentheses or new parameter);
                ,
                2,
                null
            );
    }
}
