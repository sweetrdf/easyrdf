<?php

declare(strict_types=1);

namespace Test\ClassProxy\EasyRdf;

use EasyRdf\Graph;

/*
 * This file is licensed under the terms of BSD-3 license and
 * is part of the EasyRdf package.
 *
 * (c) 2021 Konrad Abicht <hi@inspirito.de>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * Provides property annotations, because in ResourceTest new properties get created on the fly.
 * We avoid errors like "Access to an undefined property EasyRdf\Resource::$test." with this approach.
 *
 * @see https://phpstan.org/writing-php-code/phpdocs-basics#magic-properties
 *
 * @property string $foobar
 * @property string $test
 */
class GraphProxy extends Graph
{
}
