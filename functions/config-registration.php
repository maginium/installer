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

use Maginium\Installer\Helpers\ConfigurationRegistry;
use Symfony\Component\Finder\Finder;

/**
 * Class ConfigRegistration.
 *
 * Handles the dynamic registration of components by searching for and including
 * '*.json' files located in specified directories. Uses Symfony Finder
 * to locate files and Dotenv for environment variable management.
 */
class ConfigRegistration
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
     * ConfigRegistration constructor.
     *
     * Initializes the base directory, loads environment variables, and sets up
     * the Symfony Finder instance for searching '*.json' files.
     *
     * @param array|string[] $globPatterns Array of glob patterns to search for components.
     */
    public function __construct(array $globPatterns)
    {
        // Set the base directory two levels up from the current directory.
        $this->baseDir = base_path();

        $this->finder = new Finder;

        // Store the provided glob patterns to later search for component registrations.
        $this->globPatterns = $globPatterns;
    }

    /**
     * Registers components by finding and including '*.json' files based on
     * the specified glob patterns.
     *
     * If no files are found matching the glob patterns, a RuntimeException is thrown.
     *
     * @throws \RuntimeException If no '*.json' files are found.
     */
    public function registerConfigs(): void
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

            // Read the content of the configuration file (JSON content).
            $configData = file_get_contents($filePath);

            // If the file content is not valid JSON, throw an error.
            $decodedConfig = json_decode($configData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Skip invalid JSON files.
                continue;
            }

            // Use the filename as the config type (without extension).
            $configType = pathinfo($filePath, PATHINFO_FILENAME);

            // Use empty array if configurations not exists
            $configData = $decodedConfig ?? [];

            // Register the configuration using the extracted config type and parsed data.
            ConfigurationRegistry::instance()->addConfiguration($configType, $configData);
        }
    }

    /**
     * Configures the Finder to search for '*.json' files in the directory specified by a glob pattern.
     *
     * Extracts the base directory from the glob pattern, and if the directory exists, sets the Finder to search
     * for the '*.json' files within that directory.
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
                ->name('*.json'); // Pass the wildcard pattern directly to the 'name' method
        }
    }
}

/**
 * Main function to execute the component registration process.
 *
 * Defines the finder patterns for locating '*.json' files and
 * invokes the registration functionality in the ConfigRegistration class.
 */
function registerConfigs(): void
{
    // Define the glob patterns from 'registration_globlist' file.
    $globPatterns = [
        // Match *.json files directly under src/Configs/ (no subdirectories)
        'src/Configs',
        // Match *.json files under any subdirectories of src/Configs (depth of 2 levels or more)
        'src/Configs/*/*',
        // Alternatively, match *.json files at any depth under src/Configs
        'src/Configs/**',
    ];

    // Instantiate the component registrar with the defined glob patterns.
    $registrar = new \ConfigRegistration($globPatterns);

    // Execute the registration process to include found components.
    $registrar->registerConfigs();
}

// Call the main function to start component registration.
registerConfigs();
