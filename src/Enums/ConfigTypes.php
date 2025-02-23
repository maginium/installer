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

namespace Maginium\Installer\Enums;

/**
 * Class ConfigTypes.
 *
 * This class defines constants for various configuration types used within the application.
 */
class ConfigTypes
{
    /**
     * Represents the configuration type for admin users.
     */
    public const ADMIN_USER = 'admin-user';

    /**
     * Represents the configuration type for AMQP (Advanced Message Queuing Protocol).
     */
    public const AMQP = 'ampq';

    /**
     * Represents the configuration type for managing back-pressure in systems.
     */
    public const BACK_PRESSURE = 'back-pressure';

    /**
     * Represents the configuration type for caching mechanisms.
     */
    public const CACHE = 'cache';

    /**
     * Represents the configuration type for database-related settings.
     */
    public const DATABASE = 'database';

    /**
     * Represents the configuration type for general application settings.
     */
    public const GENERAL = 'general';

    /**
     * Represents the configuration type for application modules.
     */
    public const MODULES = 'modules';

    /**
     * Represents the configuration type for OpenSearch-related settings.
     */
    public const OPENSEARCH = 'opensearch';

    /**
     * Represents the configuration type for session management.
     */
    public const SESSION = 'session';

    /**
     * Represents the configuration type for store-specific settings.
     */
    public const STORE = 'store';

    /**
     * Get a list of all configuration types.
     *
     * This method returns an array of all defined configuration type constants.
     *
     * @return array<string> A list of all configuration types.
     */
    public static function all(): array
    {
        return [
            self::ADMIN_USER,
            self::AMQP,
            self::BACK_PRESSURE,
            self::CACHE,
            self::DATABASE,
            self::GENERAL,
            self::MODULES,
            self::OPENSEARCH,
            self::SESSION,
            self::STORE,
        ];
    }
}
