<?php

namespace Tests\EasyRdf\Literal;

use EasyRdf\Literal\Boolean;
use Test\TestCase;

/*
 * EasyRdf
 *
 * LICENSE
 *
 * Copyright (c) 2011-2014 Nicholas J Humfrey.  All rights reserved.
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

class BooleanTest extends TestCase
{
    public function testConstructStringTrue()
    {
        $literal = new Boolean('true');
        $this->assertStringEquals('true', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertTrue($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstructStringFalse()
    {
        $literal = new Boolean('false');
        $this->assertStringEquals('false', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertFalse($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstructString1()
    {
        $literal = new Boolean('1');
        $this->assertStringEquals('1', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertTrue($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstructString0()
    {
        $literal = new Boolean('0');
        $this->assertStringEquals('0', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertFalse($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstructTrue()
    {
        $literal = new Boolean(true);
        $this->assertStringEquals('true', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertTrue($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstructFalse()
    {
        $literal = new Boolean(false);
        $this->assertStringEquals('false', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertFalse($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstruct1()
    {
        $literal = new Boolean(1);
        $this->assertStringEquals('true', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertTrue($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testConstruct0()
    {
        $literal = new Boolean(0);
        $this->assertStringEquals('false', $literal);
        $this->assertEquals('boolean', \gettype($literal->getValue()));
        $this->assertFalse($literal->getValue());
        $this->assertStringEquals('', $literal->getLang());
        $this->assertSame('xsd:boolean', $literal->getDatatype());
    }

    public function testIsTrue()
    {
        $true = new Boolean(true);
        $this->assertTrue($true->isTrue());

        $false = new Boolean(false);
        $this->assertFalse($false->isTrue());
    }

    public function testIsFalse()
    {
        $false = new Boolean(false);
        $this->assertTrue($false->isFalse());

        $true = new Boolean(true);
        $this->assertFalse($true->isFalse());
    }
}
