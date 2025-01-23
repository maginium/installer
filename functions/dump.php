<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\VarDumper\Caster\ScalarStub;
use Symfony\Component\VarDumper\VarDumper;

if (! function_exists('dump')) {
    /**
     * The `dump` function is used for dumping the content of variables for debugging.
     *
     * @param mixed ...$vars The variables to dump.
     *
     * @return mixed|null The dumped variables, or null if no variables are passed.
     */
    function dump(mixed ...$vars): mixed
    {
        if (! $vars) {
            // Check if no variables were passed to the function.
            // If no variables are passed, dump a ScalarStub with a bug emoji.
            VarDumper::dump(new ScalarStub('🐛'));

            // Return null if no variables were passed.
            return null;
        }

        if (array_key_exists(0, $vars) && count($vars) === 1) {
            // Check if exactly one variable is passed.

            // Dump the single variable.
            VarDumper::dump($vars[0]);

            // Set a key to track the variable position (for later return).
            $k = 0;
        } else {
            // Handle multiple variables passed to the function.
            foreach ($vars as $k => $v) {
                // Iterate over each variable.
                // Dump each variable with an identifier key.

                // If the key is an integer, increment it for better display.
                VarDumper::dump($v, is_int($k) ? 1 + $k : $k);
            }
        }

        if (count($vars) > 1) {
            // Check if multiple variables were passed.

            // Return the variables if more than one was passed.
            return $vars;
        }

        // Return the last dumped variable if only one was passed.
        return $vars[$k];
    }
}

if (! function_exists('dd')) {
    /**
     * The `dd` function is similar to `dump`, but it terminates the script after dumping the variable(s).
     *
     * @param mixed ...$vars The variables to dump and terminate the script.
     *
     * @return never This function always ends the script execution, so it doesn't return anything.
     */
    function dd(mixed ...$vars): never
    {
        // If the script is not being run in CLI or PHP debug modes, and if headers are not sent, send a 500 HTTP error header.
        if (! \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) && ! headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        if (! $vars) {
            // If no variables are passed.
            // Dump a ScalarStub with a bug emoji, indicating an issue.
            VarDumper::dump(new ScalarStub('🐛'));

            // Terminate the script with an error status.
            exit(1);
        }

        if (array_key_exists(0, $vars) && count($vars) === 1) {
            // If only one variable is passed.
            // Dump the single variable.
            VarDumper::dump($vars[0]);
        } else {
            // If multiple variables are passed.
            foreach ($vars as $k => $v) {
                // Iterate over the variables.
                // Dump each variable with an identifier key.

                // If the key is an integer, increment it for better display.
                VarDumper::dump($v, is_int($k) ? 1 + $k : $k);
            }
        }

        // Terminate the script after dumping the variables, with an error status.
        exit(1);
    }
}
