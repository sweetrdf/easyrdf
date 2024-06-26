<?php

namespace Tests\EasyRdf\Sparql;

/*
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
 * @copyright  Copyright (c) 2021 Konrad Abicht <hi@inspirito.de>
 * @copyright  Copyright (c) 2009-2014 Nicholas J Humfrey
 * @license    https://www.opensource.org/licenses/bsd-license.php
 */

use EasyRdf\Literal;
use EasyRdf\Resource;
use EasyRdf\Sparql\Result;
use Test\TestCase;

class ResultTest extends TestCase
{
    public function testSelectAllXml()
    {
        $result = new Result(
            readFixture('sparql_select_all.xml'),
            'application/sparql-results+xml'
        );

        $this->assertSame(3, $result->numFields());
        $this->assertSame(['s', 'p', 'o'], $result->getFields());

        $this->assertCount(14, $result);
        $this->assertSame(14, $result->numRows());
        $this->assertSame(14, \count($result));
        $this->assertEquals(
            new Resource('_:genid1'),
            $result[0]->s
        );
        $this->assertEquals(
            new Resource('http://xmlns.com/foaf/0.1/name'),
            $result[0]->p
        );
        $this->assertEquals(
            new Literal("Joe's Current Project"),
            $result[0]->o
        );
    }

    public function testSelectAllXmlWithWhitespace()
    {
        $result = new Result(
            readFixture('sparql_select_all_ws.xml'),
            'application/sparql-results+xml'
        );

        $this->assertSame(3, $result->numFields());
        $this->assertSame(['s', 'p', 'o'], $result->getFields());

        $this->assertCount(14, $result);
        $this->assertSame(14, $result->numRows());
        $this->assertSame(14, \count($result));
        $this->assertEquals(
            new Resource('_:genid1'),
            $result[0]->s
        );
        $this->assertEquals(
            new Resource('http://xmlns.com/foaf/0.1/name'),
            $result[0]->p
        );
        $this->assertEquals(
            new Literal("Joe's Current Project"),
            $result[0]->o
        );
    }

    public function testSelectAllJson()
    {
        $result = new Result(
            readFixture('sparql_select_all.json'),
            'application/sparql-results+json'
        );

        $this->assertSame(3, $result->numFields());
        $this->assertSame(['s', 'p', 'o'], $result->getFields());

        $this->assertCount(14, $result);
        $this->assertSame(14, $result->numRows());
        $this->assertSame(14, \count($result));
        $this->assertEquals(
            new Resource('_:genid1'),
            $result[0]->s
        );
        $this->assertEquals(
            new Resource('http://xmlns.com/foaf/0.1/name'),
            $result[0]->p
        );
        $this->assertEquals(
            new Literal("Joe's Current Project"),
            $result[0]->o
        );
    }

    public function testSelectEmptyXml()
    {
        $result = new Result(
            readFixture('sparql_select_empty.xml'),
            'application/sparql-results+xml'
        );

        $this->assertSame(3, $result->numFields());
        $this->assertSame(['s', 'p', 'o'], $result->getFields());
        $this->assertCount(0, $result);
    }

    public function testSelectEmptyJson()
    {
        $result = new Result(
            readFixture('sparql_select_empty.json'),
            'application/sparql-results+json'
        );

        $this->assertSame(3, $result->numFields());
        $this->assertSame(['s', 'p', 'o'], $result->getFields());
        $this->assertCount(0, $result);
    }

    public function testSelectLangLiteralXml()
    {
        $result = new Result(
            readFixture('sparql_select_lang.xml'),
            'application/sparql-results+xml'
        );

        // 1st: Example using xml:lang="en"
        $first = $result[0];
        $this->assertSame('London', $first->label->getValue());
        $this->assertSame('en', $first->label->getLang());
        $this->assertNull($first->label->getDatatype());

        // 2nd: Example using xml:lang="es"
        $second = $result[1];
        $this->assertSame('Londres', $second->label->getValue());
        $this->assertSame('es', $second->label->getLang());
        $this->assertNull($second->label->getDatatype());

        // 3rd: no lang
        $third = $result[2];
        $this->assertSame('London', $third->label->getValue());
        $this->assertNull($third->label->getLang());
        $this->assertNull($third->label->getDatatype());
    }

    public function testSelectLangLiteralJson()
    {
        $result = new Result(
            readFixture('sparql_select_lang.json'),
            'application/sparql-results+json'
        );

        // 1st: Example using xml:lang="en"
        $first = $result[0];
        $this->assertSame('London', $first->label->getValue());
        $this->assertSame('en', $first->label->getLang());
        $this->assertNull($first->label->getDatatype());

        // 2nd: Example using lang="es"
        $second = $result[1];
        $this->assertSame('Londres', $second->label->getValue());
        $this->assertSame('es', $second->label->getLang());
        $this->assertNull($second->label->getDatatype());

        // 3rd: no lang
        $third = $result[2];
        $this->assertSame('London', $third->label->getValue());
        $this->assertNull($third->label->getLang());
        $this->assertNull($third->label->getDatatype());
    }

    public function testSelectTypedLiteralJson()
    {
        $result = new Result(
            readFixture('sparql_typed_literal.json'),
            'application/sparql-results+json'
        );

        $first = $result[0];
        $this->assertStringEquals('http://www.bbc.co.uk/programmes/b0074dlv#programme', $first->episode);
        $this->assertStringEquals(1, $first->pos);
        $this->assertStringEquals('Rose', $first->label);
    }

    public function testSelectTypedLiteralXml()
    {
        $result = new Result(
            readFixture('sparql_typed_literal.xml'),
            'application/sparql-results+xml'
        );

        $first = $result[0];
        $this->assertStringEquals('http://www.bbc.co.uk/programmes/b0074dlv#programme', $first->episode);
        $this->assertStringEquals(1, $first->pos);
        $this->assertStringEquals('Rose', $first->label);
    }

    public function testSelectHugeXml()
    {
        $huge = "<sparql xmlns=\"http://www.w3.org/2005/sparql-results#\">\n";
        $huge .= "<head><variable name=\"s\"/><variable name=\"p\"/><variable name=\"o\"/></head>\n";
        $huge .= "<results>\n";
        for ($i = 0; $i < 50000; ++$i) {
            $huge .= "<result>\n";
            $huge .= "<binding name=\"s\"><uri>http://www.example.com/person/$i</uri></binding>\n";
            $huge .= "<binding name=\"p\"><uri>http://www.w3.org/1999/02/22-rdf-syntax-ns#type</uri></binding>\n";
            $huge .= "<binding name=\"o\"><uri>http://xmlns.com/foaf/0.1/Person</uri></binding>\n";
            $huge .= "</result>\n";
        }
        $huge .= "</results>\n</sparql>\n";

        // Check it is more than 10Mb
        $this->assertGreaterThan(10485760, \strlen($huge));

        $result = new Result($huge, 'application/sparql-results+xml');

        $this->assertCount(50000, $result);
        $this->assertSame(50000, $result->numRows());
    }

    public function testAskTrueJson()
    {
        $result = new Result(
            readFixture('sparql_ask_true.json'),
            'application/sparql-results+json'
        );

        $this->assertSame('boolean', $result->getType());
        $this->assertFalse($result->isFalse());
        $this->assertTrue($result->isTrue());
        $this->assertTrue($result->getBoolean());
        $this->assertStringEquals('true', $result);

        $this->assertSame(0, $result->numFields());
        $this->assertSame(0, $result->numRows());
        $this->assertSame(0, \count($result));
    }

    public function testAskFalseJson()
    {
        $result = new Result(
            readFixture('sparql_ask_false.json'),
            'application/sparql-results+json'
        );

        $this->assertSame('boolean', $result->getType());
        $this->assertTrue($result->isFalse());
        $this->assertFalse($result->isTrue());
        $this->assertFalse($result->getBoolean());
        $this->assertStringEquals('false', $result);

        $this->assertSame(0, $result->numFields());
        $this->assertSame(0, $result->numRows());
        $this->assertSame(0, \count($result));
    }

    public function testAskTrueXml()
    {
        $result = new Result(
            readFixture('sparql_ask_true.xml'),
            'application/sparql-results+xml'
        );
        $this->assertSame('boolean', $result->getType());
        $this->assertFalse($result->isFalse());
        $this->assertTrue($result->isTrue());
        $this->assertTrue($result->getBoolean());
        $this->assertStringEquals('true', $result);

        $this->assertSame(0, $result->numFields());
        $this->assertSame(0, $result->numRows());
        $this->assertSame(0, \count($result));
    }

    public function testAskFalseXml()
    {
        $result = new Result(
            readFixture('sparql_ask_false.xml'),
            'application/sparql-results+xml'
        );
        $this->assertSame('boolean', $result->getType());
        $this->assertTrue($result->isFalse());
        $this->assertFalse($result->isTrue());
        $this->assertFalse($result->getBoolean());
        $this->assertStringEquals('false', $result);

        $this->assertSame(0, $result->numFields());
        $this->assertSame(0, $result->numRows());
        $this->assertSame(0, \count($result));
    }

    public function testInvalidXml()
    {
        $this->expectExceptionMessage('Failed to parse SPARQL XML Query Results format');
        new Result(
            readFixture('sparql_invalid.xml'),
            'application/sparql-results+xml'
        );
    }

    public function testIncorrectSparqlNamespaceXml()
    {
        $this->expectExceptionMessage(
            'Root node namespace is not http://www.w3.org/2005/sparql-results#'
        );
        new Result(
            readFixture('sparql_wrong_ns.xml'),
            'application/sparql-results+xml'
        );
    }

    public function testNotSparqlXml()
    {
        $this->expectExceptionMessage('Root node in XML Query Results format is not <sparql>');
        new Result(
            readFixture('not_sparql_result.xml'),
            'application/sparql-results+xml'
        );
    }

    public function testInvalidJson()
    {
        $this->expectExceptionMessage('Failed to parse SPARQL JSON Query Results format');
        new Result(
            readFixture('sparql_invalid.json'),
            'application/sparql-results+json'
        );
    }

    public function testInvalidJsonTerm()
    {
        $this->expectExceptionMessage(
            'Failed to parse SPARQL Query Results format, unknown term type: newtype'
        );
        new Result(
            readFixture('sparql_invalid_term.json'),
            'application/sparql-results+json'
        );
    }

    public function testDumpSelectAllHtml()
    {
        $result = new Result(
            readFixture('sparql_select_all.xml'),
            'application/sparql-results+xml'
        );

        $html = $result->dump('html');
        $this->assertStringContainsString("<table class='sparql-results'", $html);
        $this->assertStringContainsString('>?s</th>', $html);
        $this->assertStringContainsString('>?p</th>', $html);
        $this->assertStringContainsString('>?o</th></tr>', $html);

        $this->assertStringContainsString('>http://www.example.com/joe#me</a></td>', $html);
        $this->assertStringContainsString('>foaf:name</a></td>', $html);
        $this->assertStringContainsString('>&quot;Joe Bloggs&quot;@en</span></td>', $html);
        $this->assertStringContainsString('</table>', $html);
    }

    public function testDumpSelectAllText()
    {
        $result = new Result(
            readFixture('sparql_select_all.xml'),
            'application/sparql-results+xml'
        );

        $text = $result->dump('text');
        $this->assertStringContainsString('+-------------------------------------+', $text);
        $this->assertStringContainsString('| ?s                                  |', $text);
        $this->assertStringContainsString('| http://www.example.com/joe#me       |', $text);
        $this->assertStringContainsString('+---------------------+', $text);
        $this->assertStringContainsString('| ?p                  |', $text);
        $this->assertStringContainsString('| foaf:name           |', $text);
        $this->assertStringContainsString('+--------------------------------+', $text);
        $this->assertStringContainsString('| ?o                             |', $text);
        $this->assertStringContainsString('| "Joe Bloggs"@en                |', $text);
    }

    public function testDumpSelectUnbound()
    {
        $result = new Result(
            readFixture('sparql_select_unbound.xml'),
            'application/sparql-results+xml'
        );

        $html = $result->dump('html');
        $this->assertStringContainsString('>?person</th>', $html);
        $this->assertStringContainsString('>?name</th>', $html);
        $this->assertStringContainsString('>?foo</th>', $html);

        $this->assertStringContainsString('>http://dbpedia.org/resource/Tim_Berners-Lee</a>', $html);
        $this->assertStringContainsString('>&quot;Tim Berners-Lee&quot;@en</span>', $html);
        $this->assertStringContainsString('<td>&nbsp;</td>', $html);
    }

    public function testDumpAskFalseHtml()
    {
        $result = new Result(
            readFixture('sparql_ask_false.xml'),
            'application/sparql-results+xml'
        );

        $html = $result->dump('html');
        $this->assertStringContainsString('>false</span>', $html);
    }

    public function testDumpAskFalseText()
    {
        $result = new Result(
            readFixture('sparql_ask_false.xml'),
            'application/sparql-results+xml'
        );

        $text = $result->dump('text');
        $this->assertSame('Result: false', $text);
    }

    public function testDumpUnknownType()
    {
        $result = new Result(
            readFixture('sparql_ask_false.xml'),
            'application/sparql-results+xml'
        );

        $reflector = new \ReflectionProperty('EasyRdf\Sparql\Result', 'type');
        if (!method_exists($reflector, 'setAccessible')) {
            $this->markTestSkipped(
                'ReflectionProperty::setAccessible() is not available.'
            );
        } else {
            $reflector->setAccessible(true);
        }
        $reflector->setValue($result, 'foobar');

        $this->expectExceptionMessage(
            'Failed to dump SPARQL Query Results format, unknown type: foobar'
        );
        $result->dump();
    }

    public function testToStringBooleanTrue()
    {
        $result = new Result(
            readFixture('sparql_ask_true.xml'),
            'application/sparql-results+xml'
        );

        $this->assertSame('true', (string) $result);
    }

    public function testToStringBooleanFalse()
    {
        $result = new Result(
            readFixture('sparql_ask_false.xml'),
            'application/sparql-results+xml'
        );

        $this->assertSame('false', (string) $result);
    }

    public function testToStringSelectAll()
    {
        $result = new Result(
            readFixture('sparql_select_all.xml'),
            'application/sparql-results+xml'
        );

        $string = (string) $result;
        $this->assertStringContainsString('+-------------------------------------+', $string);
        $this->assertStringContainsString('| http://www.example.com/joe#me       |', $string);
    }

    public function testUnsupportedFormat()
    {
        $this->expectExceptionMessage('Unsupported SPARQL Query Results format: foo/bar');

        new Result('foobar', 'foo/bar');
    }
}
