<?php

declare(strict_types=1);

/*
 *
 *  🚀 This file is part of the Maginium Framework.
 *
 *  ©️ 2025. Pixielity ©. Technologies <contact@maginium>
 *  🖋️ Author: Abdelrhman Kouta
 *      - 📧 Email: pixiedia@gmail.com
 *      - 🌐 Website: https://maginium.com
 *  📖 Documentation: https://docs.maginium.com
 *
 *  📄 For the full copyright and license information, please view
 *  the LICENSE file that was distributed with this source code.
 */

use Maginium\Installer\Helpers\CommandRegistry;
use Symfony\Component\Finder\Finder;

/**
 * Class CommandRegistration.
 *
 * Handles the dynamic registration of components by searching for and including
 * '*.php' files located in specified directories. Uses Symfony Finder
 * to locate files and Dotenv for environment variable management.
 */
class CommandRegistration
{
    /**
     * @var string Base directory from which to start searching for components.
     */
    private string $baseDir;

    /**
     * @var Finder Symfony Finder instance for file search functionality.
     */
    private Finder $finder;

    /**
     * @var array|string[] Array of glob patterns that specify directories to search.
     */
    private array $globPatterns;

    /**
     * CommandRegistration constructor.
     *
     * Initializes the base directory, loads environment variables, and sets up
     * the Symfony Finder instance for searching '*.php' files.
     *
     * @param array|string[] $globPatterns Array of glob patterns to search for components.
     */
    public function __construct(array $globPatterns)
    {
        // Set the base directory two levels up from the current directory.
        $this->baseDir = BP;

        $this->finder = new Finder;

        // Store the provided glob patterns to later search for component registrations.
        $this->globPatterns = $globPatterns;
    }

    /**
     * Registers components by finding and including '*.php' files based on
     * the specified glob patterns.
     *
     * If no files are found matching the glob patterns, a RuntimeException is thrown.
     *
     * @throws \RuntimeException If no '*.php' files are found.
     */
    public function registerCommands(): void
    {
        // Iterate over each glob pattern and configure Finder to search in relevant directories.
        foreach ($this->globPatterns as $globPattern) {
            $this->configureFinderFromPattern($globPattern);
        }

        // Throw an error if no files were found.
        if (! $this->finder->hasResults()) {
            throw new \RuntimeException('No files found matching the glob patterns.');
        }

        // Include each located configuration file.
        foreach ($this->finder as $file) {
            // Get the real path of the file.
            $filePath = $file->getRealPath();

            // Include the file to load the class definition.
            require_once $filePath;

            // Extract the class name from the file path.
            $className = $this->getClassNameFromFilePath($filePath);

            // Check if the class exists
            if (class_exists($className)) {
                // Instantiate the class dynamically
                $command = new $className;

                // Register the command using the CommandRegistry
                CommandRegistry::instance()->addCommand($command);
            } else {
                // Handle the case where the class doesn't exist (optional)
                throw new \RuntimeException("Class {$className} not found in file: {$filePath}");
            }
        }
    }

    /**
     * Extracts the class name and namespace from the file path.
     *
     * @param string $filePath The full path to the PHP file.
     *
     * @return string The fully qualified class name (namespace + class name).
     */
    private function getClassNameFromFilePath(string $filePath): string
    {
        // Include the file to ensure it is loaded.
        require_once $filePath;

        // Extract the class name and namespace from the file.
        $fileContent = file_get_contents($filePath);

        // Match the namespace using a regular expression
        if (preg_match('/\bnamespace\s+([a-zA-Z0-9\\\\_\\\\]+);/m', $fileContent, $matches)) {
            // Extract the namespace
            $namespace = $matches[1];
        } else {
            // Throw an exception if the namespace is not found
            throw new \RuntimeException("Namespace not found in file: {$filePath}");
        }

        // Match the class name using a regular expression
        if (preg_match('/\bclass\s+(\w+)/', $fileContent, $matches)) {
            // Extract the class name
            $className = $matches[1];
        } else {
            // Throw an exception if the class is not found
            throw new \RuntimeException("Class not found in file: {$filePath}");
        }

        // Combine the namespace and class name to return the fully qualified class name
        return '\\' . $namespace . '\\' . $className;
    }

    /**
     * Commandures the Finder to search for '*.php' files in the directory specified by a glob pattern.
     *
     * Extracts the base directory from the glob pattern, and if the directory exists, sets the Finder to search
     * for the '*.php' files within that directory.
     *
     * @param string $globPattern The glob pattern specifying directories to search.
     */
    private function configureFinderFromPattern(string $globPattern): void
    {
        // Concatenate the base directory with the pattern-specific directory.
        $baseDir = join_paths($this->baseDir, $globPattern);

        // Check if the directory exists before configuring Finder to search within it.
        if (is_dir($baseDir)) {
            $this->finder->files()
                ->in($baseDir)
                ->name('*.php'); // Pass the wildcard pattern directly to the 'name' method
        }
    }
}

/**
 * Main function to execute the component registration process.
 *
 * Defines the finder patterns for locating '*.php' files and
 * invokes the registration functionality in the CommandRegistration class.
 */
function registerCommands(): void
{
    // Define the glob patterns from 'registration_globlist' file.
    $globPatterns = [
        // Match *.php files directly under src/Commands/ (no subdirectories)
        'src/Commands',
        // Match *.php files under any subdirectories of src/Commands (depth of 2 levels or more)
        'src/Commands/*/*',
        // Alternatively, match *.php files at any depth under src/Commands
        'src/Commands/**',
    ];

    // Instantiate the component registrar with the defined glob patterns.
    $registrar = new \CommandRegistration($globPatterns);

    // Execute the registration process to include found components.
    $registrar->registerCommands();
}

// Call the main function to start component registration.
registerCommands();
