<?php

namespace Tests\EasyRdf\Serialiser;

/*
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2021 Konrad Abicht <hi@inspirito.de>
 * Copyright (c) 2009-2020 Nicholas J Humfrey.  All rights reserved.
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
 * @package    EasyRdf
 * @copyright  Copyright (c) 2021 Konrad Abicht <hi@inspirito.de>
 * @copyright  Copyright (c) 2009-2020 Nicholas J Humfrey
 * @license    https://www.opensource.org/licenses/bsd-license.php
 */

use EasyRdf\Graph;
use EasyRdf\Serialiser\Rapper;
use Test\TestCase;

class RapperTest extends TestCase
{
    /** @var Graph */
    private $graph;
    /** @var Rapper */
    private $serialiser;

    protected function setUp(): void
    {
        exec('which rapper 2>&1', $output, $retval);
        if (0 == $retval) {
            $this->graph = new Graph();
            $this->serialiser = new Rapper();
            parent::setUp();
        } else {
            $this->markTestSkipped(
                'The rapper command is not available on this system.'
            );
        }
    }

    public function testRapperNotFound()
    {
        $this->expectException('EasyRdf\Exception');
        $this->expectExceptionMessage(
            "Failed to execute the command 'random_command_that_doesnt_exist'"
        );

        new Rapper('random_command_that_doesnt_exist');
    }

    public function testSerialiseRdfXml()
    {
        $joe = $this->graph->resource('http://www.example.com/joe#me');
        $joe->set('foaf:name', 'Joe Bloggs');
        $project = $this->graph->newBNode();
        $project->add('foaf:name', 'Project Name');
        $joe->add('foaf:project', $project);

        $rdfxml = $this->serialiser->serialise($this->graph, 'rdfxml');
        $this->assertNotNull($rdfxml);
        $this->assertStringContainsString(
            '<rdf:Description rdf:about="http://www.example.com/joe#me">',
            $rdfxml
        );
        $this->assertStringContainsString(':name>Project Name<', $rdfxml);
    }

    public function testSerialiseUnsupportedFormat()
    {
        $this->expectException('EasyRdf\Exception');
        $this->expectExceptionMessage('Error while executing command rapper');

        $this->serialiser->serialise(
            $this->graph,
            'unsupportedformat'
        );
    }
}
