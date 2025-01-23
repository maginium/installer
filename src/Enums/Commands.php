<?php

declare(strict_types=1);

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
