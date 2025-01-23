<?php

declare(strict_types=1);

namespace Maginium\Installer\Enums;

use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

/**
 * Defines constants for various input option modes used in Symfony commands.
 */
class InputModes
{
    /**
     * Mode representing an option with no value (flag).
     * Corresponds to Symfony's InputOption::VALUE_NONE.
     *
     * @var int
     */
    public const NONE = InputOption::VALUE_NONE;

    /**
     * Mode representing an option that requires a value.
     * Corresponds to Symfony's InputOption::VALUE_REQUIRED.
     *
     * @var int
     */
    public const REQUIRED = InputOption::VALUE_REQUIRED;

    /**
     * Mode representing an option that can optionally have a value.
     * Corresponds to Symfony's InputOption::VALUE_OPTIONAL.
     *
     * @var int
     */
    public const OPTIONAL = InputOption::VALUE_OPTIONAL;

    /**
     * Mode representing an option that accepts an array of values.
     * Corresponds to Symfony's InputOption::VALUE_IS_ARRAY.
     *
     * @var int
     */
    public const ARRAY = InputOption::VALUE_IS_ARRAY;

    /**
     * Mode representing a negatable option (e.g., --flag / --no-flag).
     * Corresponds to Symfony's InputOption::VALUE_NEGATABLE.
     *
     * @var int
     */
    public const NEGATABLE = InputOption::VALUE_NEGATABLE;

    /**
     * Maps string modes to their corresponding constants.
     *
     * Converts a string representation of a mode to its integer constant
     * value, ensuring case-insensitivity for input.
     *
     * @param string $mode The mode as a string.
     *
     * @throws InvalidArgumentException If the mode is not valid.
     *
     * @return int The corresponding constant integer value.
     */
    public static function fromString(string $mode): int
    {
        // Convert the mode to lowercase to ensure case-insensitivity when comparing
        return match (mb_strtolower($mode)) {
            // If the mode is 'none', return the constant VALUE_NONE
            'none' => InputOption::VALUE_NONE,
            // If the mode is 'required', return the constant VALUE_REQUIRED
            'required' => InputOption::VALUE_REQUIRED,
            // If the mode is 'optional', return the constant VALUE_OPTIONAL
            'optional' => InputOption::VALUE_OPTIONAL,
            // If the mode is 'array', return the constant VALUE_IS_ARRAY
            'array' => InputOption::VALUE_IS_ARRAY,
            // If the mode is 'negatable', return the constant VALUE_NEGATABLE
            'negatable' => InputOption::VALUE_NEGATABLE,
            // If the mode does not match any of the above, throw an exception
            default => throw new InvalidArgumentException("Invalid mode: {$mode}"),
        };
    }
}
