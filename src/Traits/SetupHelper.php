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

namespace Maginium\Installer\Traits;

use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * Trait SetupHelper.
 *
 * Provides utility methods for retrieving and managing instances
 * of core setup classes like Language, Timezone, and Currency.
 */
trait SetupHelper
{
    /**
     * Ensure that the required PHP extensions are installed.
     *
     * @throws RuntimeException If any required PHP extensions are missing
     *
     * @return void
     */
    protected function ensureExtensionsAreAvailable(): void
    {
        // Get a list of currently loaded PHP extensions.
        $availableExtensions = get_loaded_extensions();

        // Define a collection of required extensions and filter out those that are already loaded.
        $missingExtensions = collect([
            'ctype', 'filter', 'hash', 'mbstring', 'openssl', 'session', 'tokenizer',
        ])->reject(fn($extension) => in_array($extension, $availableExtensions));

        // If there are no missing extensions, simply return.
        if ($missingExtensions->isEmpty()) {
            return;
        }

        // If there are missing extensions, throw a RuntimeException listing the missing extensions.
        throw new RuntimeException(
            sprintf('The following PHP extensions are required but are not installed: %s', $missingExtensions->join(', ', ', and ')),
        );
    }

    /**
     * Generate a valid APP_URL for the given application name.
     *
     * @param  string  $name The name of the application.
     *
     * @return string The generated URL based on the application's name.
     */
    protected function generateAppUrl($name): string
    {
        // Create a hostname using the application's name, converting it to lowercase, and appending the TLD.
        $hostname = Str::lower($name) . '.' . $this->getTld();

        // Check if the hostname can be resolved. If so, return the full URL with the hostname, otherwise fallback to 'localhost'.
        return $this->canResolveHostname($hostname) ? 'http://' . $hostname : 'http://localhost';
    }

    /**
     * Get the TLD (Top Level Domain) for the application.
     *
     * @return string The TLD, or 'test' if not available.
     */
    protected function getTld(): string
    {
        // Retrieve the TLD using the 'runOnValetOrHerd' method or fallback to 'test' if unavailable.
        return $this->runOnValetOrHerd('tld') ?: 'test';
    }

    /**
     * Determine whether the given hostname is resolvable.
     *
     * @param  string  $hostname The hostname to check.
     *
     * @return bool True if the hostname can be resolved, false otherwise.
     */
    protected function canResolveHostname($hostname): bool
    {
        // Check if the hostname resolves successfully by checking if the result of 'gethostbyname' equals the original hostname.
        return gethostbyname($hostname . '.') !== $hostname . '.';
    }

    /**
     * Get the version that should be downloaded.
     *
     * @return string The version to be downloaded, either 'dev-master' or empty string.
     */
    protected function getVersion(): string
    {
        // Check if the 'dev' option is passed, and if so, return 'dev-master'. Otherwise, return an empty string.
        return $this->option('dev') ? 'dev-master' : '';
    }

    /**
     * Verify that the application does not already exist.
     *
     * @param  string  $directory The directory to check for existence.
     *
     * @throws RuntimeException If the application already exists in the directory.
     *
     * @return void
     */
    protected function verifyApplicationDoesntExist($directory): void
    {
        // Check if the specified directory exists and is not the current working directory.
        if ((is_dir($directory) || is_file($directory)) && $directory !== getcwd()) {
            // If the application directory already exists, throw a RuntimeException.
            throw new RuntimeException('Application already exists!');
        }
    }

    /**
     * Get the installation directory for the given application name.
     *
     * @param  string  $name The name of the application.
     *
     * @return string The path to the installation directory.
     */
    protected function getInstallationDirectory(string $name): string
    {
        // If the name is not '.', concatenate the current working directory with the application name to form the installation path.
        // Otherwise, just return the current directory ('.').
        return $name !== '.' ? getcwd() . '/' . $name : '.';
    }

    /**
     * Sanitize the application name by trimming unwanted characters.
     *
     * @param string $name  The application name provided by the user.
     *
     * @return string  The sanitized application name.
     */
    protected function sanitizeName(string $name): string
    {
        // Remove trailing slashes or backslashes from the name.
        return rtrim($name, '/\\');
    }

    /**
     * getRandomKey generates a random application key.
     */
    protected function getRandomKey(): string
    {
        return Str::random(16);
    }

    /**
     * Replace the given string in the given file with a replacement string.
     *
     * @param  string|array  $search The string or array of strings to search for.
     * @param  string|array  $replace The string or array of strings to replace with.
     * @param  string  $file The path to the file where the replacement will occur.
     *
     * @return void
     */
    protected function replaceInFile(string|array $search, string|array $replace, string $file): void
    {
        // Reads the contents of the file, replaces the search string with the replacement, and writes the result back to the file.
        $this->getFilesystem()->put(
            $file,
            Str::replace($search, $replace, $this->getFilesystem()->get($file)),
        );
    }

    /**
     * Run the given commands in the shell.
     *
     * @param  array|string  $commands The commands to execute (either a string or an array of strings).
     * @param  string|null  $workingPath The path where the commands should be run.
     * @param  array  $env The environment variables to set for the process.
     * @param  bool  $silent Optional parameter to execute the commands silently (default: false).
     *
     * @return Process The process instance that ran the commands.
     */
    protected function executeCommands(
        $commands,
        ?string $workingPath = null,
        array $env = [],
        bool $silent = false,
    ): Process {
        // If the commands are passed as a single string, convert them into an array.
        if (is_string($commands)) {
            $commands = [$commands];
        }

        // If the output is not decorated (i.e., no color formatting in the terminal), append '--no-ansi' to each command
        // (except 'chmod' and 'git' commands) to avoid any ANSI escape codes.
        if (! static::getOutput()->isDecorated()) {
            $commands = array_map(function($value) {
                if (Str::startsWith($value, 'chmod') || Str::startsWith($value, 'git')) {
                    return $value;
                }

                return $value . ' --no-ansi';
            }, $commands);
        }

        // If the 'quiet' option is set or the $silent flag is true, append '--quiet' to each command
        // (except 'chmod' and 'git' commands) to suppress command output.
        if ($this->option('quiet') || $silent) {
            $commands = array_map(function($value) {
                if (Str::startsWith($value, 'chmod') || Str::startsWith($value, 'git')) {
                    return $value;
                }

                return $value . ' --quiet';
            }, $commands);
        }

        // Create a new Process instance using the shell command line, combining all commands with '&&' and specifying the working directory and environment variables.
        $process = Process::fromShellCommandline(implode(' && ', $commands), $workingPath, $env, null, null);

        // If the operating system is not Windows and an 'install.lock' file exists, disable the timeout for the process.
        if ('\\' !== SP && $this->getFilesystem()->exists(base_path('install.lock'))) {
            $process->setTimeout(0);
        }

        // Run the process (commands).
        $process->run();

        // Return the process instance.
        return $process;
    }

    /**
     * Verify if the application directory exists unless the --force flag is set.
     *
     * @return void
     */
    protected function verifyApplicationDirectory()
    {
        // If the --force option is not set, verify that the application does not already exist
        if ($this->option('force') !== true) {
            $this->verifyApplicationDoesntExist(
                $this->getInstallationDirectory($this->argument('name')),
            );
        }
    }
}
