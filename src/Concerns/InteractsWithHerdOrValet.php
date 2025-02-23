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

use Symfony\Component\Process\Exception\ProcessStartFailedException;

/**
 * Trait InteractsWithHerdOrValet.
 *
 * This trait provides helper methods for interacting with the Herd and Valet command-line tools.
 * It includes functionality to determine if a given directory is parked on Herd or Valet, as well as
 * executing commands through these tools. The trait ensures that it gracefully handles process execution
 * failures and returns the appropriate results based on successful execution.
 */
trait InteractsWithHerdOrValet
{
    /**
     * Determine if the given directory is parked using Herd or Valet.
     *
     * This method checks if the provided directory is listed as a parked site by either
     * Herd or Valet. It runs a command through Herd or Valet to fetch all parked paths
     * and checks if the directory is included in the list.
     *
     * @param  string  $directory The directory to check
     *
     * @return bool  True if the directory is parked on Herd or Valet, false otherwise
     */
    public function isParkedOnHerdOrValet(string $directory): bool
    {
        // Fetch the paths from Herd or Valet using the 'paths' command.
        $output = $this->runOnValetOrHerd('paths');

        // If output is successfully fetched, check if the directory is parked.
        // We compare the parent directory of the provided path with the list of parked directories.
        return $output !== false ? in_array(dirname($directory), json_decode($output)) : false;
    }

    /**
     * Runs the given command on the "herd" or "valet" CLI tools.
     *
     * This method tries to execute the specified command (`$command`) on both Herd and Valet,
     * checking for success with each tool. It returns the command output if successful,
     * or `false` if neither tool is available or the command fails.
     *
     * @param  string  $command The command to run (e.g., 'paths')
     *
     * @return string|false The command output if successful, false otherwise
     */
    protected function runOnValetOrHerd(string $command)
    {
        // Iterate over the tools (Herd and Valet) to execute the command.
        foreach (['herd', 'valet'] as $tool) {
            // Create a process instance to run the command on the current tool.
            $process = $this->createProcess($tool, [$command, '-v']); // -v option for verbosity

            try {
                // Attempt to run the process and capture its output.
                $process->run();

                // Check if the process ran successfully and return its output.
                if ($process->isSuccessful()) {
                    return trim($process->getOutput());
                }
            } catch (ProcessStartFailedException $e) {
                // Catch the exception if the process fails to start (no tool available).
                // No action is needed here since we will simply try the next tool.
            }
        }

        // If neither tool succeeds, return false indicating failure.
        return false;
    }
}
