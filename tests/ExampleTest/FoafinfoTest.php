<?php

namespace Tests\ExampleTest;

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
class FoafinfoTest extends TestCase
{
    public function testNoParams()
    {
        $output = executeExample('foafinfo.php');
        $this->assertStringContainsString('<title>EasyRdf FOAF Info Example</title>', $output);
        $this->assertStringContainsString('<h1>EasyRdf FOAF Info Example</h1>', $output);
        $this->assertStringContainsString(
            '<input type="text" name="uri" id="uri" value="http://njh.me/foaf.rdf" size="50" />',
            $output
        );
    }

    public function testNjh()
    {
        $output = executeExample(
            'foafinfo.php',
            ['uri' => 'http://njh.me/foaf.rdf']
        );

        $this->assertStringContainsString('<title>EasyRdf FOAF Info Example</title>', $output);
        $this->assertStringContainsString('<h1>EasyRdf FOAF Info Example</h1>', $output);
        $this->assertStringContainsString('<dt>Name:</dt><dd>Nicholas J Humfrey</dd>', $output);
        $this->assertStringContainsString(
            '<dt>Homepage:</dt><dd><a href="http://www.aelius.com/njh/">http://www.aelius.com/njh/</a></dd>',
            $output
        );

        $this->assertStringContainsString('<h2>Known Persons</h2>', $output);
        $this->assertStringContainsString('>Patrick Sinclair</a></li>', $output);
        $this->assertStringContainsString('>Yves Raimond</a></li>', $output);

        $this->assertStringContainsString('<h2>Interests</h2>', $output);
        $this->assertStringContainsString('>RDF</a></li>', $output);
    }
}
