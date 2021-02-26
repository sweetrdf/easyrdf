<?php

namespace Test\EasyRdf\Parser;

/*
 * This file is licensed under the terms of BSD-3 license and
 * is part of the EasyRdf package.
 *
 * (c) Konrad Abicht <hi@inspirito.de>
 * (c) 2009-2020 Nicholas J Humfrey
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

class MockRdfParser
{
    public function parse($graph, $data, $format, $baseUri)
    {
        $graph->add(
            'http://www.example.com/joe#me',
            'foaf:name',
            'Joseph Bloggs'
        );

        return true;
    }
}
