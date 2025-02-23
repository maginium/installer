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

namespace Maginium\Installer\Commands;

use Exception;
use Illuminate\Support\Composer;
use Maginium\Installer\Concerns\ConfiguresPrompts;
use Maginium\Installer\Concerns\HasInteraction;
use Maginium\Installer\Concerns\InteractsWithComposer;
use Maginium\Installer\Concerns\InteractsWithEnv;
use Maginium\Installer\Concerns\InteractsWithGit;
use Maginium\Installer\Concerns\InteractsWithHerdOrValet;
use Maginium\Installer\Concerns\InteractsWithIO;
use Maginium\Installer\Concerns\InteractWithContainer;
use Maginium\Installer\Enums\Commands;
use Maginium\Installer\Traits\Configurable;
use Maginium\Installer\Traits\InstallationWizard;
use Maginium\Installer\Traits\SetupHelper;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\spin;

/**
 * Command to handle new project installations.
 *
 * This command performs various steps to install the Maginium project,
 * including sanitization, version determination, Composer initialization,
 * and the actual setup process with progress feedback.
 */
#[AsCommand(Commands::NEW)]
class NewCommand extends Command
{
    // Adds configurable options for the class
    use Configurable;
    // Handles prompt configurations during setup
    use ConfiguresPrompts;
    // Enables interaction capabilities within the command
    use HasInteraction;
    // Facilitates the installation wizard process
    use InstallationWizard;
    // Provides interaction with Composer for dependency management
    use InteractsWithComposer;
    // Handles interactions with environment files
    use InteractsWithEnv;
    // Provides utilities for interacting with Git repositories
    use InteractsWithGit;
    // Supports interactions with Herd or Valet for local development environments
    use InteractsWithHerdOrValet;
    // Provides input/output interaction utilities
    use InteractsWithIO;
    // Manages package dependencies required by the application
    use InteractWithContainer;
    // Offers various setup helper methods
    use SetupHelper;

    /**
     * A description of the command functionality.
     */

    // Describes the purpose of the command.
    protected ?string $description = 'Install a new Maginium project and set it up.';

    /**
     * Executes the installation command, managing various steps like sanitizing inputs,
     * initializing Composer, handling the force option, and running setup commands.
     *
     * @param OutputInterface $output The output interface for writing messages to the console.
     *
     * @return int  The exit code of the process, indicating success or failure.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Sanitizes the project name passed as an argument.
        $projectName = $this->sanitizeName($this->argument('name'));

        // Gets the directory where the project should be installed.
        $installationDirectory = $this->getInstallationDirectory($projectName);

        // Initializes Composer with the specified directory.
        $this->initializeComposer($installationDirectory);

        // Handles the --force option if provided.
        $this->handleForceOption($installationDirectory);

        // Clear the console screen using ANSI escape codes
        $this->clearConsole();

        $this->getInstallationCommand();

        // Start setup prompts for additional configuration.
        $this->startSetupPrompts();

        // $process = spin(
        //     // Spins with a progress message while executing the setup process.
        //     message: '🚀 Setting up the project template...', // Displays a progress message.
        //     // The callback to execute the setup commands.
        //     callback: function() use ($installationDirectory) {
        //         // Retrieves the version of Maginium to be installed.
        //         $version = $this->getVersion();

        //         // Generates the setup commands.
        //         $commands = $this->generateSetupCommands($installationDirectory, $version);

        //         // Executes the setup commands silently.
        //         return $this->executeCommands($commands, silent: true);
        //     },
        // );

        // If the setup process was successful, proceed with installation.
        // if ($process->isSuccessful()) {
        if (true) {
            // Proceed with installation steps.
            // Initiates the actual installation process.
            // $installationSuccess = $this->performMaginiumInstallation();

            // If installation is successful, finalize the setup.
            // if ($installationSuccess) {
            // $metaPackageInstallation = spin(
            //     message: '⬇️ Installing meta package...', // Progress message for the database migration process.
            //     callback: fn(): bool => $this->composerRequireMetaPackage(),
            // );

            // if ($metaPackageInstallation) {
            if (true) {
                $setupMigration = $this->performSetupMigration();

                // If the process fails, display an error message.
                if ($setupMigration) {
                    // Finalizes the installation by updating configuration.
                    $this->finalizeInstallationSetup($projectName, $installationDirectory);
                }
            }
            // }
        }

        // Return success code for the command.
        return self::SUCCESS;
    }

    /**
     * Initializes the command after the input has been bound and before the input
     * is validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @see InputInterface::bind()
     * @see InputInterface::validate()
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        // Clear the console screen using ANSI escape codes
        $output->write(sprintf("\033\143"));

        // Set the input and output for further usage
        static::setOutput($output);
        static::setInput($input);

        // Step 1: Configure and display prompts for user input
        $this->configurePrompts($input, $output);
    }

    /**
     * Handle the --force option to determine whether to overwrite existing files.
     *
     * @param string $directory The directory where the installation is taking place.
     *
     * @return void
     */
    private function handleForceOption(string $directory): void
    {
        // If --force is not set, verify that the application doesn't exist.
        if (! $this->option('force')) {
            // Verifies that the application directory does not exist.
            // $this->verifyApplicationDoesntExist($directory);
        }

        // If --force is set in the current directory, throw an exception.
        if ($this->option('force') && $directory === '.') {
            throw new RuntimeException('Cannot use --force option in the current directory.');
        }
    }

    /**
     * Perform the actual Maginium database migration with proper feedback.
     *
     * This method handles the full database migration process, including running necessary commands
     * for cleaning up, compiling dependencies, deploying static content, and managing caches.
     * It also ensures proper feedback to the user during the migration process.
     *
     * @return bool Returns true if migration is successful, false otherwise.
     */
    private function performSetupMigration(): bool
    {
        // Perform database migration tasks with a spinning progress indicator.
        return spin(
            message: '🔄 Migrating Database...', // Progress message for the database migration process.
            callback: function(): bool {
                // Define the migration commands to be executed in sequence.
                $commands = [
                    'rm -rf var/di/* var/generation/* var/cache/* var/page_cache/* var/session/* var/view_preprocessed/* pub/static/* generated/*',  // Clean up generated and cached files
                    // 'php bin/magento setup:di:compile', // Compile dependency injection configurations
                    // 'php bin/magento setup:static-content:deploy -f', // Deploy static content
                    // 'php bin/magento cache:flush', // Flush cache
                    // 'php bin/magento cache:clean', // Clean cache
                ];

                // Execute each command and return false if any command fails.
                foreach ($commands as $command) {
                    $process = $this->executeCommands($command);

                    // If any command fails, display an error and return false.
                    if (! $process->isSuccessful()) {
                        $this->error('⚠️ Migration failed: ' . $process->getErrorOutput());

                        return false;
                    }
                }

                return true;
            },
        );
    }

    /**
     * Perform the actual Maginium installation with proper feedback.
     *
     * @return bool Returns true if installation is successful, false otherwise.
     */
    private function performMaginiumInstallation(): bool
    {
        // Start setup prompts for additional configuration.
        $this->startSetupPrompts();

        return spin( // Spins while performing the Maginium installation.
            message: '🅼 Installing Maginium...', // Displays a progress message for installation.
            // Callback to execute the installation command.
            callback: function(): bool {
                // Retrieves the installation command.
                $command = $this->getInstallationCommand();

                // Executes the installation command.
                $process = $this->executeCommands($command);

                // If the process fails, display an error message.
                if (! $process->isSuccessful()) {
                    $this->error('Installation failed: ' . $process->getErrorOutput());

                    // Return false if installation fails.
                    return false;
                }

                // Return true if installation is successful.
                return true;
            },
        );
    }

    /**
     * Generate the necessary setup commands for the installation process.
     *
     * @param string $directory The installation directory.
     * @param string $version The version of Maginium to install.
     *
     * @return array List of commands to run for installation.
     */
    private function generateSetupCommands(string $directory, string $version): array
    {
        // Finds the Composer executable.
        $composer = $this->findComposer();

        // Initialize an empty array for commands.
        $commands = [];

        // If the --force option is set, delete the existing directory if it's not the current directory.
        if ($directory !== '.' && $this->option('force')) {
            $commands[] = PHP_OS_FAMILY === 'Windows'
                ? "(if exist \"{$directory}\" rd /s /q \"{$directory}\")" // Command to remove the directory in Windows.

                // Command to remove the directory in Unix-based systems.
                : "rm -rf \"{$directory}\"";
        }

        // Command to create the Maginium project using Composer.
        $commands[] = "{$composer} create-project maginium/template \"{$directory}\" {$version} --remove-vcs --prefer-dist --no-scripts";
        // Command to run post-root-package-install after project creation.
        $commands[] = "{$composer} run post-root-package-install -d \"{$directory}\"";

        // Return the generated setup commands.
        return $commands;
    }

    /**
     * Finalize the installation by updating the environment file and displaying success messages.
     *
     * @param string $name The sanitized application name.
     * @param string $directory The installation directory.
     *
     * @return void
     */
    private function finalizeInstallationSetup(string $name, string $directory): void
    {
        // If the project name is not the current directory, update the .env file.
        if ($name !== '.') {
            $this->replaceInFile('APP_URL=http://localhost', 'APP_URL=' . $this->generateAppUrl($name), $directory . '/.env');
        }

        // Output outro message with additional information.
        $this->outputInstallationCredits();

        // Display success messages with instructions on how to continue.
        $this->line("  <bg=blue;fg=white> INFO </> Application ready in <options=bold>[{$name}]</>. Start your local development with:" . PHP_EOL);
        $this->line('<fg=gray>➜</> <options=bold>cd ' . $name . '</>');
        $this->line('<fg=gray>➜</> <options=bold>npm install && npm run build</>');

        // If the project is on Herd or Valet, provide a URL to open the project.
        if ($this->isParkedOnHerdOrValet($directory)) {
            $url = $this->generateAppUrl($name);
            $this->line('<fg=gray>➜</> Open: <options=bold;href=' . $url . '>' . $url . '</>');
            // Otherwise, give the command to run the project locally.
        } else {
            $this->line('<fg=gray>➜</> <options=bold>composer run dev</>');
        }

        // Adds a new line for formatting.
        $this->line('');

        // Final information and links to Maginium resources.
        $this->line('  New to Maginium? Visit our <href=https://bootcamp.maginium.com>bootcamp</> and <href=https://maginium.com/docs/installation#next-/> for more details. <options=bold>Build something awesome!</>');
    }
}
