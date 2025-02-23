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
use Illuminate\Support\Composer;
use RuntimeException;

/**
 * Trait InteractsWithComposer.
 *
 * This trait provides helper methods for interacting with Composer to install dependencies,
 * manage authentication, and process required packages in the Maginium CMS context.
 */
trait InteractsWithComposer
{
    /**
     * @var Composer|null
     */
    protected ?Composer $composer = null;

    /**
     * Set the Composer instance for later use.
     *
     * This method stores the Composer instance, allowing the trait's methods to
     * access the Composer object without needing to pass it explicitly each time.
     *
     * @param Composer $composer The Composer instance to store
     *
     * @return void
     */
    protected function setComposer(Composer $composer): void
    {
        $this->composer = $composer;
    }

    /**
     * Get the Composer instance.
     *
     * This method returns the stored Composer instance, throwing an exception
     * if it's not set.
     *
     * @throws RuntimeException if the Composer instance has not been set
     *
     * @return Composer
     */
    protected function getComposer(): Composer
    {
        if ($this->composer === null) {
            throw new RuntimeException('Composer instance has not been set.');
        }

        return $this->composer;
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string The full composer command to be executed.
     */
    protected function findComposer()
    {
        $composer = $this->getComposer();

        // Finds and returns the appropriate composer command for the environment.
        return implode(' ', $composer->findComposer());
    }

    /**
     * Initialize Composer with the necessary configurations.
     *
     * @param string $directory The installation directory.
     *
     * @return void
     */
    protected function initializeComposer(string $directory): void
    {
        // Creates a new Composer instance with the directory.
        $composer = new Composer(new Filesystem, $directory);

        // Sets the Composer instance for use in the command.
        $this->setComposer($composer);
    }

    /**
     * Generate and execute the Composer require command to install specified dependencies.
     *
     * This method constructs the appropriate Composer command string based on the provided
     * package(s) and uses the Composer object to install the required packages.
     *
     * @param  string|array $package The package or list of packages to require (e.g. 'maginium/all' or ['package1', 'package2'])
     *
     * @return bool
     */
    protected function composerRequire(string|array $package): bool
    {
        $composer = $this->getComposer();

        // Ensure $package is always treated as an array, even if a single package string is provided
        $packages = is_array($package) ? $package : [$package];

        // Generate the Composer require command for the processed packages
        return $composer->requirePackages($packages);
    }

    /**
     * Install core Maginium dependencies with optional versioning.
     *
     * This method checks if a version is specified in the $want parameter. If no version is
     * provided, it installs the default Maginium packages. If a version is provided, it installs
     * the packages with the specified version.
     *
     * @return bool
     */
    protected function composerRequireMetaPackage(): bool
    {
        // Install the default package
        return $this->composerRequire('maginium/meta-package:*');
    }

    /**
     * Set authentication for Composer and Maginium CMS.
     *
     * This method configures authentication using the provided email and project key,
     * and can store the credentials or project details in the necessary configuration files.
     *
     * @param  string $email The email associated with the Composer account
     * @param  string $projectKey The project key for the Maginium CMS
     *
     * @return void
     */
    protected function setComposerAuth(string $email, string $projectKey): void
    {
        $composer = $this->getComposer();

        // Example: Save authentication token (currently commented out)
        // $composer->addAuthCredentials(
        //     $this->getComposerUrl(false),
        //     $email,
        //     $projectKey,
        // );

        // Example: Store project details (currently commented out)
        // $this->injectJsonToFile(storage_path('cms/project.json'), [
        //     'project' => $projectKey,
        // ]);
    }
}
