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
