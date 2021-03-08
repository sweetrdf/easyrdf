<?php

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

// Set time zone to UTC for running tests
date_default_timezone_set('UTC');

/**
 * Helper function: get path to a fixture file
 *
 * @param string $name fixture file name
 *
 * @return string Path to the fixture file
 */
function fixturePath($name)
{
    return __DIR__.\DIRECTORY_SEPARATOR.'fixtures'.\DIRECTORY_SEPARATOR.$name;
}

/**
 * Helper function: read fixture data from file
 *
 * @param string $name fixture file name
 *
 * @return string Fixture data
 */
function readFixture($name)
{
    return file_get_contents(fixturePath($name));
}

/**
 * Helper function: execute an example script in a new process
 *
 * Process isolation helps ensure that one script isn't tainting
 * the environment for another script, making it a fairer test.
 *
 * If you want to use a non-default PHP CLI executable, then set
 * the PHP environment variable to the path of executable.
 *
 * @param string $name   the name of the example to run
 * @param array  $params query string parameters to pass to the script
 *
 * @throws Exception
 *
 * @return string The resulting webpage (everything printed to STDOUT)
 */
function executeExample($name, array $params = [])
{
    $phpBin = getenv('PHP');
    if (!$phpBin) {
        $phpBin = 'php';
    }

    // We use a wrapper to setup the environment
    $wrapper = __DIR__.\DIRECTORY_SEPARATOR.'cli_example_wrapper.php';

    // Open a pipe to the new PHP process
    $descriptorspec = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open(
        escapeshellcmd($phpBin).' '.
        escapeshellcmd($wrapper).' '.
        escapeshellcmd($name).' '.
        escapeshellcmd(http_build_query($params)),
        $descriptorspec,
        $pipes
    );
    if (is_resource($process)) {
        // $pipes now looks like this:
        // 0 => writeable handle connected to child stdin
        // 1 => readable handle connected to child stdout
        // 2 => readable handle connected to child stderr

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        // It is important that you close any pipes before calling
        // proc_close in order to avoid a deadlock
        $returnValue = proc_close($process);
        if ($returnValue || $stderr) {
            throw new Exception("Failed to run script ($returnValue): ".$stderr.$stdout);
        }
    } else {
        throw new Exception("Failed to execute new php process: $phpBin");
    }

    return $stdout;
}

/**
 * Checks if script is running on Linux or not.
 * If not, it assumes it runs on Windows.
 */
function isLinux(): bool
{
    return '/' === DIRECTORY_SEPARATOR;
}
