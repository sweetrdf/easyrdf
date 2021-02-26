<?php

namespace Test\EasyRdf\Parser;

use EasyRdf\Parser;

/*
 * This file is licensed under the terms of BSD-3 license and
 * is part of the EasyRdf package.
 *
 * (c) Konrad Abicht <hi@inspirito.de>
 * (c) Nicholas J Humfrey
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

class MockParser extends Parser
{
    public function parse($graph, $data, $format, $baseUri)
    {
        parent::checkParseParams($graph, $data, $format, $baseUri);
        // Parsing goes here
        return true;
    }
}
