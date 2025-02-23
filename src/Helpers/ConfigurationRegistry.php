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

namespace Maginium\Installer\Helpers;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Maginium\Installer\Traits\Singleton;

/**
 * ConfigurationRegistry class extends Laravel's Collection to manage various configurations.
 *
 * This class provides a central registry for different types of configurations, such as currencies,
 * timezones, languages, etc. It allows easy access, modification, and management of these configurations.
 */
class ConfigurationRegistry extends Collection
{
    use Singleton;

    /**
     * The key for specifying the configurations array.
     */
    public const CONFIGURATIONS = 'configurations';

    /**
     * The key for specifying the tags array.
     */
    public const TAGS = 'tags';

    /**
     * The key for specifying the shortcut in the configuration array.
     */
    public const SHORTCUT = 'shortcut';

    /**
     * The key for specifying the mode (e.g., flag, value) in the configuration array.
     */
    public const MODE = 'mode';

    /**
     * The key for specifying the name of the option in the configuration array.
     */
    public const NAME = 'name';

    /**
     * The key for specifying the description of the option in the configuration array.
     */
    public const DESCRIPTION = 'description';

    /**
     * The key for specifying the default value of the option in the configuration array.
     */
    public const DEFAULT = 'default';

    /**
     * The key for specifying suggested values for the option in the configuration array.
     */
    public const SUGGESTED_VALUES = 'suggestedValues';

    /**
     * Constructor to initialize the registry with default or custom configurations.
     *
     * The constructor initializes the collection with the provided configurations.
     * If no configurations are provided, the default configurations are used.
     * This allows the registry to be extended or overridden based on the application's needs.
     *
     * @param array $configurations The custom configurations to initialize the registry with.
     */
    public function __construct($configurations = [])
    {
        // Initialize the registry with the default configurations
        parent::__construct($configurations);
    }

    /**
     * Retrieve all configurations.
     *
     * This method returns a collection of all configurations, such as currencies or languages.
     *
     * @return array The complete set of configurations.
     */
    public function getConfigurations(): array
    {
        return $this->all();
    }

    /**
     * Retrieve a configuration set by type.
     *
     * This method allows retrieving a specific configuration set (e.g., currencies or languages).
     *
     * @param string $configType The type of configuration (e.g., 'currencies').
     *
     * @return array The requested configuration as a collection.
     */
    public function getConfiguration(string $configType): array
    {
        // Ensure the configuration type exists in the registry
        if (! $this->has($configType)) {
            throw new InvalidArgumentException("Configuration type '{$configType}' does not exist.");
        }

        return $this->get($configType);
    }

    /**
     * Check if a specific configuration set exists.
     *
     * This method checks whether a configuration set of a specific type exists in the registry.
     *
     * @param string $configType The type of configuration (e.g., 'currencies').
     *
     * @return bool True if the configuration set exists, otherwise false.
     */
    public function hasConfiguration(string $configType): bool
    {
        return $this->has($configType);
    }

    /**
     * Retrieve the configurations array for all configurations that have a specific tag.
     *
     * This method filters through the configurations and returns only the "configurations"
     * array of those that have the given tag in their "tags" array inside the "configurations" array.
     *
     * @param string $tag The tag to filter configurations by (e.g., "magento").
     *
     * @return array The filtered configurations arrays that contain the specified tag.
     */
    public function getByTag(string $tag): array
    {
        // Loop over all configurations and filter by tag
        return $this->filter(function($configData) use ($tag) {
            // Loop through each configuration set in "configurations" array
            foreach ($configData[static::CONFIGURATIONS] as $configItem) {
                // Check if the "tags" key exists and contains the specified tag
                if (isset($configItem[static::TAGS]) && in_array($tag, $configItem[static::TAGS])) {
                    return true; // Found a match, return true to keep this configData
                }
            }

            return false; // No match found, exclude this configData
        })
        // Map over the result to only return the "configurations" array for each match
            ->map(function($configData) {
                return $configData[static::CONFIGURATIONS]; // Return only the configurations array
            })
            ->all(); // Convert the filtered and mapped result to an array
    }

    /**
     * Add a new configuration set to the registry.
     *
     * This method allows adding a new set of configurations (e.g., new currencies or timezones)
     * to the registry.
     *
     * @param string $configType The type of configuration (e.g., 'currencies', 'languages').
     * @param array $configData The configuration data to add.
     *
     * @return void
     */
    public function addConfiguration(string $configType, array $configData): void
    {
        // Ensure the configuration type exists in the registry
        if (! $this->has($configType)) {
            // Initialize the configuration type with an empty array if it doesn't exist
            $this->put($configType, []);
        }

        // Merge the new configuration with the existing configuration set for the given type
        // This assumes $this->get($configType) is an array or Arrayable item.
        $existingConfig = $this->get($configType);

        // Merge the existing configuration with the new data
        $mergedConfig = array_merge($existingConfig, $configData);

        // Put the merged configuration back in the registry
        $this->put($configType, $mergedConfig);
    }

    /**
     * Update an existing configuration set.
     *
     * This method allows updating an existing configuration set (e.g., modifying currencies).
     *
     * @param string $configType The type of configuration (e.g., 'currencies').
     * @param array $configData The updated configuration data.
     *
     * @throws InvalidArgumentException If the configuration type does not exist.
     *
     * @return void
     */
    public function updateConfiguration(string $configType, array $configData): void
    {
        // Ensure the configuration type exists in the registry
        if (! $this->has($configType)) {
            throw new InvalidArgumentException("Configuration type '{$configType}' does not exist.");
        }

        // Replace the existing configuration with the new one
        $this->put($configType, $configData);
    }

    /**
     * Remove a configuration set from the registry.
     *
     * This method allows removing a specific configuration set (e.g., a currency or timezone).
     *
     * @param string $configType The type of configuration (e.g., 'currencies').
     *
     * @return void
     */
    public function removeConfiguration(string $configType): void
    {
        // Ensure the configuration type exists before removing
        if ($this->has($configType)) {
            $this->forget($configType);
        }
    }
}
