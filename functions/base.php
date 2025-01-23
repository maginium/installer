<?php

declare(strict_types=1);

use Symfony\Component\Process\ExecutableFinder;

if (! function_exists('base_path')) {
    /**
     * Returns the base path, optionally appending a given relative path.
     *
     * If the BP constant is defined, it will return its value.
     * Otherwise, it returns the directory name of the current script (__DIR__).
     * If a path is provided, it will be appended to the base path.
     *
     * @param string|null $path Optional relative path to append to the base path.
     *
     * @return string The base path, optionally concatenated with the given path.
     */
    function base_path(?string $path = null): string
    {
        $basePath = defined('BP') ? BP : __DIR__;

        // If a path is provided, append it to the base path with directory separator
        return join_paths($basePath, $path);
    }
}

if (! function_exists('vendor_path')) {
    /**
     * Returns the path to the vendor directory.
     *
     * This function concatenates the base path with the 'vendor' directory.
     * It is generally used to reference third-party libraries installed via Composer.
     *
     * @return string The vendor path.
     */
    function vendor_path(): string
    {
        return base_path() . '/vendor';
    }
}

if (! function_exists('join_paths')) {
    /**
     * Join the given paths together.
     *
     * @param  string|null  $basePath
     * @param  string  ...$paths
     *
     * @return string
     */
    function join_paths($basePath, ...$paths)
    {
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath . implode('', $paths);
    }
}

if (! function_exists(function: 'php_binary')) {
    /**
     * Determine the PHP Binary.
     *
     * @return string
     */
    function php_binary()
    {
        return (new ExecutableFinder)->find('bin/magento') ?: 'php';
    }
}

if (! function_exists('magento_binary')) {
    /**
     * Determine the Magento Binary.
     *
     * @return string
     */
    function magento_binary()
    {
        return 'bin/magento';
    }
}
