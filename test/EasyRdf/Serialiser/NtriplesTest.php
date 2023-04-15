<?php

namespace Test\EasyRdf\Serialiser;

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
use EasyRdf\Literal;
use EasyRdf\RdfNamespace;
use EasyRdf\Resource;
use EasyRdf\Serialiser\Ntriples;
use Test\TestCase;

class NtriplesTest extends TestCase
{
    /** @var Ntriples */
    protected $serialiser = null;
    /** @var Graph */
    protected $graph = null;

    protected function setUp(): void
    {
        $this->graph = new Graph();
        $this->serialiser = new Ntriples();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        RdfNamespace::resetNamespaces();
        RdfNamespace::reset();
    }

    public function testSerialiseValueUriResource()
    {
        $this->assertSame(
            '<http://example.com/>',
            $this->serialiser->serialiseValue(
                new Resource('http://example.com/')
            )
        );
    }

    public function testSerialiseValueUriArray()
    {
        $this->assertSame(
            '<http://example.com/>',
            $this->serialiser->serialiseValue(
                ['type' => 'uri', 'value' => 'http://example.com/']
            )
        );
    }

    public function testSerialiseValueBnodeArray()
    {
        $this->assertSame(
            '_:one',
            $this->serialiser->serialiseValue(
                ['type' => 'bnode', 'value' => '_:one']
            )
        );
    }

    public function testSerialiseValueBnodeResource()
    {
        $this->assertSame(
            '_:two',
            $this->serialiser->serialiseValue(
                new Resource('_:two')
            )
        );
    }

    public function testSerialiseValueLiteralArray()
    {
        $this->assertSame(
            '"foo"',
            $this->serialiser->serialiseValue(
                ['type' => 'literal', 'value' => 'foo']
            )
        );
    }

    public function testSerialiseValueLiteralObject()
    {
        $this->assertSame(
            '"Hello"',
            $this->serialiser->serialiseValue(
                new Literal('Hello')
            )
        );
    }

    public function testSerialiseValueLiteralObjectWithDatatype()
    {
        $this->assertSame(
            '"10"^^<http://www.w3.org/2001/XMLSchema#integer>',
            $this->serialiser->serialiseValue(
                Literal::create(10)
            )
        );
    }

    public function testSerialiseValueLiteralObjectWithLang()
    {
        $this->assertSame(
            '"Hello World"@en',
            $this->serialiser->serialiseValue(
                new Literal('Hello World', 'en')
            )
        );
    }

    public function testSerialiseBadValue()
    {
        $this->expectException('EasyRdf\Exception');
        $this->expectExceptionMessage(
            "Unable to serialise object of type 'chipmonk' to ntriples"
        );
        $this->serialiser->serialiseValue(
            ['type' => 'chipmonk', 'value' => 'yes?']
        );
    }

    public function testSerialise()
    {
        $joe = $this->graph->resource(
            'http://www.example.com/joe#me',
            'foaf:Person'
        );
        $joe->set('foaf:name', 'Joe Bloggs');
        $joe->set(
            'foaf:homepage',
            $this->graph->resource('http://www.example.com/joe/')
        );
        $this->assertSame(
            '<http://www.example.com/joe#me> '.
            '<http://www.w3.org/1999/02/22-rdf-syntax-ns#type> '.
            "<http://xmlns.com/foaf/0.1/Person> .\n".
            '<http://www.example.com/joe#me> '.
            '<http://xmlns.com/foaf/0.1/name> '.
            "\"Joe Bloggs\" .\n".
            '<http://www.example.com/joe#me> '.
            '<http://xmlns.com/foaf/0.1/homepage> '.
            "<http://www.example.com/joe/> .\n",
            $this->serialiser->serialise($this->graph, 'ntriples')
        );
    }

    public function testSerialiseQuotes()
    {
        $joe = $this->graph->resource('http://www.example.com/joe#me');
        $joe->set('foaf:nick', '"Joey"');
        $this->assertSame(
            '<http://www.example.com/joe#me> '.
            '<http://xmlns.com/foaf/0.1/nick> '.
            '"\"Joey\"" .'."\n",
            $this->serialiser->serialise($this->graph, 'ntriples')
        );
    }

    public function testSerialiseBackslash()
    {
        $joe = $this->graph->resource('http://www.example.com/joe#me');
        $joe->set('foaf:nick', '\\backslash');
        $this->assertSame(
            '<http://www.example.com/joe#me> '.
            '<http://xmlns.com/foaf/0.1/nick> '.
            '"\\\\backslash" .'."\n",
            $this->serialiser->serialise($this->graph, 'ntriples')
        );
    }

    public function testSerialiseBNode()
    {
        $joe = $this->graph->resource('http://www.example.com/joe#me');
        $project = $this->graph->newBNode();
        $project->add('foaf:name', 'Project Name');
        $joe->add('foaf:project', $project);

        $this->assertSame(
            "_:genid1 <http://xmlns.com/foaf/0.1/name> \"Project Name\" .\n".
            '<http://www.example.com/joe#me> '.
            "<http://xmlns.com/foaf/0.1/project> _:genid1 .\n",
            $this->serialiser->serialise($this->graph, 'ntriples')
        );
    }

    public function testSerialiseLang()
    {
        $joe = $this->graph->resource('http://example.com/joe#me');
        $joe->set('foaf:name', new Literal('Joe', 'en'));

        $turtle = $this->serialiser->serialise($this->graph, 'ntriples');
        $this->assertStringEquals(
            '<http://example.com/joe#me> '.
            '<http://xmlns.com/foaf/0.1/name> '.
            "\"Joe\"@en .\n",
            $turtle
        );
    }

    public function testSerialiseDatatype()
    {
        $joe = $this->graph->resource('http://example.com/joe#me');
        $joe->set('foaf:foo', Literal::create(1, null, 'xsd:integer'));

        $ntriples = $this->serialiser->serialise($this->graph, 'ntriples');
        $this->assertStringEquals(
            '<http://example.com/joe#me> '.
            '<http://xmlns.com/foaf/0.1/foo> '.
            "\"1\"^^<http://www.w3.org/2001/XMLSchema#integer> .\n",
            $ntriples
        );
    }

    public function testSerialiseEmptyPrefix()
    {
        RdfNamespace::set('', 'http://foo/bar/');

        $joe = $this->graph->resource(
            'http://foo/bar/me'
        );

        $joe->set('foaf:name', 'Joe Bloggs');
        $joe->set(
            'foaf:homepage',
            $this->graph->resource('http://example.com/joe/')
        );

        $ntriples = $this->serialiser->serialise($this->graph, 'ntriples');

        $this->assertSame(
            "<http://foo/bar/me> <http://xmlns.com/foaf/0.1/name> \"Joe Bloggs\" .\n".
            "<http://foo/bar/me> <http://xmlns.com/foaf/0.1/homepage> <http://example.com/joe/> .\n",
            $ntriples
        );
    }

    public function testSerialiseUnsupportedFormat()
    {
        $this->expectException('EasyRdf\Exception');
        $this->expectExceptionMessage(
            'EasyRdf\Serialiser\Ntriples does not support: unsupportedformat'
        );
        $this->serialiser->serialise(
            $this->graph,
            'unsupportedformat'
        );
    }

    /**
     * @see https://github.com/easyrdf/easyrdf/issues/219
     * @see https://phabricator.wikimedia.org/T76854
     */
    public function testIssue219Unicode()
    {
        $pairs = [
            '位' => '"\u4F4D"',
            'Дуглас Адамс' => '"\u0414\u0443\u0433\u043B\u0430\u0441 \u0410\u0434\u0430\u043C\u0441"',
        ];

        $serializer = new Ntriples();

        foreach ($pairs as $string => $expected) {
            $literal = new Literal($string);
            $actual = $serializer->serialiseValue($literal);

            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests combinations of control characters and multibyte characters.
     */
    public function testMixedWithControlCharacters()
    {
        $serializer = new Ntriples();
        // Include the NULL byte, a character, a control character,
        // a multibyte character, a character, and a character outside the BMP.
        $string = chr(0).'a'.chr(31).'位'.chr(127).'𐀐';

        $literal = new Literal($string);
        $actual = $serializer->serialiseValue($literal);

        $this->assertEquals('"\u0000a\u001F\u4F4D\u007F\U00010010"', $actual);
    }

    /**
     * Tests that random sequences are not confused with multibyte characters.
     */
    public function testUnintendedMultibyteCharacter()
    {
        $serializer = new Ntriples();
        // Ensure that when the sequence from \xC1 to \xCF are interpreted as
        // separate characters and not confused with multibyte characters.
        $string = "\xC1\xC2\xC3\xC4\xC5\xC6\xC7\xC8\xC9\xCA\xCB\xCC\xCD\xCE\xCF";
        $literal = new Literal($string);
        $actual = $serializer->serialiseValue($literal);

        $this->assertEquals('"\u00C1\u00C2\u00C3\u00C4\u00C5\u00C6\u00C7\u00C8\u00C9\u00CA\u00CB\u00CC\u00CD\u00CE\u00CF"', $actual);
    }

    /**
     * Tests the basic Latin characters from U+0020 to U+007E.
     */
    public function testVisibleLatinCharacters()
    {
        $serializer = new Ntriples();
        $string = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
        $literal = new Literal($string);
        $actual = $serializer->serialiseValue($literal);
        $expected = '" !\"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\\\]^_`abcdefghijklmnopqrstuvwxyz{|}~"';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the Latin-1 Supplement characters from U+00A0 to U+00FF.
     */
    public function testLatin1SupplementCharacters()
    {
        $serializer = new Ntriples();
        $string = ' ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $literal = new Literal($string);
        $actual = $serializer->serialiseValue($literal);
        $expected = '" \u00A1\u00A2\u00A3\u00A4\u00A5\u00A6\u00A7\u00A8\u00A9\u00AA\u00AB\u00AC\u00AD\u00AE\u00AF\u00B0\u00B1\u00B2\u00B3\u00B4\u00B5\u00B6\u00B7\u00B8\u00B9\u00BA\u00BB\u00BC\u00BD\u00BE\u00BF\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5\u00C6\u00C7\u00C8\u00C9\u00CA\u00CB\u00CC\u00CD\u00CE\u00CF\u00D0\u00D1\u00D2\u00D3\u00D4\u00D5\u00D6\u00D7\u00D8\u00D9\u00DA\u00DB\u00DC\u00DD\u00DE\u00DF\u00E0\u00E1\u00E2\u00E3\u00E4\u00E5\u00E6\u00E7\u00E8\u00E9\u00EA\u00EB\u00EC\u00ED\u00EE\u00EF\u00F0\u00F1\u00F2\u00F3\u00F4\u00F5\u00F6\u00F7\u00F8\u00F9\u00FA\u00FB\u00FC\u00FD\u00FE\u00FF"';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the Latin Extended-A characters from U+0100 to U+017F.
     */
    public function testLatinExtendedACharacters()
    {
        $serializer = new Ntriples();
        $string = ' ĀāĂăĄąĆćĈĉĊċČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĲĳĴĵĶķĸĹĺĻļĽľĿŀŁłŃńŅņŇňŉŊŋŌōŎŏŐőŒœŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžſ';
        $literal = new Literal($string);
        $actual = $serializer->serialiseValue($literal);
        $expected = '" \u0100\u0101\u0102\u0103\u0104\u0105\u0106\u0107\u0108'.
            '\u0109\u010A\u010B\u010C\u010D\u010E\u010F\u0110\u0111\u0112\u0113'.
            '\u0114\u0115\u0116\u0117\u0118\u0119\u011A\u011B\u011C\u011D\u011E'.
            '\u011F\u0120\u0121\u0122\u0123\u0124\u0125\u0126\u0127\u0128\u0129'.
            '\u012A\u012B\u012C\u012D\u012E\u012F\u0130\u0131\u0132\u0133\u0134'.
            '\u0135\u0136\u0137\u0138\u0139\u013A\u013B\u013C\u013D\u013E\u013F'.
            '\u0140\u0141\u0142\u0143\u0144\u0145\u0146\u0147\u0148\u0149\u014A'.
            '\u014B\u014C\u014D\u014E\u014F\u0150\u0151\u0152\u0153\u0154\u0155'.
            '\u0156\u0157\u0158\u0159\u015A\u015B\u015C\u015D\u015E\u015F\u0160'.
            '\u0161\u0162\u0163\u0164\u0165\u0166\u0167\u0168\u0169\u016A\u016B'.
            '\u016C\u016D\u016E\u016F\u0170\u0171\u0172\u0173\u0174\u0175\u0176'.
            '\u0177\u0178\u0179\u017A\u017B\u017C\u017D\u017E\u017F"';
        $this->assertEquals($expected, $actual);
    }
}
