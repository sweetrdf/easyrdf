<?php

namespace Tests\EasyRdf\Parser;

/*
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2009-2015 Nicholas J Humfrey.  All rights reserved.
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
 * @copyright  Copyright (c) 2009-2015 Nicholas J Humfrey
 * @license    https://www.opensource.org/licenses/bsd-license.php
 */

use EasyRdf\Exception as EasyRdfException;
use EasyRdf\Graph;
use EasyRdf\Parser\Exception;
use EasyRdf\Parser\RdfPhp;
use Test\TestCase;

class RdfPhpTest extends TestCase
{
    /** @var RdfPhp */
    protected $parser;
    /** @var Graph */
    protected $graph;
    protected $rdf_data;

    protected function setUp(): void
    {
        $this->graph = new Graph();
        $this->parser = new RdfPhp();
        $this->rdf_data = [
            'http://example.com/joe' => [
                'http://xmlns.com/foaf/0.1/name' => [
                    [
                        'type' => 'literal',
                        'value' => 'Joseph Bloggs',
                        'lang' => 'en',
                    ],
                ],
            ],
        ];
    }

    public function testParse()
    {
        $count = $this->parser->parse($this->graph, $this->rdf_data, 'php', null);
        $this->assertSame(1, $count);

        $joe = $this->graph->resource('http://example.com/joe');
        $this->assertNotNull($joe);
        $this->assertClass('EasyRdf\Resource', $joe);
        $this->assertSame('http://example.com/joe', $joe->getUri());
        $this->assertStringEquals('', $joe->type());

        $name = $joe->get('foaf:name');
        $this->assertNotNull($name);
        $this->assertClass('EasyRdf\Literal', $name);
        $this->assertSame('Joseph Bloggs', $name->getValue());
        $this->assertSame('en', $name->getLang());
        $this->assertNull($name->getDatatype());
    }

    public function testParseTwice()
    {
        $count = $this->parser->parse($this->graph, $this->rdf_data, 'php', null);
        $this->assertSame(1, $count);
        $count = $this->parser->parse($this->graph, $this->rdf_data, 'php', null);
        $this->assertSame(0, $count);
    }

    public function testParseDuplicateBNodes()
    {
        $foafName = 'http://xmlns.com/foaf/0.1/name';
        $bnodeA = ['_:genid1' => [
            $foafName => [['type' => 'literal', 'value' => 'A']],
        ]];
        $bnodeB = ['_:genid1' => [
            $foafName => [['type' => 'literal', 'value' => 'B']],
        ]];

        $this->parser->parse($this->graph, $bnodeA, 'php', null);
        $this->parser->parse($this->graph, $bnodeB, 'php', null);

        $this->assertStringEquals(
            'A',
            $this->graph->get('_:genid1', 'foaf:name')
        );
        $this->assertStringEquals(
            'B',
            $this->graph->get('_:genid2', 'foaf:name')
        );
    }

    public function testParseUnsupportedFormat()
    {
        $this->expectException(EasyRdfException::class);
        $this->expectExceptionMessage(
            'EasyRdf\Parser\RdfPhp does not support: unsupportedformat'
        );
        $this->parser->parse(
            $this->graph,
            $this->rdf_data,
            'unsupportedformat',
            null
        );
    }

    /**
     * 'foo' is not a valid input for RdfPhp
     */
    public function testParseInvalidInput1()
    {
        $this->expectException(Exception::class);

        $this->parser->parse($this->graph, 'foo', 'php', null);
    }

    /**
     * list of strings is not a valid input for RdfPhp
     */
    public function testParseInvalidInput2()
    {
        $this->expectException(Exception::class);

        $this->parser->parse($this->graph, ['foo', 'bar'], 'php', null);
    }

    /**
     * 1-level dictionary of strings is not a valid input for RdfPhp
     */
    public function testParseInvalidInput3()
    {
        $this->expectException(Exception::class);

        $this->parser->parse($this->graph, ['foo' => 'bar'], 'php', null);
    }

    /**
     * 2-level dictionary of strings is not a valid input for RdfPhp
     */
    public function testParseInvalidInput4()
    {
        $this->expectException(Exception::class);

        $this->parser->parse($this->graph, ['foo' => ['bar' => 'baz']], 'php', null);
    }

    /**
     * 2-level dictionary of incorrect arrays is not a valid input for RdfPhp
     */
    public function testParseInvalidInput5()
    {
        $this->expectException(Exception::class);

        $this->parser->parse($this->graph, ['foo' => ['bar' => ['baz' => 'buzz']]], 'php', null);
    }
}
