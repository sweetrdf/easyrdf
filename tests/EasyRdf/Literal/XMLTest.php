<?php

namespace Tests\EasyRdf\Literal;

use EasyRdf\Literal\XML;
use Test\TestCase;

/*
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2013-2014 Nicholas J Humfrey.  All rights reserved.
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

class XMLTest extends TestCase
{
    public function testConstruct()
    {
        $literal = new XML('<tag>Hello World</tag>');
        $this->assertClass('EasyRdf\Literal\XML', $literal);
        $this->assertStringEquals('<tag>Hello World</tag>', $literal);
        $this->assertEquals('string', \gettype($literal->getValue()));
        $this->assertSame('<tag>Hello World</tag>', $literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('rdf:XMLLiteral', $literal->getDatatype());
    }

    public function testDomParse()
    {
        $literal = new XML('<tag>Hello World</tag>');
        $dom = $literal->domParse();
        $this->assertClass('DOMDocument', $dom);
        $this->assertSame(
            "<?xml version=\"1.0\"?>\n<tag>Hello World</tag>\n",
            $dom->saveXML()
        );
    }
}
