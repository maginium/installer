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
use Symfony\Component\Console\Command\Command;

/**
 * CommandRegistry class extends Laravel's Collection to manage and store various types of commands.
 *
 * This class serves as a central repository for commands used in the application, such as commands for
 * handling currencies, timezones, languages, and other application-specific commands. It provides an easy-to-use
 * interface for adding, retrieving, and managing these commands.
 */
class CommandRegistry extends Collection
{
    use Singleton;

    /**
     * CommandRegistry constructor.
     *
     * Initializes the command registry with either default or custom commands.
     * If no commands are provided, an empty collection is used. This allows
     * for flexible extension or overriding of the registry contents.
     *
     * @param array $commands An array of custom commands to initialize the registry with.
     *
     * @throws InvalidArgumentException If the provided commands are not in the expected format.
     */
    public function __construct(array $commands = [])
    {
        // Validate the format of the provided commands, ensuring they are all strings
        foreach ($commands as $command) {
            if (! is_string($command)) {
                throw new InvalidArgumentException('Each command must be a string.');
            }
        }

        // Initialize the collection with the validated commands or an empty array
        parent::__construct($commands);
    }

    /**
     * Retrieve all registered commands.
     *
     * This method returns all the commands currently stored in the registry.
     *
     * @return array The list of all registered commands.
     */
    public function getCommands(): array
    {
        return $this->all();
    }

    /**
     * Check if a specific command exists in the registry.
     *
     * This method checks whether a given command is present in the registry.
     *
     * @param string|Command $command The command to check for existence in the registry.
     *
     * @return bool True if the command exists, false otherwise.
     */
    public function hasCommand(string|Command $command): bool
    {
        return $this->contains($command);
    }

    /**
     * Add a new command to the registry.
     *
     * This method allows adding a single command to the existing collection of commands.
     * It validates that the command is a string before adding it to the registry.
     *
     * @param Command $command The command to be added to the registry.
     *
     * @throws InvalidArgumentException If the command is not a valid string.
     */
    public function addCommand(Command $command): void
    {
        // Validate that the command is a string
        if (! $command instanceof Command) {
            throw new InvalidArgumentException(sprintf('The command must be instance of %s.', Command::class));
        }

        // Add the command to the registry
        $this->push($command);
    }
}
