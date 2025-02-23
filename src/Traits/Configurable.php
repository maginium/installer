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
use InvalidArgumentException;
use it;
use Maginium\Installer\Enums\InputModes;
use Maginium\Installer\Helpers\ConfigurationRegistry;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Trait Configurable.
 *
 * This trait provides various configuration options for the Maginium installer command.
 * It defines methods to configure the command's arguments, options, and other parameters
 * required for setting up a new Maginium application. Each configuration method is modular,
 * handling different aspects of the application's setup process, such as database, cache,
 * session, and other service configurations.
 */
trait Configurable
{
    /**
     * Tracks the shortcuts that have already been used to ensure uniqueness.
     *
     * This array stores all generated shortcuts as keys, allowing quick lookups
     * to detect and prevent duplication. Each shortcut corresponds to a
     * configuration option added to the command.
     *
     * @var array
     */
    private $usedShortcuts = [];

    /**
     * Configures the command options for creating a new Maginium application.
     * This method sets up all the configuration options for the command, such as
     * database, cache, session, and environment settings, using values from the
     * configuration registry.
     *
     * It iterates over each configuration type and its associated settings, adding
     * them as options to the command with proper handling of default values,
     * descriptions, and optional settings.
     *
     * @return void
     */
    protected function configure()
    {
        // Initialize the base configuration settings
        $this->setBaseConfiguration();

        // Configure the installation-related options, such as database, cache, etc.
        $this->setInstallationOptions();

        // Retrieve all configuration settings from the configuration registry
        $options = $this->getConfigurationRegistry()->getConfigurations();

        // Iterate over each configuration type (e.g., 'database', 'cache')
        foreach ($options as $type => $option) {
            // Iterate over each individual configuration within the current type
            foreach ($option[ConfigurationRegistry::CONFIGURATIONS] as $key => $configuration) {
                // Check for missing fields and throw an exception if necessary
                $this->validateConfiguration($configuration);

                // Check if shortcut is set, if not generate it
                $shortcut = isset($configuration[ConfigurationRegistry::SHORTCUT]) && ! empty($configuration[ConfigurationRegistry::SHORTCUT])
                    ? $configuration[ConfigurationRegistry::SHORTCUT]
                    : $this->generateOptionShortcut($key, $type); // Generate shortcut if it does not exist

                // Determine the mode of the option (flag, value, etc.) with fallback to VALUE_OPTIONAL
                // The mode is converted to the corresponding constant using a helper method
                $mode = is_string($configuration[ConfigurationRegistry::MODE])
                    ? $this->getOptionMode($configuration[ConfigurationRegistry::MODE])
                    : InputOption::VALUE_OPTIONAL;

                // Description of the option, if provided
                $description = $configuration[ConfigurationRegistry::DESCRIPTION] ?? null;

                // Default value for the option, if provided
                $default = $this->acceptValue($mode) ? $configuration[ConfigurationRegistry::DEFAULT] : null;

                // Suggested values for the option, if any, to provide autocomplete or validation
                $suggestedValues = $this->acceptValue($mode) ? $configuration[ConfigurationRegistry::SUGGESTED_VALUES] : [];

                // Add the option to the command with the collected parameters
                // The 'addOption' method registers the option for use in the command
                $this->addOption(
                    name: $key, // Option name
                    shortcut: $shortcut, // Shortcut for the option (if any)
                    mode: $mode, // Mode (flag, value, etc.)
                    description: $description, // Description for the option (helps with documentation)
                    default: $default, // Default value if the option is not set by the user
                    suggestedValues: $suggestedValues, // Suggested values for validation or autocomplete
                );
            }
        }
    }

    /**
     * Set the basic configuration for the command.
     * Defines the basic properties of the command, such as the name, description,
     * and several options (like creating a Git repository or installing a development version).
     *
     * @return self
     */
    protected function setBaseConfiguration(): self
    {
        return $this
          // Define an argument for the application's name (mandatory)
            ->addArgument('name', InputArgument::REQUIRED);
    }

    /**
     * Set the installation configuration options.
     * Defines options for installation settings such as database driver, stack selection, and testing frameworks.
     *
     * @return self
     */
    protected function setInstallationOptions(): self
    {
        return $this
          // Force install option, even if the directory already exists
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Forces install even if the directory already exists');
    }

    /**
     * Returns true if the option accepts a value.
     *
     * @return bool true if value mode is not ConfigurationRegistry::VALUE_NONE, false otherwise
     */
    protected function acceptValue($mode): bool
    {
        return $mode === InputOption::VALUE_REQUIRED || $mode === InputOption::VALUE_OPTIONAL;
    }

    /**
     * Generate and display the title and description for a given configuration section type.
     *
     * This function retrieves the configuration data for the specified type
     * and displays the title and description using console output methods.
     *
     * @param string $sectionType The configuration section type (e.g., ConfigTypes::STORE).
     *
     * @return void
     */
    protected function generateSectionPrompt(string $sectionType): void
    {
        // Retrieve the configuration for the given section type
        $config = $this->getConfigurationRegistry()->getConfiguration($sectionType);

        if (isset($config[ConfigurationRegistry::NAME], $config[ConfigurationRegistry::DESCRIPTION])) {
            // Styling title (bold and green)
            $this->line('<fg=green;options=bold>' . $config[ConfigurationRegistry::NAME] . '</>');

            // Styling description (light gray)
            $this->comment('<fg=bright-cyan>' . $config[ConfigurationRegistry::DESCRIPTION] . '</>');
        } else {
            // Display a warning if the configuration data is incomplete or unavailable
            $this->line('Configuration data for the selected section is not available.', 'error');
        }
    }

    /**
     * Generate a shortcut based on the provided key and optional namespace.
     *
     * @param string $key The key to generate a shortcut for.
     * @param string|null $namespace The optional namespace.
     *
     * @return string The generated shortcut.
     */
    protected function generateOptionShortcut(string $key, ?string $namespace = null): string
    {
        // Split the key into words based on dash or underscore (e.g., 'my-key' becomes ['my', 'key'])
        $words = preg_split('/[-_]/', $key);

        // Generate a shortcut by taking the first character of each word and converting to uppercase
        $shortcut = implode('', array_map(fn($word) => mb_strtoupper($word[0]), $words));

        // Ensure the shortcut is lowercase, optionally prefixed with the namespace
        $shortcut = Str::lower($namespace ? "{$namespace}:{$shortcut}" : $shortcut);

        // If the shortcut already exists, append the full key to ensure uniqueness
        if (in_array($shortcut, $this->usedShortcuts, true)) {
            $shortcut = Str::lower($namespace ? "{$namespace}:{$key}" : $key);
        }

        // Add the shortcut to the array to track future duplication
        $this->usedShortcuts[] = $shortcut;

        return $shortcut;
    }

    /**
     * Converts a string mode to the corresponding constant integer from InputModes.
     *
     * @param string $mode The mode as a string.
     *
     * @throws InvalidArgumentException If the mode is not valid.
     *
     * @return int The corresponding constant integer value.
     */
    protected function getOptionMode(string $mode): int
    {
        return InputModes::fromString($mode);
    }

    /**
     * Validates that the configuration has all required fields.
     *
     * @param array $configuration The configuration array to validate.
     *
     * @throws InvalidArgumentException If any required field is missing.
     */
    protected function validateConfiguration(array $configuration)
    {
        $requiredFields = ['name', 'description', 'default', 'suggestedValues'];

        foreach ($requiredFields as $field) {
            if (! array_key_exists($field, $configuration)) {
                throw new InvalidArgumentException("Missing required configuration field: {$field}");
            }
        }
    }
}
