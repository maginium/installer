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

use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;
use Maginium\Installer\Helpers\ConfigurationRegistry;
use Maginium\Installer\Helpers\Currency as CurrencyHelper;
use Maginium\Installer\Helpers\Language as LanguageHelper;
use Maginium\Installer\Helpers\Timezone as TimezoneHelper;
use Povils\Figlet\Figlet;
use ReflectionClass;
use Symfony\Component\Process\Process;

/**
 * Trait Container.
 *
 * This class acts as a dependency container, managing instances of core setup dependencies
 * such as LanguageHelper, TimezoneHelper, CurrencyHelper, Filesystem, ConfigurationRegistry,
 * and Figlet. It centralizes the instantiation and retrieval of these services.
 */
trait InteractWithContainer
{
    /**
     * @var array Stores the resolved service instances.
     */
    private array $instances = [];

    /**
     * Retrieve a custom service from the container.
     *
     * @param string $key
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (! $this->has($key)) {
            throw new InvalidArgumentException("Service '{$key}' not found in the container.");
        }

        return $this->instances[$key];
    }

    /**
     * Register a custom service in the container.
     *
     * @param string $key
     * @param callable $factory
     */
    public function register(string $key, callable $factory): void
    {
        $this->instances[$key] = $factory();
    }

    /**
     * Create a new instance of a given class with optional arguments.
     *
     * This method is a generic utility for instantiating any class. It allows passing
     * arguments to the constructor when required.
     *
     * @param string $class The fully qualified class name.
     * @param array $arguments Optional arguments to pass to the class constructor.
     *
     * @throws InvalidArgumentException
     *
     * @return object The new instance of the given class.
     */
    public function make(string $class, array $arguments = []): object
    {
        if (! class_exists($class)) {
            throw new InvalidArgumentException("Class '{$class}' does not exist.");
        }

        // If no arguments, instantiate the class directly
        if (empty($arguments)) {
            return new $class;
        }

        // Instantiate the class with arguments using Reflection
        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->newInstanceArgs($arguments);
    }

    /**
     * Check if an instance exists in the container.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->instances);
    }

    /**
     * Retrieve or create the LanguageHelper instance.
     *
     * @return LanguageHelper
     */
    public function getLanguageHelper(): LanguageHelper
    {
        return $this->instances[LanguageHelper::class] ??= new LanguageHelper;
    }

    /**
     * Retrieve or create the TimezoneHelper instance.
     *
     * @return TimezoneHelper
     */
    public function getTimezoneHelper(): TimezoneHelper
    {
        return $this->instances[TimezoneHelper::class] ??= new TimezoneHelper;
    }

    /**
     * Retrieve or create the CurrencyHelper instance.
     *
     * @return CurrencyHelper
     */
    public function getCurrencyHelper(): CurrencyHelper
    {
        return $this->instances[CurrencyHelper::class] ??= new CurrencyHelper;
    }

    /**
     * Retrieve or create the ConfigurationRegistry instance.
     *
     * @return ConfigurationRegistry
     */
    public function getConfigurationRegistry(): ConfigurationRegistry
    {
        return $this->instances[ConfigurationRegistry::class] ??= ConfigurationRegistry::instance();
    }

    /**
     * Retrieve or create the Filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem(): Filesystem
    {
        return $this->instances[Filesystem::class] ??= new Filesystem;
    }

    /**
     * Retrieve or create the Figlet instance.
     *
     * @return Figlet
     */
    public function getFiglet(): Figlet
    {
        return $this->instances[Figlet::class] ??= new Figlet;
    }

    /**
     * Create and return a new Process instance.
     *
     * @param string $command The command to execute.
     * @param array $arguments The arguments to pass to the command.
     *
     * @return Process The new Process instance.
     */
    public function createProcess(string $command, array $arguments = []): Process
    {
        // Use the `make` method to create a new Process instance
        return $this->make(Process::class, [array_merge([$command], $arguments)]);
    }
}
