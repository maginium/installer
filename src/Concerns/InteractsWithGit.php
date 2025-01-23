<?php

declare(strict_types=1);

namespace Maginium\Installer\Concerns;

use Symfony\Component\Process\Process;

/**
 * Trait InteractsWithGit.
 *
 * Provides Git-related utility methods.
 */
trait InteractsWithGit
{
    /**
     * Return the local machine's default Git branch if set, or default to 'main'.
     *
     * @return string The default Git branch (either the global config or 'main').
     */
    protected function defaultBranch(): string
    {
        // Create a Process instance to run the Git command that retrieves the default branch name.
        // The command 'git config --global init.defaultBranch' checks the global Git config for the default branch.
        $process = $this->createProcess('git', ['config', '--global', 'init.defaultBranch']);

        // Execute the command and wait for it to finish.
        $process->run();

        // Get the output from the command execution, trim any extra whitespace or newlines.
        $output = trim($process->getOutput());

        // Return the default branch name if the command was successful and output is not empty.
        // Otherwise, return 'main' as the fallback default branch name.
        return $process->isSuccessful() && $output ? $output : 'main';
    }
}
