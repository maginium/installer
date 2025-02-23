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

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

trait InteractsWithIO
{
    /**
     * The input interface implementation.
     *
     * @var InputInterface
     */
    private static $input;

    /**
     * The output interface implementation.
     *
     * @var SymfonyStyle
     */
    private static $output;

    /**
     * The default verbosity of output commands.
     *
     * @var int
     */
    private static $verbosity = OutputInterface::VERBOSITY_NORMAL;

    /**
     * The mapping between human readable verbosity levels and Symfony's OutputInterface.
     *
     * @var array
     */
    private static array $verbosityMap = [
        'v' => OutputInterface::VERBOSITY_VERBOSE,
        'vv' => OutputInterface::VERBOSITY_VERY_VERBOSE,
        'vvv' => OutputInterface::VERBOSITY_DEBUG,
        'quiet' => OutputInterface::VERBOSITY_QUIET,
        'normal' => OutputInterface::VERBOSITY_NORMAL,
    ];

    /**
     * Determine if the given argument is present.
     *
     * @param  string|int  $name
     *
     * @return bool
     */
    public static function hasArgument($name)
    {
        return static::getInput()->hasArgument($name);
    }

    /**
     * Get the value of a command argument.
     *
     * @param  string|null  $key  The key of the argument to retrieve.
     *                             If null, all arguments are returned.
     * @param  mixed        $default  The default value to return if the argument does not exist.
     *
     * @return array|string|bool|null  The value of the argument, or all arguments if no key is provided.
     */
    public static function argument($key = null, $default = null)
    {
        // If no key is provided, return all arguments.
        if ($key === null) {
            return static::getInput()->getArguments();
        }

        // If the argument exists, return its value, otherwise return the default value.
        return static::getInput()->hasArgument($key) ? static::getInput()->getArgument($key) : $default;
    }

    /**
     * Get all of the arguments passed to the command.
     *
     * @return array
     */
    public static function arguments()
    {
        return static::argument();
    }

    /**
     * Determine if the given option is present.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public static function hasOption($name)
    {
        return static::getInput()->hasOption($name);
    }

    /**
     * Get the value of a command option.
     *
     * @param  string|null  $key  The key of the option to retrieve.
     *                             If null, all options are returned.
     * @param  mixed        $default  The default value to return if the option does not exist.
     *
     * @return string|array|bool|null  The value of the option, or all options if no key is provided.
     */
    public static function option($key = null, $default = null)
    {
        // If no key is provided, return all options.
        if ($key === null) {
            return static::getInput()->getOptions();
        }

        // If the option exists, return its value, otherwise return the default value.
        return static::getInput()->hasOption($key) ? static::getInput()->getOption($key) : $default;
    }

    /**
     * Get all of the options passed to the command.
     *
     * @return array
     */
    public static function options()
    {
        return static::option();
    }

    /**
     * Confirm a question with the user.
     *
     * @param  string  $question
     * @param  bool  $default
     *
     * @return bool
     */
    public static function confirm($question, $default = false)
    {
        return static::getOutput()->confirm($question, $default);
    }

    /**
     * Prompt the user for input.
     *
     * @param  string  $question
     * @param  string|null  $default
     *
     * @return mixed
     */
    public static function ask($question, $default = null)
    {
        return static::getOutput()->ask($question, $default);
    }

    /**
     * Prompt the user for input with auto completion.
     *
     * @param  string  $question
     * @param  array|callable  $choices
     * @param  string|null  $default
     *
     * @return mixed
     */
    public static function anticipate($question, $choices, $default = null)
    {
        return static::askWithCompletion($question, $choices, $default);
    }

    /**
     * Prompt the user for input with auto completion.
     *
     * @param  string  $question
     * @param  array|callable  $choices
     * @param  string|null  $default
     *
     * @return mixed
     */
    public static function askWithCompletion($question, $choices, $default = null)
    {
        $question = new Question($question, $default);

        is_callable($choices)
            ? $question->setAutocompleterCallback($choices)
            : $question->setAutocompleterValues($choices);

        return static::getOutput()->askQuestion($question);
    }

    /**
     * Prompt the user for input but hide the answer from the console.
     *
     * @param  string  $question
     * @param  bool  $fallback
     *
     * @return mixed
     */
    public static function secret($question, $fallback = true)
    {
        $question = new Question($question);

        $question->setHidden(true)->setHiddenFallback($fallback);

        return static::getOutput()->askQuestion($question);
    }

    /**
     * Give the user a single choice from an array of answers.
     *
     * @param  string  $question
     * @param  array  $choices
     * @param  string|int|null  $default
     * @param  mixed|null  $attempts
     * @param  bool  $multiple
     *
     * @return string|array
     */
    public static function choice($question, array $choices, $default = null, $attempts = null, $multiple = false)
    {
        $question = new ChoiceQuestion($question, $choices, $default);

        $question->setMaxAttempts($attempts)->setMultiselect($multiple);

        return static::getOutput()->askQuestion($question);
    }

    /**
     * Format input to textual table.
     *
     * @param  array  $headers
     * @param  Arrayable|array  $rows
     * @param  TableStyle|string  $tableStyle
     * @param  array  $columnStyles
     *
     * @return void
     */
    public static function table($headers, $rows, $tableStyle = 'default', array $columnStyles = [])
    {
        $table = new Table(static::$output);

        if ($rows instanceof Arrayable) {
            $rows = $rows->toArray();
        }

        $table->setHeaders((array)$headers)->setRows($rows)->setStyle($tableStyle);

        foreach ($columnStyles as $columnIndex => $columnStyle) {
            $table->setColumnStyle($columnIndex, $columnStyle);
        }

        $table->render();
    }

    /**
     * Execute a given callback while advancing a progress bar.
     *
     * @param  iterable|int  $totalSteps
     * @param  Closure  $callback
     *
     * @return mixed|void
     */
    public static function withProgressBar($totalSteps, Closure $callback)
    {
        $bar = static::getOutput()->createProgressBar(
            is_iterable($totalSteps) ? count($totalSteps) : $totalSteps,
        );

        $bar->start();

        if (is_iterable($totalSteps)) {
            foreach ($totalSteps as $key => $value) {
                $callback($value, $bar, $key);

                $bar->advance();
            }
        } else {
            $callback($bar);
        }

        $bar->finish();

        if (is_iterable($totalSteps)) {
            return $totalSteps;
        }
    }

    /**
     * Write a string as information output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function info($string, $verbosity = null)
    {
        static::line($string, 'info', $verbosity);
    }

    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @param  string|null  $style
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function line($string, $style = null, $verbosity = null)
    {
        $styled = $style ? "<{$style}>{$string}</{$style}>" : $string;

        static::getOutput()->writeln($styled, static::parseVerbosity($verbosity));
    }

    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function comment($string, $verbosity = null)
    {
        static::line($string, 'comment', $verbosity);
    }

    /**
     * Write a string as question output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function question($string, $verbosity = null)
    {
        static::line($string, 'question', $verbosity);
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function error($string, $verbosity = null)
    {
        static::line($string, 'error', $verbosity);
    }

    /**
     * Write a string as warning output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function warn($string, $verbosity = null)
    {
        if (! static::getOutput()->getFormatter()->hasStyle('warning')) {
            $style = new OutputFormatterStyle('yellow');

            static::getOutput()->getFormatter()->setStyle('warning', $style);
        }

        static::line($string, 'warning', $verbosity);
    }

    /**
     * Write a string in an alert box.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     *
     * @return void
     */
    public static function alert($string, $verbosity = null)
    {
        $length = Str::length(strip_tags($string)) + 12;

        static::comment(Str::repeat('*', $length), $verbosity);
        static::comment('*     ' . $string . '     *', $verbosity);
        static::comment(Str::repeat('*', $length), $verbosity);

        static::comment('', $verbosity);
    }

    /**
     * Write a blank line.
     *
     * @param  int  $count
     *
     * @return static
     */
    public static function newLine(int $count = 1): static
    {
        static::getOutput()->newLine($count);

        return new static;
    }

    /**
     * Set the input interface implementation.
     *
     * @param  InputInterface  $input
     *
     * @return void
     */
    public static function setInput(InputInterface $input)
    {
        static::$input = $input;
    }

    /**
     * Set the output interface implementation.
     *
     * @param  OutputInterface  $output
     *
     * @return void
     */
    public static function setOutput(OutputInterface $output): void
    {
        static::$output = $output;
    }

    /**
     * Get the input implementation.
     *
     * @return InputInterface
     */
    public static function getInput(): InputInterface
    {
        return static::$input;
    }

    /**
     * Get the output implementation.
     *
     * @return SymfonyStyle
     */
    public static function getOutput(): OutputInterface
    {
        return static::$output;
    }

    /**
     * Set the verbosity level.
     *
     * @param  string|int  $level
     *
     * @return void
     */
    protected static function setVerbosity($level)
    {
        static::$verbosity = static::parseVerbosity($level);
    }

    /**
     * Get the verbosity level in terms of Symfony's OutputInterface level.
     *
     * @param  string|int|null  $level
     *
     * @return int
     */
    protected static function parseVerbosity($level = null)
    {
        if (isset(static::$verbosityMap[$level])) {
            $level = static::$verbosityMap[$level];
        } elseif (! is_int($level)) {
            $level = static::$verbosity;
        }

        return $level;
    }

    /**
     * Clears the console screen.
     *
     * This method clears the terminal screen, giving the user a fresh view of the current status.
     *
     * @return void
     */
    protected static function clearConsole(): void
    {
        // For Unix-like systems (Linux, macOS)
        if (PHP_OS_FAMILY === 'Unix') {
            static::line("\033[2J\033[0;0H");
        }
        // For Windows systems
        elseif (PHP_OS_FAMILY === 'Windows') {
            system('cls');
        }
    }
}
