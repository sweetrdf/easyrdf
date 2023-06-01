<?php

namespace Test;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

/*
 * This file is licensed under the terms of BSD-3 license and
 * is part of the EasyRdf package.
 *
 * (c) 2021 Konrad Abicht <hi@inspirito.de>
 * (c) 2009-2020 Nicholas J Humfrey
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

class TestCase extends FrameworkTestCase
{
    public static function assertStringEquals($str1, $str2, $message = null)
    {
        self::assertSame((string) $str1, (string) $str2, (string) $message);
    }

    /**
     * Note: this differs from assertInstanceOf because it disallows subclasses
     */
    public static function assertClass($class, $object)
    {
        self::assertSame($class, $object::class);
    }

    /**
     * assertMatchesRegularExpression is not available in lower PHP versions,
     * therefore this polyfill.
     *
     * @todo remove after dropping PHP 7.2 support.
     */
    protected function assertMatchesRegularExpressionPolyfill(
        string $pattern,
        string $string,
        string $message = ''
    ): void {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression($pattern, $string, $message);
        } else {
            $this->assertRegExp($pattern, $string, $message);
        }
    }
}
