<?php

declare(strict_types=1);

namespace Maginium\Installer\Concerns;

use Illuminate\Support\Sleep;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\spin;

/**
 * Trait HasInteraction.
 *
 * This trait handles user interaction for the Maginium installer.
 * It provides methods for prompting the user for input, verifying system configurations,
 * and displaying messages like banners or success notes. Each method in this trait
 * ensures a smooth installation process by guiding the user through necessary steps.
 */
trait HasInteraction
{
    /**
     * Handle interaction with the user before validating input.
     *
     * This method prompts the user for various inputs if they aren't already provided, such as
     * the project name, available PHP extensions, Git repository settings, and more.
     * It also ensures necessary prerequisites are met before proceeding.
     *
     * @param  InputInterface  $input  The input interface for capturing user input.
     * @param  OutputInterface $output The output interface for displaying messages.
     *
     * @return void
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        // Call the parent method to execute any basic interaction behavior
        parent::interact($input, $output);

        // Step 2: Display the application banner (styling the banner text)
        $this->displayBanner();

        // // Step 3: Ensure the necessary PHP extensions are available for installation
        // spin(
        //     message: '🔎 Ensuring the necessary PHP extensions are available for installation...',
        //     callback: function() {
        //         // Add 3-second delay inside the callback
        //         Sleep::sleep(3);

        //         $this->ensureExtensionsAreAvailable();
        //     },
        // );

        // Step 4: Prompt for the project name if it is not already provided
        $this->promptForProjectName();

        // // Step 5: Verify if the application directory already exists unless the --force flag is set
        // spin(
        //     message: '🔎 Verifying if the application directory already exists...',
        //     callback: function() {
        //         // Add 3-second delay inside the callback
        //         Sleep::sleep(3);

        //         $this->verifyApplicationDirectory();
        //     },
        // );
    }

    /**
     * Display the application banner using ASCII art.
     *
     * This method initializes the Figlet instance and renders the Maginium banner.
     * The banner is styled with a red text color and displayed on a blue background.
     *
     * @return void
     */
    protected function displayBanner(): void
    {
        // Initialize Figlet instance for rendering ASCII art text.
        $figlet = $this->getFiglet();
        $figlet->setFont('ivrit');

        // Render the "Maginium" text and style it with colors.
        $this->line('<fg=#D53535>' . $figlet->render('Maginium') . '</>');
    }

    /**
     * Output the final message and credits after installation.
     *
     * This method displays a creative credit message after the successful completion
     * of the installation process. It provides a textual artwork as a visual finalization.
     *
     * @return void
     */
    protected function outputInstallationCredits(): void
    {
        // Clear the console screen using ANSI escape codes
        $this->clearConsole();

        // Message with credits artwork to display after installation is successful.
        $message = '⠀⠀⠀⠀⠀⠀⠀⡀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
            ⠀⠀⠀⠀⠀⠀⠠⢿⣿⠇⠀⠀⠀⣴⣦⠀⠀⠀⠀⠀⠀⠀⠀⣀⣤⠀⠸⠿⠀⠀
            ⠀⠀⠀⠀⠀⠀⠀⠀⠁⢠⡄⠀⠀⠀⠀⠀⠀⠀⠀⠀⣀⣤⣼⣯⣅⠀⠀⠀⠀⠀
            ⠀⠀⠀⠀⢀⣤⡀⠀⠀⠀⠹⣆⠀⠀⠀⠀⠀⠀⣠⠞⠋⣿⡇⢀⣽⠇⠀⠀⠀⠀
            ⠀⠀⠀⠀⠘⠛⠁⠀⢤⣤⣤⣿⣦⠀⠀⠀⠀⣰⣏⣀⡀⠘⠛⠛⠉⠀⠀⠀⠀⠀
            ⠀⠀⠀⠀⠀⠀⠀⠀⠈⢿⡄⠀⠀⠀⢀⣴⢾⣿⠉⠉⣿⠀⠀⠀⠐⣾⣷⠄⠀⠀
            ⠀⠀⠀⠀⠀⠀⢀⣀⣀⠀⠀⠀⠀⠀⣾⠃⠀⠻⣶⠾⠋⠀⠀⠀⠈⠈⠁⠀⠀⠀
            ⠀⠀⠀⠀⠀⠀⢻⣿⣿⣿⣶⣄⠀⠸⠏⠀⠀⠀⠀⠀⢀⡀⠀⠀⠀⠀⠀⢀⡀⠀
            ⠀⠀⠀⠀⠀⣦⠈⢿⣿⣿⣿⣿⣷⣄⠀⠀⠀⣤⡀⢠⡿⠻⣦⣀⣠⣴⠾⠛⠁⠀
            ⠀⠀⠀⠀⠸⣿⣷⣄⠻⣿⣿⣿⣿⣿⣷⡄⠀⠈⠻⠟⠁⠀⠈⠉⠁⠀⠀⠀⠀⠀
            ⠀⠀⠀⠀⣄⠙⠻⣿⣷⣌⠻⣿⣿⣿⣿⣿⡄⠀⠀⠀⢠⣶⡄⠀⠀⠀⠀⠀⠀⠀
            ⠀⠀⠀⠸⣿⣿⣦⣈⠙⠿⣷⣄⠙⠻⠿⣿⠇⠀⠀⠀⠀⠉⠀⠀⠀⠀⠀⠀⠀⠀
            ⠀⠀⢠⣦⣈⠙⠿⣿⣷⣦⡄⠉⠛⠂⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
            ⠀⠀⣼⠿⠿⠛⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀
            ⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀';

        // Output the credit message as part of the installation success.
        $this->line((string)$message);
    }
}
