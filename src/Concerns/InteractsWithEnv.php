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

namespace Maginium\Installer\Concerns;

use Dotenv\Dotenv;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

/**
 * Trait EnvTrait.
 *
 * Provides utility methods for managing environment variables.
 * This trait handles tasks related to checking, setting, and encoding environment variables.
 */
trait InteractsWithEnv
{
    /**
     * Check if the app's .env file is writable.
     * This method ensures that the .env file exists and is writable, allowing updates to environment variables.
     *
     * @return bool Returns true if the .env file is writable, false otherwise.
     */
    protected function checkEnvWritable(): bool
    {
        // Get the absolute path of the .env file
        $path = base_path('.env');

        // Get the absolute path of the .gitignore file
        $gitignore = base_path('.gitignore');

        // If the .env file does not exist, copy the example environment file and reload variables.
        if (! $this->getFilesystem()->exists($path)) {
            // Copy .env.example to .env
            copy(base_path('.env.example'), $path);

            // Refresh environment variables after creating .env
            $this->refreshEnvVars();
        }

        // Check if the .gitignore file exists and is writable; if so, add modules to it.
        if ($this->getFilesystem()->exists($gitignore) && is_writable($gitignore)) {
            // Add relevant modules to .gitignore
            $this->addModulesToGitignore($gitignore);
        }

        // Return whether the .env file is writable
        return is_writable($path);
    }

    /**
     * Refresh environment variables by reloading the .env file.
     * This method ensures the latest values from the .env file are loaded into the application.
     */
    protected function refreshEnvVars(): void
    {
        // Load .env variables into the application
        Dotenv::create(Env::getRepository(), App::environmentPath(), App::environmentFile())->load();
    }

    /**
     * Set multiple environment variables at once.
     * This method iterates over an associative array and sets each key-value pair as an environment variable.
     *
     * @param array $vars Associative array of environment variables to set.
     */
    protected function setEnvVars(array $vars): void
    {
        foreach ($vars as $key => $val) {
            // Set each individual environment variable
            $this->setEnvVar($key, $val);
        }
    }

    /**
     * Set a single environment variable.
     * This method updates the value of a specific environment variable in the .env file.
     *
     * @param string $key The key of the environment variable.
     * @param mixed $value The value to set for the environment variable.
     */
    protected function setEnvVar($key, $value): void
    {
        // Get the absolute path to the .env file
        $path = base_path('.env');

        // Get the current value of the environment variable
        $old = $this->getEnvVar($key);

        // Encode the value if necessary for special characters
        $value = $this->encodeEnvVar($value);

        // If the current value of the environment variable is a boolean, convert it to string 'true' or 'false'.
        if (is_bool(env($key))) {
            $old = env($key) ? 'true' : 'false';
        }

        // If the .env file exists, replace the old value with the new one.
        if ($this->getFilesystem()->exists($path)) {
            $this->getFilesystem()->put($path, Str::replace(
                [$key . '=' . $old, $key . '=' . '"' . $old . '"'],  // Search for the old key-value pair
                [$key . '=' . $value, $key . '=' . $value],  // Replace with the new key-value pair
                $this->getFilesystem()->get($path),  // Get the contents of the .env file
            ));
        }

        // Store the new value in the userConfig array for later use
        $this->userConfig[$key] = $value;
    }

    /**
     * Encode the environment variable for compatibility with certain characters.
     * This method ensures that the value is properly escaped to prevent issues with special characters in environment files.
     *
     * @param mixed $value The value to encode.
     *
     * @return mixed The encoded value (if it's a string).
     */
    protected function encodeEnvVar($value): mixed
    {
        // If the value is not a string, return it as is.
        if (! is_string($value)) {
            return $value;
        }

        // If the value contains quotes, escape them by adding a backslash before each quote.
        if (Str::contains($value, '"')) {
            // Escape quotes
            $value = Str::replace('"', '\\"', $value);
        }

        // Return the encoded value
        return $value;
    }

    /**
     * Get the value of a specific environment variable.
     * This method retrieves the current value of an environment variable.
     *
     * @param string $key The key of the environment variable.
     *
     * @return string The value of the environment variable.
     */
    protected function getEnvVar($key): string
    {
        // Return the current value of the environment variable using the env() helper
        return env($key);
    }
}
