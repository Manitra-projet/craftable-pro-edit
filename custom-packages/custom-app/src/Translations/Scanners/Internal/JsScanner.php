<?php

namespace CustomPackages\CustomApp\Translations\Scanners\Internal;

use CustomPackages\CustomApp\Translations\Scanners\Contracts\ScannerInterface;

class JsScanner extends BaseScanner implements ScannerInterface
{
    public function setUpScanPatterns()
    {
        $functions = '\$tChoice|\$t|trans|wTrans|transChoice|wTransChoice';

        $this
            ->addScanPattern('[^\w]' . // Must not start with any alphanum
                '(?:' . $functions . ')' . // Must start with one of the functions
                '\(\s*' . // Match opening parentheses
                '["]' . // Match "
                '(' . // Start a new group to match:
                '[^"]+' . //Can have everything except "
                ')' . // Close group
                '["]' . // Closing quote
                '(?:' . // Start of optional second parameter
                '(?:,\s*)' . // Second parameter
                '["]' . // Match "
                '([^"]+)' . //Can have everything except "
                '["]' .
                ')?', 2, 1) // End of optional second parameter
            ->addScanPattern("[^\w](?:$functions)\(\s*[']([^']+)['](?:(?:,\s*)[\']([^']+)[\'])?", 2, 1); // same as above but with ''
    }
}
