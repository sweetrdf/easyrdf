<?php

namespace Test\Examples;

use Test\TestCase;

/**
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2009-2014 Nicholas J Humfrey.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 3. The name of the author 'Nicholas J Humfrey" may be used to endorse or
 *    promote products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright  Copyright (c) 2009-2014 Nicholas J Humfrey
 * @license    https://www.opensource.org/licenses/bsd-license.php
 */
class BasicSparqlTest extends TestCase
{
    public function testCountries()
    {
        $output = executeExample('basic_sparql.php');
        $this->assertContains('<title>EasyRdf Basic Sparql Example</title>', $output);
        $this->assertContains('<h1>EasyRdf Basic Sparql Example</h1>', $output);
        $this->assertContains('<h2>List of countries</h2>', $output);
        $this->assertContains(
            '<li><a href="http://dbpedia.org/resource/China">China</a></li>',
            $output
        );
        $this->assertContains(
            '<li><a href="http://dbpedia.org/resource/India">India</a></li>',
            $output
        );
        $this->assertContains(
            '<li><a href="http://dbpedia.org/resource/United_States">United States</a></li>',
            $output
        );
        $this->assertContains(
            '<li><a href="http://dbpedia.org/resource/United_Kingdom">United Kingdom</a></li>',
            $output
        );
        $this->assertContains(
            '<li><a href="http://dbpedia.org/resource/Zimbabwe">Zimbabwe</a></li>',
            $output
        );
        $this->assertRegExp('|Total number of countries: (\d+)|', $output);
    }
}
