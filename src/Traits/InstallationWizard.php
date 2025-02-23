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

namespace Maginium\Installer\Traits;

use Illuminate\Support\Str;
use Maginium\Installer\Concerns\InteractWithContainer;
use Maginium\Installer\Enums\Commands;
use Maginium\Installer\Enums\ConfigTags;
use Maginium\Installer\Enums\ConfigTypes;
use Maginium\Installer\Helpers\ConfigurationRegistry;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\form;
use function Laravel\Prompts\password;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

/**
 * Trait InstallationWizard.
 *
 * This trait contains a set of methods that prompt the user for various installation configuration options
 * during the installation process. Each method corresponds to a specific area of the installation, such as
 * logging, backend configuration, database settings, and more.
 */
trait InstallationWizard
{
    use InteractWithContainer;

    /**
     * Run the full installation process by gathering all configuration questions.
     *
     * This method orchestrates the entire installation process, gathering all necessary configuration settings.
     * It triggers the creation of different types of configuration questions such as logging, admin user settings, etc.
     *
     * @return void
     */
    public function startSetupPrompts(): void
    {
        // Define the configuration types and their respective prompt methods
        $configSections = [
            ConfigTypes::GENERAL => 'promptForGeneral',
            ConfigTypes::STORE => 'promptForStore',
            ConfigTypes::DATABASE => 'promptForDatabase',
            ConfigTypes::CACHE => 'promptForCache',
            ConfigTypes::AMQP => 'promptForAmqp',
            ConfigTypes::MODULES => 'promptForBackend',
            ConfigTypes::OPENSEARCH => 'promptForOpenSearch',
            ConfigTypes::ADMIN_USER => 'promptForAdminUser',
        ];

        // Iterate through each section and execute the prompts
        foreach ($configSections as $section => $method) {
            // Generate section title and description
            $this->generateSectionPrompt($section);

            // call the function to prompting
            $this->{$method}();

            // Clear the console after each section prompt
            $this->clearConsole();
        }
    }

    /**
     * Prepares the installation command for Magento setup.
     *
     * This method builds the Magento setup installation command by collecting
     * configuration options and appending them as command-line arguments.
     *
     * @return array The full installation command for Magento setup.
     */
    protected function getInstallationCommand(): array
    {
        // Retrieve Magento configuration options filtered by the 'magento' tag
        $magentoOptions = $this->getConfigurationRegistry()->getByTag(ConfigTags::MAGENTO);

        // Initialize an array to hold the configuration values
        $config = [];

        // Loop through the options and fetch each configuration value
        foreach ($magentoOptions as $type => $option) {
            foreach ($option as $key => $optionArray) {
                // Get the option value for each key
                $config[$key] = $this->option($key);
            }
        }

        // Initialize an empty string to hold command-line arguments
        $args = '';

        // Build the command-line arguments string
        foreach ($config as $key => $value) {
            $args .= " --{$key}={$value}";
        }

        // Combine the Magento setup command with the arguments
        $command = [
            php_binary(),
            magento_binary(),
            Commands::SETUP_INSTALL,
            $args,
        ];

        // Return the final command string
        return $command;
    }

    /**
     * Prompt the user for the project name if it is not already provided.
     *
     * @return void
     */
    protected function promptForProjectName()
    {
        // Check if the project name has not been provided
        if (! $this->argument(ConfigurationRegistry::NAME)) {
            // If not, prompt the user for a project name with validation
            static::getInput()->setArgument(ConfigurationRegistry::NAME, text(
                label: 'What is the name of your project?',
                placeholder: 'E.g. example-app',
                required: 'The project name is required.',
                validate: function($value) {
                    // Validate the project name format
                    if (preg_match('/[^\pL\pN\-_.]/', $value) !== 0) {
                        return 'The name may only contain letters, numbers, dashes, underscores, and periods.';
                    }
                },
            ));
        }
    }

    /**
     * Create backend configuration questions.
     *
     * Prompts the user for backend-related settings such as frontname and whether to enable async admin config saving.
     * The values are stored as options for later retrieval using `getOption`.
     *
     * @return void
     */
    private function promptForBackend(): void
    {
        // Prompt user to set the backend frontname. Default: 'backend'
        $backendFrontname = text('Backend frontname (will be autogenerated if missing)?', default: 'backend', hint: 'Define the frontname for your backend interface.');

        // Prompt user to enable async admin config saving. Default: 'Yes'
        $asyncAdminConfigSave = select('Enable async Admin Config Save?', [
            '1' => 'Yes', // Option 1: Yes
            '0' => 'No', // Option 0: No
        ], default: '1', hint: 'Choose whether to enable asynchronous saving of admin configurations.');

        // Set the collected values as options so they can be retrieved later with `getOption`

        // Store the frontend name for the backend
        static::getInput()->setOption('backend-frontname', $backendFrontname);

        // Store the async configuration setting
        static::getInput()->setOption('config-async', $asyncAdminConfigSave);
    }

    /**
     * Create AMQP (Advanced Message Queuing Protocol) configuration questions.
     *
     * This method prompts the user to answer various questions related to the AMQP system configuration.
     * These questions cover aspects like enabling AMQP, server host, port, authentication details, and SSL options.
     * The gathered answers are then set as options in the input object for later retrieval.
     *
     * @return void
     */
    private function promptForAmqp(): void
    {
        // Prompt the user to decide whether to enable AMQP messaging (default is false)
        $amqpEnabled = confirm(
            'Amqp Enabled?',
            default: false,
            hint: 'Enable or disable AMQP messaging system.',
        );

        // Only proceed with the additional configuration if AMQP is enabled
        if ($amqpEnabled) {
            // Prompt for the AMQP server host, with a default and placeholder for clarity
            $amqpHost = text(
                'Amqp server host?',
                default: '127.0.0.1',
                placeholder: 'Enter AMQP server host',
            );

            // Prompt for the AMQP server port, with a default value of '5672' (common AMQP port)
            $amqpPort = text(
                'Amqp server port?',
                default: '5672',
                placeholder: 'Enter AMQP server port',
            );

            // Prompt for the AMQP server username, defaulting to 'guest' as a common username
            $amqpUsername = text(
                'Amqp server username?',
                default: 'guest',
                placeholder: 'Enter AMQP server username',
            );

            // Prompt for the AMQP server password, with validation (default 'guest' as a placeholder)
            $amqpPassword = password(
                'Amqp server password?',
                placeholder: 'Enter AMQP server password',
                validate: fn(string $value) => match (true) {
                    empty($value) => 'The password cannot be empty.',

                    default => null,  // Default password for the AMQP server
                },
            );

            // Prompt for the AMQP virtual host (default is '/')
            $amqpVhost = text(
                'Amqp virtualhost?',
                default: '/',
                placeholder: 'Enter AMQP virtual host',
            );

            // Prompt to enable or disable SSL for AMQP connections (default is false)
            $amqpSsl = confirm(
                'Amqp SSL?',
                default: false,
                hint: 'Enable or disable SSL for secure AMQP connections.',
            );

            // Ask whether consumers should wait for a message from the queue (default is 'Yes')
            $consumerWaitForMessage = select(
                'Should consumers wait for a message from the queue?',
                [
                    '1' => 'Yes',  // Consumer will wait for messages
                    '0' => 'No',   // Consumer will not wait for messages
                ],
                default: '1',
                hint: 'Specify whether consumers should wait for messages from the queue.',
            );

            // Allow the user to choose the default connection type for message queues (options: DB, AMQP, or custom)
            $amqpQueueConnection = select(
                'Message queues default connection. Can be "db", "amqp" or a custom queue system.',
                [
                    'db' => 'DB',  // Option for DB connection
                    'amqp' => 'AMQP',  // Option for AMQP connection
                    'custom' => 'Custom Queue System',  // Option for a custom queue
                ],
                default: 'amqp',  // Default selection is 'amqp'
                hint: 'Choose the default connection for message queues.',
            );

            // Store the collected AMQP configuration options in the input object for later use
            static::getInput()->setOption('amqp-host', $amqpHost);
            static::getInput()->setOption('amqp-port', $amqpPort);
            static::getInput()->setOption('amqp-user', $amqpUsername);
            static::getInput()->setOption('amqp-password', $amqpPassword ?? 'guest');
            static::getInput()->setOption('amqp-virtualhost', $amqpVhost);
            static::getInput()->setOption('amqp-ssl', $amqpSsl);
            static::getInput()->setOption('queue-default-connection', $amqpQueueConnection);

            // Additionally store the 'wait for message' option for consumers
            static::getInput()->setOption('consumers-wait-for-messages', $consumerWaitForMessage);
        }
    }

    /**
     * Create database server configuration questions.
     *
     * Prompts the user for database server connection settings, including host, name, user, engine, password, and prefix.
     *
     * @return void
     */
    private function promptForDatabase(): void
    {
        // Ask for the database server host
        $dbHost = text(
            label: 'Database server host?',
            default: '127.0.0.1', // Default database server host
            placeholder: 'Enter database server host',
            hint: 'Host of the database server',
        );

        // Save the database host option
        static::getInput()->setOption('db-host', $dbHost);

        // Ask for the database name
        $dbName = text(
            label: 'Database name?',
            default: 'maginium', // Default database name
            placeholder: 'Enter the database name',
            hint: 'Name of the database',
        );

        // Save the database name option
        static::getInput()->setOption('db-name', $dbName);

        // Ask for the database username
        $dbUser = text(
            label: 'Database server username?',
            default: 'maginium', // Default database username
            placeholder: 'Enter the database username',
            hint: 'Username for the database connection',
        );

        // Save the database username option
        static::getInput()->setOption('db-user', $dbUser);

        // Ask for the database engine
        $dbEngine = text(
            label: 'Database server engine?',
            default: 'innodb', // Default database engine is InnoDB
            placeholder: 'Enter the database engine',
            hint: 'Engine for the database (e.g., InnoDB)',
        );

        // Save the database engine option
        static::getInput()->setOption('db-engine', $dbEngine);

        // Ask for the database password
        $dbPassword = text(
            label: 'Database server password?',
            default: 'maginium', // Default database password
            placeholder: 'Enter the database password',
            hint: 'Password for the database connection',
        );

        // Save the database password option
        static::getInput()->setOption('db-password', $dbPassword);

        // Ask for the database table prefix
        $dbPrefix = text(
            label: 'Database table prefix?',
            default: '', // Default is no prefix
            placeholder: 'Enter table prefix (optional)',
            hint: 'Prefix for database tables',
        );

        // Save the database prefix option
        static::getInput()->setOption('db-prefix', $dbPrefix);

        // Ask for the optional database model (default is mysql4)
        $dbModel = text(
            label: 'Database model (optional, default: mysql4)?',
            default: 'mysql4', // Default database model is mysql4
            placeholder: 'Enter the database model',
            hint: 'Model for the database configuration',
        );

        // Save the database model option
        static::getInput()->setOption('db-model', $dbModel);

        // Ask whether to skip database validation during setup (default: false)
        $skipDbValidation = confirm(
            'Skip database validation during setup?',
            default: false,
            hint: 'Choose whether to skip database validation.',
        );

        // Save the database skip validation option
        static::getInput()->setOption('skip-db-validation', $skipDbValidation);

        // Prompt for enabling database cleanup, defaulting to false (no cleanup).
        // This allows the user to choose whether to enable automatic database cleanup.
        $cleanupDatabase = confirm(
            'Enable database cleanup?',
            default: false,
            hint: 'Choose whether to enable automatic cleanup of the database',
        );

        // Store the cleanup option.
        static::getInput()->setOption('cleanup-database', $cleanupDatabase);
    }

    /**
     * Create OpenSearch configuration questions.
     *
     * This method prompts the user for OpenSearch-related settings such as host, port, authentication,
     * index prefix, and timeout settings. The collected values are saved as options to be used later
     * in the application via `getOption`.
     *
     * @return void
     */
    private function promptForOpenSearch(): void
    {
        // Prompt for the OpenSearch host, defaulting to 'localhost' if not provided.
        // The user is asked to specify the host for the OpenSearch server.
        $opensearchHost = text(
            'OpenSearch host?',
            default: 'localhost',
            hint: 'Enter the OpenSearch host.',
            placeholder: 'e.g., localhost',
        );

        // Store the provided host option.
        static::getInput()->setOption('opensearch-host', $opensearchHost);

        // Prompt for the OpenSearch port, defaulting to '9200' (default OpenSearch port).
        // The user is asked to specify the port on which OpenSearch is running.
        $opensearchPort = text(
            'OpenSearch port?',
            default: '9200',
            hint: 'Enter the OpenSearch port.',
            placeholder: 'e.g., 9200',
        );

        // Store the port option.
        static::getInput()->setOption('opensearch-port', $opensearchPort);

        // Prompt for enabling authentication for OpenSearch, defaulting to false (no authentication).
        // If enabled, the system will prompt for username and password.
        $opensearchEnableAuth = confirm(
            'Enable authentication for OpenSearch?',
            default: false,
            hint: 'Enable authentication for connecting to OpenSearch.',
        );

        // Store authentication status option.
        static::getInput()->setOption('opensearch-enable-auth', $opensearchEnableAuth);

        // If authentication is enabled, prompt for the OpenSearch username and password.
        if ($opensearchEnableAuth) {
            // Ask for the username used for OpenSearch authentication, defaulting to 'admin'.
            $opensearchUsername = text(
                'OpenSearch username?',
                default: 'admin',
                hint: 'Enter the username for OpenSearch authentication.',
                placeholder: 'e.g., admin',
            );

            // Store the username option.
            static::getInput()->setOption('opensearch-username', $opensearchUsername);

            // Ask for the password used for OpenSearch authentication, with no default value.
            $opensearchPassword = password(
                'OpenSearch password?',
                hint: 'Enter the password for OpenSearch authentication.',
                placeholder: 'Password',
            );

            // Store the password option.
            static::getInput()->setOption('opensearch-password', $opensearchPassword);
        }

        // Prompt for the OpenSearch index prefix, defaulting to 'my_index_' for index names.
        // This is used to identify the indexes created for this application.
        $opensearchIndexPrefix = text(
            'OpenSearch index prefix?',
            default: 'my_index_',
            hint: 'Enter the prefix for OpenSearch indices.',
            placeholder: 'e.g., my_index_',
        );

        // Store the index prefix option.
        static::getInput()->setOption('opensearch-index-prefix', $opensearchIndexPrefix);

        // Prompt for the OpenSearch timeout value, defaulting to '30' seconds.
        // This defines how long the system should wait for a response before timing out.
        $opensearchTimeout = text(
            'OpenSearch timeout in seconds?',
            default: '30',
            hint: 'Enter the timeout value for OpenSearch (in seconds).',
            placeholder: 'e.g., 30',
        );

        // Store the timeout option.
        static::getInput()->setOption('opensearch-timeout', $opensearchTimeout);
    }

    /**
     * Create general configuration questions.
     *
     * This method prompts the user for various general configuration options such as sales order
     * increment prefix, database cleanup, module enable/disable settings, and secure
     * login settings for the admin panel.
     *
     * @return void
     */
    private function promptForGeneral(): void
    {
        // Boolean question: 'Yes' or 'No'
        $debugLogging = confirm('Enable debug logging?', true);

        // Option for enabling/disabling debug logging
        static::getInput()->setOption('enable-debug-logging', (bool)$debugLogging);

        // Boolean question: 'Yes' or 'No'
        $syslogLogging = confirm('Enable syslog logging?', true);

        // Option for enabling/disabling syslog logging
        static::getInput()->setOption('enable-syslog-logging', (bool)$syslogLogging);

        // Prompt for the sales order increment prefix, defaulting to 'SO-'.
        // This is used for creating unique sales order numbers.
        $salesOrderIncrementPrefix = text(
            'Sales order increment prefix?',
            default: 'SO-',
            hint: 'The prefix used for sales order numbers',
            placeholder: 'e.g., SO-',
        );

        // Store the prefix option.
        static::getInput()->setOption('sales-order-increment-prefix', $salesOrderIncrementPrefix);

        // Prompt for enabling sample data, defaulting to false (no sample data).
        // Sample data is often used for testing purposes.
        $useSampleData = confirm(
            'Enable sample data?',
            default: false,
            hint: 'Enable sample data for Magento (useful for testing)',
        );

        // Store the sample data option.
        static::getInput()->setOption('use-sample-data', $useSampleData);

        // Prompt for enabling modules, where the user can input a comma-separated list of modules.
        // These modules will be enabled during the configuration.
        $enableModules = text(
            'Modules to enable (comma separated)?',
            default: '',
            hint: 'Comma-separated list of modules to enable',
            placeholder: 'e.g., Module1, Module2',
        );

        // Store the list of enabled modules.
        static::getInput()->setOption('enable-modules', $enableModules);

        // Prompt for disabling modules, where the user can input a comma-separated list of modules to disable.
        $disableModules = text(
            'Modules to disable (comma separated)?',
            default: '',
            hint: 'Comma-separated list of modules to disable',
            placeholder: 'e.g., Module3, Module4',
        );

        // Store the list of disabled modules.
        static::getInput()->setOption('disable-modules', $disableModules);

        // Prompt for whether the document root is in the 'pub' directory, defaulting to true.
        // This is used to configure the document root for the web server.
        $documentRootIsPub = confirm(
            'Is the document root in the pub directory?',
            default: true,
            hint: 'Specify whether your document root is inside the pub folder',
        );

        // Store the document root option.
        static::getInput()->setOption('document-root-is-pub', $documentRootIsPub);

        // Prompt for enabling secure admin login, defaulting to true (secure login enabled).
        // This ensures that the admin panel is accessed over HTTPS for security.
        $useSecureAdmin = confirm(
            'Enable secure admin login?',
            default: true,
            hint: 'Enable secure login for the admin panel',
        );

        // Store the secure admin option.
        static::getInput()->setOption('use-secure-admin', $useSecureAdmin);

        // Prompt for enabling an admin security key, defaulting to false (no security key).
        // This adds an additional layer of security for the admin login.
        $adminUseSecurityKey = confirm(
            'Enable admin security key?',
            default: false,
            hint: 'Enable an additional security key for the admin panel',
        );

        // Store the security key option.
        static::getInput()->setOption('admin-use-security-key', $adminUseSecurityKey);
    }

    /**
     * Create store configuration questions.
     *
     * Prompts the user for store details such as URL, language code, time zone, and currency code.
     * The provided values will be set as options, so they can be accessed later using `getOption`.
     *
     * @return void
     */
    private function promptForStore(): void
    {
        // Prompt user for store URL (default: 'http://localhost/')
        $storeUrl = text(
            label: 'Store URL?', // The prompt for the user
            placeholder: 'Enter store URL', // A placeholder to show the user what to input
            default: 'http://localhost/', // Default value in case the user doesn't provide any input
            hint: 'The base URL for the store. E.g., http://localhost/ or https://example.com/', // Additional hint to guide the user
        );

        // Store base URL option
        static::getInput()->setOption('base-url', $storeUrl);

        // Prompt user for default language code (default: 'en_US')
        $languageCode = search(
            label: 'Select Default language code',
            placeholder: 'Enter language code', // Placeholder text to guide the user in providing the language code.
            options: function(string $value) {
                return $this->getLanguageHelper()
                    ->filter(function($language) use ($value) {
                        // Filter languages by name containing the search term (case-insensitive).
                        return Str::contains($language[ConfigurationRegistry::NAME], $value, ignoreCase: true);
                    })
                    ->values() // Re-index the filtered results.
                    ->pluck(ConfigurationRegistry::NAME) // Extract only the language names.
                    ->all(); // Return the results as an array.
            },
            hint: 'Specify the language code for the store, e.g., en_US for English.', // Hint on how the language code should be formatted.
        );

        // Store default time zone option
        static::getInput()->setOption('timezone', $languageCode);

        // Prompt user for default time zone (default: 'UTC')
        $timezone = search(
            label: 'Select Default time zone',
            placeholder: 'Enter time zone', // A placeholder to show the user what to input.
            options: function(string $value) {
                return $this->getTimezoneHelper()
                    ->filter(function($timezone) use ($value) {
                        // Filter timezones by name containing the search term (case-insensitive).
                        return Str::contains($timezone[ConfigurationRegistry::NAME], $value, ignoreCase: true);
                    })
                    ->values() // Re-index the filtered results.
                    ->pluck(ConfigurationRegistry::NAME) // Extract only the timezone names.
                    ->all(); // Return the results as an array.
            },
            hint: 'Specify the default time zone for the store, e.g., UTC or GMT.', // Hint to explain the format.
        );

        // Store default language code option
        static::getInput()->setOption('language', $timezone);

        // Prompt user for default currency code (default: 'USD')
        $currencyCode = search(
            label: 'Select Default currency code',
            placeholder: 'Enter currency code', // A placeholder to show the user what to input.
            options: function(string $value) {
                return $this->getCurrencyHelper()
                    ->filter(function($currency) use ($value) {
                        // Filter currencies by name containing the search term (case-insensitive).
                        return Str::contains($currency[ConfigurationRegistry::NAME], $value, ignoreCase: true);
                    })
                    ->values() // Re-index the filtered results.
                    ->pluck(ConfigurationRegistry::NAME) // Extract only the currency names.
                    ->all(); // Return the results as an array.
            },
            hint: 'Currency code for the store, e.g., USD for US dollars.', // Hint to guide the user.
        );

        // Store default currency code option
        static::getInput()->setOption('currency', $currencyCode);
    }

    /**
     * Create admin user configuration questions.
     *
     * Prompts the user for admin user details such as username, password, email, first name, and last name.
     * The provided values will be set as options, so they can be accessed later using `getOption`.
     *
     * @return void
     */
    private function promptForAdminUser()
    {
        // Create a form instance
        $form = form();

        // Prompt for Admin Username
        $form->text(
            label: 'Admin user?', // Prompt for the admin username
            placeholder: 'Enter admin username', // Placeholder for the username input
            required: true, // Mark the username as a required field
            name: 'admin_user', // Field name for the username
            default: 'admin', // Default value for the username
            hint: 'The username for the admin user.', // Hint for the admin username field
        );

        // Prompt for Admin Password with validation
        $form->password(
            label: 'Admin password?', // Prompt for the admin password
            placeholder: 'Enter admin password', // Placeholder for the password input
            validate: fn(string $value): ?string => match (true) {
                mb_strlen($value) < 8 => 'The password must be at least 8 characters.', // Validation rule for password length
                default => null, // No error if the password is valid
            },
            name: 'admin_password', // Field name for the password
            hint: 'Minimum 8 characters required for the password.', // Hint for the password input
        );

        // Prompt for Admin Email
        $form->text(
            label: 'Admin email?', // Prompt for the admin email address
            placeholder: 'Enter admin email address', // Placeholder for the email input
            required: true, // Mark the email as a required field
            name: 'admin_email', // Field name for the email
            default: 'admin@admin.com', // Default email address
            hint: 'The email address for the admin user.', // Hint for the email input
        );

        // Prompt for Admin First Name
        $form->text(
            label: 'Admin first name?', // Prompt for the admin's first name
            placeholder: 'Enter admin first name', // Placeholder for the first name input
            required: true, // Mark the first name as required
            name: 'admin_firstname', // Field name for the first name
            default: 'admin', // Default first name
            hint: 'First name of the admin user.', // Hint for the first name field
        );

        // Prompt for Admin Last Name
        $form->text(
            label: 'Admin last name?', // Prompt for the admin's last name
            placeholder: 'Enter admin last name', // Placeholder for the last name input
            required: true, // Mark the last name as required
            name: 'admin_lastname', // Field name for the last name
            default: 'admin', // Default last name
            hint: 'Last name of the admin user.', // Hint for the last name input
        );

        // Submit the form and collect responses
        $response = $form->submit();

        // Store the collected values as options for later access
        static::getInput()->setOption('admin-user', $response['admin_user']);
        static::getInput()->setOption('admin-email', $response['admin_email']);
        static::getInput()->setOption('admin-password', $response['admin_password']);
        static::getInput()->setOption('admin-lastname', $response['admin_lastname']);
        static::getInput()->setOption('admin-firstname', $response['admin_firstname']);
    }

    /**
     * Create cache and locking mechanism configuration questions.
     *
     * Prompts the user for various cache backend, page cache, locking mechanism, and additional cache settings.
     *
     * @return void
     */
    private function promptForCache(): void
    {
        // Cache Backend Configuration: Select the cache backend (Redis, Memcached, Database, or File)
        $cacheBackend = select(
            label: 'Select cache type',
            options: [
                'redis' => 'Redis',  // Option to use Redis for caching
                'memcached' => 'Memcached',  // Option to use Memcached for caching
                'mongo' => 'MongoDB', // Option to use Database for caching
            ],
            default: 'redis',   // Default backend is Redis
            hint: 'Select the type of cache backend you want to use.',
        );

        // Store selected cache backend option
        static::getInput()->setOption('cache-backend', $cacheBackend);

        match ($cacheBackend) {
            'redis' => $this->promptForRedis(),
            'memcached' => $this->promptForMemcached(),
            'mongo' => $this->promptForMongo(),
        };

        // Additional Cache Settings: Define HTTP cache hosts and cache ID prefix
        $httpCacheHosts = text(
            label: 'HTTP cache hosts?',
            placeholder: 'Enter HTTP cache hosts',
            default: '127.0.0.1',   // Default HTTP cache host address
            hint: 'Specify the HTTP hosts for caching content.',
        );

        // Store HTTP cache hosts
        static::getInput()->setOption('http-cache-hosts', $httpCacheHosts);

        $cacheIdPrefix = text(
            label: 'Cache ID prefix?',
            placeholder: 'Enter cache ID prefix',
            default: 'cache_', // Default prefix for cache IDs
            hint: 'Prefix used for cache identifiers.',
        );

        // Store cache ID prefix
        static::getInput()->setOption('cache-id-prefix', $cacheIdPrefix);

        // $this->promptForCacheLock();
        // $this->promptForPageCache();
        // $this->promptForSession();
        // $this->promptForBackpressure();
    }

    /**
     * Prompts the user for Redis connection details and cache settings.
     * This method gathers configuration values for the Redis server, database index,
     * port, password, cache ID prefix, and parallel cache generation option.
     *
     * @return void
     */
    private function promptForRedis(): void
    {
        // Prompt for the Redis server address. Default is '127.0.0.1' for local Redis server.
        // The user can specify a different server address if needed.
        $redisServer = text(
            label: 'Redis server',
            placeholder: 'Enter Redis server address',
            default: '127.0.0.1', // Default Redis server address for cache
            hint: 'Specify the Redis server address for cache management.',
        );

        // Store the Redis server address to be used in the configuration.
        static::getInput()->setOption('cache-backend-redis-server', $redisServer);

        // Prompt for the Redis database index. Default is '0', which is the typical default for Redis.
        // The index is used to target a specific Redis database for caching purposes.
        $redisDb = text(
            label: 'Redis database index',
            placeholder: 'Enter Redis database index',
            default: '0',   // Default Redis database index for cache is 0
            hint: 'The Redis database index for caching. Default is 0.',
        );

        // Store the Redis database index for cache to be used in the configuration.
        static::getInput()->setOption('cache-backend-redis-db', $redisDb);

        // Prompt for the Redis port. Default is '6379', which is the standard Redis port.
        // The user can modify this if their Redis instance is running on a different port.
        $redisPort = text(
            label: 'Redis port',
            placeholder: 'Enter Redis port',
            default: '6379', // Default Redis port is 6379
            hint: 'The port number for Redis. Default is 6379.',
        );

        // Store the Redis port for cache to be used in the configuration.
        static::getInput()->setOption('cache-backend-redis-port', $redisPort);

        // Prompt for the Redis password. This is optional; the field can be left blank if no password is set.
        // It's important to note that Redis requires a password if configured with one.
        $redisPassword = password(
            label: 'Redis password',
            placeholder: 'Enter Redis password',
            hint: 'Leave empty if no password is required.',
        );

        // Store the Redis password for cache backend to be used in the configuration.
        static::getInput()->setOption('cache-backend-redis-password', $redisPassword);

        // Prompt for a cache ID prefix. The default value is 'cache_' which is used to prefix cache keys.
        // The user can customize this prefix if necessary to organize and distinguish different caches.
        $cacheIdPrefix = text(
            'Cache ID prefix?',
            default: 'cache_',
            hint: 'The prefix for the caching system',
            placeholder: 'e.g., cache_',
        );

        // Store the cache ID prefix to be used for cache keys in the configuration.
        static::getInput()->setOption('cache-id-prefix', $cacheIdPrefix);

        // Prompt for allowing parallel cache generation. Default is false, meaning parallel generation is disabled.
        // Enabling parallel cache generation can speed up cache processing by running multiple processes concurrently.
        $allowParallelGeneration = confirm(
            'Allow parallel cache generation?',
            default: false,
            hint: 'Enable parallel cache generation for faster processing',
        );

        // Store the parallel cache generation setting in the configuration.
        static::getInput()->setOption('allow-parallel-generation', $allowParallelGeneration);
    }

    /**
     * Create session storage configuration questions.
     *
     * Prompts the user for various session storage options such as session save mechanism,
     * Redis configuration, and session timeout settings.
     *
     * @return void
     */
    private function promptForSession(): void
    {
        // Ask the user to select the session save mechanism.
        // Options include file-based, Redis-based, or database-based sessions.
        $sessionSave = select(
            label: 'Select session save mechanism',
            options: [
                'files' => 'File-based sessions', // Option for file-based session storage
                'redis' => 'Redis-based sessions', // Option for Redis-based session storage
                'database' => 'Database-based sessions', // Option for database-based session storage
            ],
            default: 'files', // Default choice is file-based
            hint: 'Choose the method for saving sessions (files, redis, or database)', // Helpful hint for the user
        );

        // Save the selected session save mechanism in the input options
        static::getInput()->setOption('session-save', $sessionSave);

        // If the user selected Redis for session storage, prompt for Redis-specific configuration options.
        if ($sessionSave === 'redis') {
            // Ask for the Redis host address for session storage.
            $redisHost = text(
                label: 'Redis host for session storage?',
                default: '127.0.0.1', // Default Redis host address is localhost
                placeholder: 'Enter the Redis server host', // Placeholder text in the input field
                hint: 'Redis server host address', // Hint to guide the user
            );

            // Save the Redis host option
            static::getInput()->setOption('session-save-redis-host', $redisHost);

            // Ask for the Redis port for session storage.
            $redisPort = text(
                label: 'Redis port for session storage?',
                default: '6379', // Default Redis port is 6379
                placeholder: 'Enter the Redis server port',
                hint: 'Redis server port',
            );

            // Save the Redis port option
            static::getInput()->setOption('session-save-redis-port', $redisPort);

            // Ask for the Redis password for authentication (if required).
            $redisPassword = password(
                label: 'Redis password for session storage?',
                placeholder: 'Enter the Redis password',
                hint: 'Password for Redis authentication',
            );

            // Save the Redis password option
            static::getInput()->setOption('session-save-redis-password', $redisPassword);

            // Ask for the Redis timeout for session storage in seconds.
            $redisTimeout = text(
                label: 'Redis timeout for session storage?',
                default: '30', // Default timeout value is 30 seconds
                placeholder: 'Enter Redis timeout in seconds',
                hint: 'Timeout for Redis connection',
            );

            // Save the Redis timeout option
            static::getInput()->setOption('session-save-redis-timeout', $redisTimeout);

            // Ask for the Redis retry count for session storage.
            $redisRetries = text(
                label: 'Redis retries for session storage?',
                default: '5', // Default retry count is 5
                placeholder: 'Enter Redis retry count',
                hint: 'Number of retries for Redis connection',
            );

            // Save the Redis retry count option
            static::getInput()->setOption('session-save-redis-retries', $redisRetries);

            // Ask for the Redis persistent ID (if any) for session storage.
            $redisPersistentId = text(
                label: 'Redis persistent ID for session storage?',
                default: '', // Default persistent ID is empty
                placeholder: 'Enter Redis persistent ID',
                hint: 'Redis persistent ID (if any)',
            );

            // Save the Redis persistent ID option
            static::getInput()->setOption('session-save-redis-persistent-id', $redisPersistentId);

            // Ask for the Redis database index for session storage.
            $redisDb = text(
                label: 'Redis database index for session storage?',
                default: '2', // Default Redis database index is 0
                placeholder: 'Enter Redis database index',
                hint: 'Index of the Redis database to use',
            );

            // Save the Redis database index option
            static::getInput()->setOption('session-save-redis-db', $redisDb);

            // Ask for the Redis compression threshold for session storage (in bytes).
            $redisCompressionThreshold = text(
                label: 'Redis compression threshold for session storage?',
                default: '1024', // Default compression threshold is 1024 bytes
                placeholder: 'Enter Redis compression threshold',
                hint: 'Threshold for data compression in Redis',
            );

            // Save the Redis compression threshold option
            static::getInput()->setOption('session-save-redis-compression-threshold', $redisCompressionThreshold);

            // Ask for the Redis compression library to use for session storage.
            $redisCompressionLib = text(
                label: 'Redis compression library for session storage?',
                default: 'zlib', // Default compression library is zlib
                placeholder: 'Enter Redis compression library',
                hint: 'Library to use for compression',
            );

            // Save the Redis compression library option
            static::getInput()->setOption('session-save-redis-compression-lib', $redisCompressionLib);

            // Ask for the Redis logging level for session storage.
            $redisLogLevel = select(
                label: 'Redis logging level for session storage',
                options: [
                    'debug' => 'Debug', // Debug logging level
                    'info' => 'Info', // Info logging level
                    'warn' => 'Warn', // Warn logging level
                    'error' => 'Error', // Error logging level
                ],
                default: 'info', // Default logging level is info
                hint: 'Set the logging level for Redis',
            );

            // Save the Redis logging level option
            static::getInput()->setOption('session-save-redis-log-level', $redisLogLevel);

            // Ask for the maximum concurrency for Redis session storage operations.
            $redisMaxConcurrency = text(
                label: 'Redis max concurrency for session storage?',
                default: '5', // Default max concurrency is 5
                placeholder: 'Enter max concurrency',
                hint: 'Max concurrency for Redis operations',
            );

            // Save the Redis max concurrency option
            static::getInput()->setOption('session-save-redis-max-concurrency', $redisMaxConcurrency);

            // Ask if Redis session locking should be disabled.
            $redisDisableLocking = confirm(
                label: 'Disable Redis session locking?',
                default: false, // Default is not to disable locking
                hint: 'Disable Redis session locking for concurrency management',
            );

            // Save the Redis session locking option
            static::getInput()->setOption('session-save-redis-disable-locking', $redisDisableLocking);
        }
    }

    /**
     * Create backpressure logger configuration questions.
     *
     * Prompts the user for various backpressure logger settings, including Redis server and connection details.
     *
     * @return void
     */
    private function promptForBackpressure(): void
    {
        // Backpressure Logger Configuration: Choose the logging mechanism for backpressure management
        $backpressureLogger = select(
            label: 'Select backpressure logger type',
            options: [
                'redis' => 'Redis',  // Option to use Redis for backpressure logging
                'file' => 'File-based logging',  // Option for file-based logging
                'database' => 'Database logging', // Option for database-based logging
            ],
            default: 'redis', // Default selection is Redis
            hint: 'Choose a logging mechanism for backpressure management.',
        );

        // Store the user's selection
        static::getInput()->setOption('backpressure-logger', $backpressureLogger);

        // If Redis is selected for backpressure logger, prompt for Redis connection details
        if ($backpressureLogger === 'redis') {
            $redisServer = text(
                label: 'Redis server for backpressure logging?',
                placeholder: 'Enter Redis server address',
                default: '127.0.0.1',  // Default Redis server address
                hint: 'The address of the Redis server. Leave as default for local Redis.',
            );

            // Store Redis server address
            static::getInput()->setOption('backpressure-logger-redis-server', $redisServer);

            $redisPort = text(
                label: 'Redis port for backpressure logging?',
                placeholder: 'Enter Redis port',
                default: '6379',   // Default Redis port
                hint: 'The port number Redis is running on. Default is 6379.',
            );

            // Store Redis port
            static::getInput()->setOption('backpressure-logger-redis-port', $redisPort);

            $redisTimeout = text(
                label: 'Timeout for Redis backpressure logger?',
                placeholder: 'Enter Redis timeout',
                default: '30', // Default timeout of 30 seconds
                hint: 'Specify the timeout duration for Redis connections in seconds.',
            );

            // Store Redis timeout value
            static::getInput()->setOption('backpressure-logger-redis-timeout', $redisTimeout);

            $redisPersistent = confirm(
                label: 'Enable persistent Redis connection for backpressure logging?',
                default: true,// Default value is true, meaning persistent connection is enabled
                hint: 'Keep Redis connection persistent to improve performance.',
            );

            // Store persistent connection setting
            static::getInput()->setOption('backpressure-logger-redis-persistent', $redisPersistent);

            $redisDb = text(
                label: 'Redis database index for backpressure logging?',
                placeholder: 'Enter Redis database index',
                default: '3', // Default Redis database index is 0
                hint: 'The index of the Redis database to use. Default is 0.',
            );
            static::getInput()->setOption('backpressure-logger-redis-db', $redisDb); // Store Redis database index
        }
    }

    /**
     * Prompts the user to configure the page caching mechanism.
     *
     * This method allows the user to enable or disable page caching, and if enabled,
     * it prompts for Redis configuration settings specific to managing the page cache.
     *
     * @return void
     */
    private function promptForPageCache(): void
    {
        // Step 1: Enable or Disable Page Cache
        // Prompt the user to enable or disable the page caching system.
        // Enabling page caching can improve response times by serving cached pages.
        $pageCacheEnabled = confirm(
            label: 'Enable page cache?',
            default: true, // Default is true, meaning page caching is enabled.
            hint: 'Enable to cache entire pages for faster responses.',
        );

        // Store the user's selection for page caching in the configuration.
        static::getInput()->setOption('page-cache', $pageCacheEnabled);

        // Step 2: Configure Redis Settings for Page Cache (If Enabled)
        // If the user enables page caching, prompt for the Redis configuration details.
        if ($pageCacheEnabled) {
            // Prompt for the Redis server address to be used for page caching.
            // This is where the cached pages will be stored.
            $pageCacheRedisServer = text(
                label: 'Redis server for page cache?',
                placeholder: 'Enter Redis server address',
                default: $this->option('cache-backend-redis-server', '127.0.0.1'), // Default Redis server address.
                hint: 'Redis server for managing page cache.',
            );

            // Store the Redis server address for page caching.
            static::getInput()->setOption('page-cache-redis-server', $pageCacheRedisServer);

            // Prompt for the Redis database index to organize cached pages.
            // Different databases can be used to segregate various types of cache.
            $pageCacheRedisDb = text(
                label: 'Redis database index for page cache?',
                placeholder: 'Enter Redis database index',
                default: '1', // Default Redis database index for page caching.
                hint: 'Redis database index for page cache management.',
            );

            // Store the Redis database index for page caching.
            static::getInput()->setOption('page-cache-redis-db', $pageCacheRedisDb);

            // Prompt for the Redis port used for page caching.
            // This defines the port number to communicate with the Redis server.
            $pageCacheRedisPort = text(
                label: 'Redis port for page cache?',
                placeholder: 'Enter Redis port',
                default: $this->option('cache-backend-redis-port', '6379'), // Default Redis port.
                hint: 'Specify the Redis port used for page caching.',
            );

            // Store the Redis port number for page caching.
            static::getInput()->setOption('page-cache-redis-port', $pageCacheRedisPort);
        }
    }

    /**
     * Prompts the user to configure the distributed locking mechanism.
     *
     * This method allows the user to select a lock provider (Redis, Zookeeper, or Database)
     * and configure additional settings specific to the selected provider. It supports
     * setting Redis database prefixes or Zookeeper host details.
     *
     * @return void
     */
    private function promptForCacheLock()
    {
        // Locking Mechanism Configuration: Choose the provider for distributed locking
        $lockProvider = select(
            label: 'Select lock provider type',
            options: [
                'redis' => 'Redis',  // Option to use Redis for distributed locks
                'zookeeper' => 'Zookeeper',  // Option to use Zookeeper for distributed locks
                'database' => 'Database', // Option to use Database for distributed locks
            ],
            default: 'redis',   // Default selection is Redis for locks
            hint: 'Select the lock provider to handle distributed locks.',
        );

        // Store selected lock provider
        static::getInput()->setOption('lock-provider', $lockProvider);

        // If Redis is selected for lock provider, prompt for database prefix for locks
        if ($lockProvider === 'redis') {
            $lockDbPrefix = text(
                label: 'Database prefix for locks?',
                placeholder: 'Enter database prefix for locks',
                default: 'lock_',   // Default prefix for lock keys in the database
                hint: 'Prefix used for lock keys in the database.',
            );

            // Store lock prefix for Redis-based locks
            static::getInput()->setOption('lock-db-prefix', $lockDbPrefix);
        }

        // If Zookeeper is selected for lock provider, prompt for Zookeeper host address
        if ($lockProvider === 'zookeeper') {
            $zookeeperHost = text(
                label: 'Zookeeper host for locks?',
                placeholder: 'Enter Zookeeper host',
                default: '127.0.0.1',// Default Zookeeper host address
                hint: 'Host address for the Zookeeper service.',
            );

            // Store Zookeeper host address for locks
            static::getInput()->setOption('lock-zookeeper-host', $zookeeperHost);
        }
    }
}
