<?php

namespace Test\EasyRdf;

use EasyRdf\Exception;
use EasyRdf\Graph;
use EasyRdf\GraphStore;
use EasyRdf\Literal;
use Test\TestCase;

/**
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2013 Nicholas J Humfrey.  All rights reserved.
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
 * @copyright  Copyright (c) 2009-2013 Nicholas J Humfrey
 * @license    http://www.opensource.org/licenses/bsd-license.php
 */
class FusekiTest extends TestCase
{
    /** @var \EasyRdf\GraphStore */
    private $gs;

    /** @var array<int> */
    private $validStatuses = [200, 201, 204];

    protected function setUp()
    {
        parent::__construct();

        $this->gs = new GraphStore('http://localhost:3030/ds/data');

        // check connection
        if (false == $this->isFusekiServerAccessible()) {
            $this->markTestSkipped('Fuseki Server not online');
        }
    }

    protected function isFusekiServerAccessible(): bool
    {
        try {
            $graph1 = new Graph();
            $graph1->set('http://example.com/test', 'rdfs:label', 'Test 0');
            $this->gs->replace($graph1, 'easyrdf-graphstore-test.rdf');
        } catch (Exception $exception) {
            if (false !== strpos($exception->getMessage(), 'Unable to connect')) {
                return false;
            }

            throw $exception;
        }

        return true;
    }

    public function testGraphStoreReplace()
    {
        $graph1 = new Graph();
        $graph1->set('http://example.com/test', 'rdfs:label', 'Test 0');
        $result = $this->gs->replace($graph1, 'easyrdf-graphstore-test.rdf');
        $this->assertTrue(\in_array($result->getStatus(), $this->validStatuses));

        $graph1->set('http://example.com/test', 'rdfs:label', 'Test 1');
        $result = $this->gs->replace($graph1, 'easyrdf-graphstore-test.rdf');
        $this->assertTrue(\in_array($result->getStatus(), $this->validStatuses));

        $graph2 = $this->gs->get('easyrdf-graphstore-test.rdf');
        $this->assertEquals(
            [new Literal('Test 1')],
            $graph2->all('http://example.com/test', 'rdfs:label')
        );
    }

    public function testGraphStoreInsert()
    {
        $graph1 = new Graph();
        $graph1->set('http://example.com/test', 'rdfs:label', 'Test 2');
        $result = $this->gs->insert($graph1, 'easyrdf-graphstore-test2.rdf');
        $this->assertTrue(\in_array($result->getStatus(), $this->validStatuses), $result->getStatus());

        $graph1->set('http://example.com/test', 'rdfs:label', 'Test 3');
        $result = $this->gs->insert($graph1, 'easyrdf-graphstore-test2.rdf');
        $this->assertTrue(\in_array($result->getStatus(), $this->validStatuses), $result->getStatus());

        $graph2 = $this->gs->get('easyrdf-graphstore-test2.rdf');
        $labels = $graph2->all('http://example.com/test', 'rdfs:label');
        sort($labels);
        $this->assertEquals(
            [
                new Literal('Test 2'),
                new Literal('Test 3'),
            ],
            $labels
        );
    }

    public function testGraphStoreDelete()
    {
        $graph1 = new Graph();
        $graph1->set('http://example.com/test', 'rdfs:label', 'Test 4');
        $result = $this->gs->insert($graph1, 'easyrdf-graphstore-test3.rdf');
        $this->assertSame(201, $result->getStatus());

        $graph2 = $this->gs->get('easyrdf-graphstore-test3.rdf');
        $this->assertEquals(1, $graph2->countTriples());

        $result = $this->gs->delete('easyrdf-graphstore-test3.rdf');
        $this->assertSame(204, $result->getStatus());
    }
}
