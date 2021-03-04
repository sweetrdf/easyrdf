<?php

namespace Test\EasyRdf\Serialiser;

use EasyRdf\Graph;
use EasyRdf\Serialiser;

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

class MockSerialiser extends Serialiser
{
    public function serialise(Graph $graph, $format, array $options = [])
    {
        parent::checkSerialiseParams($format);
        // Serialising goes here
        return '';
    }
}
