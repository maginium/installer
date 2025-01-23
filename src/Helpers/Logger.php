<?php

declare(strict_types=1);

namespace Maginium\Installer\Helpers;

use Maginium\Installer\Concerns\InteractsWithIO;

/**
 * Logger class to handle logging messages with different severity levels.
 *
 * This class integrates with the IO interface to print messages to the console
 * and can be extended for more sophisticated logging mechanisms.
 */
class Logger
{
    use InteractsWithIO;

    /**
     * Log a message at the specified level.
     *
     * @param string $level The log level (e.g., 'info', 'warning', 'error').
     * @param string $message The log message.
     * @param int|string|null $verbosity
     *
     * @return void
     */
    public function log(string $level, string $message, int|string|null $verbosity = null): void
    {
        // Log the message using the provided logger interface
        $this->line($message, $level, $verbosity);
    }
}
