<?php

declare(strict_types=1);

/*
 *
 *  🚀 This file is part of the Maginium Framework.
 *
 *  ©️ 2025. Maginium Technologies <contact@maginium.com>
 *  🖋️ Author: Abdelrhman Kouta
 *      - 📧 Email: pixiedia@gmail.com
 *      - 🌐 Website: https://maginium.com
 *  📖 Documentation: https://docs.maginium.com
 *
 *  📄 For the full copyright and license information, please view
 *  the LICENSE file that was distributed with this source code.
 */

use Symfony\Component\Process\ExecutableFinder;

if (! function_exists('base_path')) {
    /**
     * Retrieves the base path of the application, optionally appending a relative path.
     *
     * Returns the value of the BP constant if defined, otherwise falls back to the directory
     * of the current script (__DIR__). When a relative path is provided, it is appended
     * to the base path using the appropriate directory separator.
     *
     * @param string|null $path Optional relative path to append to the base path.
     *                          If null, only the base path is returned.
     *
     * @return string The absolute base path, or base path concatenated with the given relative path.
     */
    function base_path(?string $path = null): string
    {
        // Use defined constant BP if available, otherwise use current directory
        $basePath = defined('BP') ? BP : dirname(__DIR__);

        // Return base path directly if no additional path is provided
        return $path === null ? $basePath : join_paths($basePath, $path);
    }
}

if (! function_exists('vendor_path')) {
    /**
     * Retrieves the absolute path to the vendor directory.
     *
     * Constructs the path by appending '/vendor' to the base path. This is typically
     * used to reference Composer-installed third-party libraries and dependencies.
     *
     * @return string The absolute path to the vendor directory.
     */
    function vendor_path(): string
    {
        return base_path('vendor');
    }
}

if (! function_exists('join_paths')) {
    /**
     * Combines multiple path segments into a single path string.
     *
     * Ensures proper directory separators between segments and removes duplicate
     * separators. Empty path segments (except '0') are ignored.
     *
     * @param string|null $basePath The initial base path to start with.
     * @param string ...$paths Variable number of additional path segments to join.
     *
     * @return string The combined path string with proper directory separators.
     */
    function join_paths(?string $basePath, string ...$paths): string
    {
        // Handle null basePath by converting to empty string
        $basePath ??= '';

        // Process each path segment
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                // Remove empty segments
                unset($paths[$index]);
            } else {
                // Ensure single separator and trim leading separators
                $paths[$index] = DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        // Combine base path with processed segments
        return $basePath . implode('', $paths);
    }
}

if (! function_exists('php_binary')) {
    /**
     * Determines the path to the PHP binary executable.
     *
     * Uses ExecutableFinder to locate the PHP binary. Falls back to 'php' if
     * the specific Magento binary cannot be found.
     *
     * @return string The path to the PHP binary or 'php' as default.
     */
    function php_binary(): string
    {
        // Attempt to find Magento-specific binary, fallback to generic 'php'
        return (new ExecutableFinder)->find('bin/magento') ?: 'php';
    }
}

if (! function_exists('magento_binary')) {
    /**
     * Provides the standard path to the Magento binary.
     *
     * Returns the conventional location of the Magento command-line tool
     * relative to the base path.
     *
     * @return string The relative path to the Magento binary.
     */
    function magento_binary(): string
    {
        return 'bin/magento';
    }
}
