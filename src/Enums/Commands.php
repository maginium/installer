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
 * Enum representing various console commands for the installer.
 *
 * This enum provides a structured way to define and access different commands.
 *
 * @method static self NEW() Represents the command to create a new application.
 * @method static self SETUP_INSTALL() Represents the command to install Magento setup.
 */
class Commands
{
    /**
     * Command to create a new application.
     */
    public const NEW = 'new';

    /**
     * Command to install Magento setup.
     */
    public const SETUP_INSTALL = 'setup:install';
}
