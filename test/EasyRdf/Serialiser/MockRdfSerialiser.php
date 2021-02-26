<?php

namespace Test\EasyRdf\Serialiser;

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

class MockRdfSerialiser
{
    public function serialise($graph, $format = null)
    {
        return '<rdf></rdf>';
    }
}
