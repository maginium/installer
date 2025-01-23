<?php

declare(strict_types=1);

namespace Maginium\Installer\Helpers;

use Illuminate\Support\Collection;

/**
 * Currency class extends Laravel's Collection to manage a collection of currencies.
 *
 * This class manages a collection of currencies, where each currency is represented by a code and its full name.
 *
 * @mixin Collection
 */
class Currency
{
    /**
     * The items contained in the collection.
     *
     * @var Collection
     */
    protected $items = [];

    /**
     * Constructor to initialize the collection of currencies.
     */
    public function __construct()
    {
        $this->items = collect($this->getCurrencies());
    }

    /**
     * Get the full name of a currency by its code.
     *
     * @param string $code The currency code (e.g., 'USD').
     *
     * @return string|null The full name of the currency or null if not found.
     */
    public function getCurrencyByCode(string $code): ?string
    {
        return $this->items->get($code);
    }

    /**
     * Check if a given currency code exists in the collection.
     *
     * @param string $code The currency code (e.g., 'USD').
     *
     * @return bool True if the currency exists, false otherwise.
     */
    public function isValidCurrency(string $code): bool
    {
        return $this->items->has($code);
    }

    /**
     * Get a list of all available currencies.
     *
     * @return array The list of currencies with their codes as keys.
     */
    public function getCurrencyList(): array
    {
        return $this->items->all();
    }

    /**
     * Add a new currency to the collection.
     *
     * @param string $code The currency code (e.g., 'INR').
     * @param string $name The currency name (e.g., 'Indian Rupee').
     */
    public function addCurrency(string $code, string $name): void
    {
        $this->items->put($code, $name);
    }

    /**
     * Remove a currency from the collection by its code.
     *
     * @param string $code The currency code (e.g., 'INR').
     */
    public function removeCurrency(string $code): void
    {
        $this->items->forget($code);
    }

    /**
     * The list of currencies.
     *
     * @var array<string, string>
     */
    private function getCurrencies(): array
    {
        return [
            [
                'id' => 'ALL',
                'name' => 'Albania Lek',
            ],
            [
                'id' => 'AFN',
                'name' => 'Afghanistan Afghani',
            ],
            [
                'id' => 'ARS',
                'name' => 'Argentina Peso',
            ],
            [
                'id' => 'AWG',
                'name' => 'Aruba Guilder',
            ],
            [
                'id' => 'AUD',
                'name' => 'Australia Dollar',
            ],
            [
                'id' => 'AZN',
                'name' => 'Azerbaijan New Manat',
            ],
            [
                'id' => 'BSD',
                'name' => 'Bahamas Dollar',
            ],
            [
                'id' => 'BBD',
                'name' => 'Barbados Dollar',
            ],
            [
                'id' => 'BDT',
                'name' => 'Bangladeshi taka',
            ],
            [
                'id' => 'BYR',
                'name' => 'Belarus Ruble',
            ],
            [
                'id' => 'BZD',
                'name' => 'Belize Dollar',
            ],
            [
                'id' => 'BMD',
                'name' => 'Bermuda Dollar',
            ],
            [
                'id' => 'BOB',
                'name' => 'Bolivia Boliviano',
            ],
            [
                'id' => 'BAM',
                'name' => 'Bosnia and Herzegovina Convertible Marka',
            ],
            [
                'id' => 'BWP',
                'name' => 'Botswana Pula',
            ],
            [
                'id' => 'BGN',
                'name' => 'Bulgaria Lev',
            ],
            [
                'id' => 'BRL',
                'name' => 'Brazil Real',
            ],
            [
                'id' => 'BND',
                'name' => 'Brunei Darussalam Dollar',
            ],
            [
                'id' => 'KHR',
                'name' => 'Cambodia Riel',
            ],
            [
                'id' => 'CAD',
                'name' => 'Canada Dollar',
            ],
            [
                'id' => 'KYD',
                'name' => 'Cayman Islands Dollar',
            ],
            [
                'id' => 'CLP',
                'name' => 'Chile Peso',
            ],
            [
                'id' => 'CNY',
                'name' => 'China Yuan Renminbi',
            ],
            [
                'id' => 'COP',
                'name' => 'Colombia Peso',
            ],
            [
                'id' => 'CRC',
                'name' => 'Costa Rica Colon',
            ],
            [
                'id' => 'HRK',
                'name' => 'Croatia Kuna',
            ],
            [
                'id' => 'CUP',
                'name' => 'Cuba Peso',
            ],
            [
                'id' => 'CZK',
                'name' => 'Czech Republic Koruna',
            ],
            [
                'id' => 'DKK',
                'name' => 'Denmark Krone',
            ],
            [
                'id' => 'DOP',
                'name' => 'Dominican Republic Peso',
            ],
            [
                'id' => 'XCD',
                'name' => 'East Caribbean Dollar',
            ],
            [
                'id' => 'EGP',
                'name' => 'Egypt Pound',
            ],
            [
                'id' => 'SVC',
                'name' => 'El Salvador Colon',
            ],
            [
                'id' => 'EEK',
                'name' => 'Estonia Kroon',
            ],
            [
                'id' => 'EUR',
                'name' => 'Euro Member Countries',
            ],
            [
                'id' => 'FKP',
                'name' => 'Falkland Islands (Malvinas) Pound',
            ],
            [
                'id' => 'FJD',
                'name' => 'Fiji Dollar',
            ],
            [
                'id' => 'GHC',
                'name' => 'Ghana Cedis',
            ],
            [
                'id' => 'GIP',
                'name' => 'Gibraltar Pound',
            ],
            [
                'id' => 'GTQ',
                'name' => 'Guatemala Quetzal',
            ],
            [
                'id' => 'GGP',
                'name' => 'Guernsey Pound',
            ],
            [
                'id' => 'GYD',
                'name' => 'Guyana Dollar',
            ],
            [
                'id' => 'HNL',
                'name' => 'Honduras Lempira',
            ],
            [
                'id' => 'HKD',
                'name' => 'Hong Kong Dollar',
            ],
            [
                'id' => 'HUF',
                'name' => 'Hungary Forint',
            ],
            [
                'id' => 'ISK',
                'name' => 'Iceland Krona',
            ],
            [
                'id' => 'INR',
                'name' => 'India Rupee',
            ],
            [
                'id' => 'IDR',
                'name' => 'Indonesia Rupiah',
            ],
            [
                'id' => 'IRR',
                'name' => 'Iran Rial',
            ],
            [
                'id' => 'IMP',
                'name' => 'Isle of Man Pound',
            ],
            [
                'id' => 'ILS',
                'name' => 'Israel Shekel',
            ],
            [
                'id' => 'JMD',
                'name' => 'Jamaica Dollar',
            ],
            [
                'id' => 'JPY',
                'name' => 'Japan Yen',
            ],
            [
                'id' => 'JEP',
                'name' => 'Jersey Pound',
            ],
            [
                'id' => 'KZT',
                'name' => 'Kazakhstan Tenge',
            ],
            [
                'id' => 'KPW',
                'name' => 'Korea (North) Won',
            ],
            [
                'id' => 'KRW',
                'name' => 'Korea (South) Won',
            ],
            [
                'id' => 'KGS',
                'name' => 'Kyrgyzstan Som',
            ],
            [
                'id' => 'LAK',
                'name' => 'Laos Kip',
            ],
            [
                'id' => 'LVL',
                'name' => 'Latvia Lat',
            ],
            [
                'id' => 'LBP',
                'name' => 'Lebanon Pound',
            ],
            [
                'id' => 'LRD',
                'name' => 'Liberia Dollar',
            ],
            [
                'id' => 'LTL',
                'name' => 'Lithuania Litas',
            ],
            [
                'id' => 'MKD',
                'name' => 'Macedonia Denar',
            ],
            [
                'id' => 'MYR',
                'name' => 'Malaysia Ringgit',
            ],
            [
                'id' => 'MUR',
                'name' => 'Mauritius Rupee',
            ],
            [
                'id' => 'MXN',
                'name' => 'Mexico Peso',
            ],
            [
                'id' => 'MNT',
                'name' => 'Mongolia Tughrik',
            ],
            [
                'id' => 'MZN',
                'name' => 'Mozambique Metical',
            ],
            [
                'id' => 'NAD',
                'name' => 'Namibia Dollar',
            ],
            [
                'id' => 'NPR',
                'name' => 'Nepal Rupee',
            ],
            [
                'id' => 'ANG',
                'name' => 'Netherlands Antilles Guilder',
            ],
            [
                'id' => 'NZD',
                'name' => 'New Zealand Dollar',
            ],
            [
                'id' => 'NIO',
                'name' => 'Nicaragua Cordoba',
            ],
            [
                'id' => 'NGN',
                'name' => 'Nigeria Naira',
            ],
            [
                'id' => 'NOK',
                'name' => 'Norway Krone',
            ],
            [
                'id' => 'OMR',
                'name' => 'Oman Rial',
            ],
            [
                'id' => 'PKR',
                'name' => 'Pakistan Rupee',
            ],
            [
                'id' => 'PAB',
                'name' => 'Panama Balboa',
            ],
            [
                'id' => 'PYG',
                'name' => 'Paraguay Guarani',
            ],
            [
                'id' => 'PEN',
                'name' => 'Peru Nuevo Sol',
            ],
            [
                'id' => 'PHP',
                'name' => 'Philippines Peso',
            ],
            [
                'id' => 'PLN',
                'name' => 'Poland Zloty',
            ],
            [
                'id' => 'QAR',
                'name' => 'Qatar Riyal',
            ],
            [
                'id' => 'RON',
                'name' => 'Romania New Leu',
            ],
            [
                'id' => 'RUB',
                'name' => 'Russia Ruble',
            ],
            [
                'id' => 'SHP',
                'name' => 'Saint Helena Pound',
            ],
            [
                'id' => 'SAR',
                'name' => 'Saudi Arabia Riyal',
            ],
            [
                'id' => 'RSD',
                'name' => 'Serbia Dinar',
            ],
            [
                'id' => 'SCR',
                'name' => 'Seychelles Rupee',
            ],
            [
                'id' => 'SGD',
                'name' => 'Singapore Dollar',
            ],
            [
                'id' => 'SBD',
                'name' => 'Solomon Islands Dollar',
            ],
            [
                'id' => 'SOS',
                'name' => 'Somalia Shilling',
            ],
            [
                'id' => 'ZAR',
                'name' => 'South Africa Rand',
            ],
            [
                'id' => 'LKR',
                'name' => 'Sri Lanka Rupee',
            ],
            [
                'id' => 'SEK',
                'name' => 'Sweden Krona',
            ],
            [
                'id' => 'CHF',
                'name' => 'Switzerland Franc',
            ],
            [
                'id' => 'SRD',
                'name' => 'Suriname Dollar',
            ],
            [
                'id' => 'SYP',
                'name' => 'Syria Pound',
            ],
            [
                'id' => 'TWD',
                'name' => 'Taiwan New Dollar',
            ],
            [
                'id' => 'THB',
                'name' => 'Thailand Baht',
            ],
            [
                'id' => 'TTD',
                'name' => 'Trinidad and Tobago Dollar',
            ],
            [
                'id' => 'TRY',
                'name' => 'Turkey Lira',
            ],
            [
                'id' => 'TRL',
                'name' => 'Turkey Lira',
            ],
            [
                'id' => 'TVD',
                'name' => 'Tuvalu Dollar',
            ],
            [
                'id' => 'UAH',
                'name' => 'Ukraine Hryvna',
            ],
            [
                'id' => 'GBP',
                'name' => 'United Kingdom Pound',
            ],
            [
                'id' => 'USD',
                'name' => 'United States Dollar',
            ],
            [
                'id' => 'UYU',
                'name' => 'Uruguay Peso',
            ],
            [
                'id' => 'UZS',
                'name' => 'Uzbekistan Som',
            ],
            [
                'id' => 'VEF',
                'name' => 'Venezuela Bolivar',
            ],
            [
                'id' => 'VND',
                'name' => 'Viet Nam Dong',
            ],
            [
                'id' => 'YER',
                'name' => 'Yemen Rial',
            ],
            [
                'id' => 'ZWD',
                'name' => 'Zimbabwe Dollar',
            ],
        ];
    }

    /**
     * Magic method to handle dynamic method calls on the collection.
     *
     * This method allows you to call methods dynamically on the internal `$items` collection
     * of the class when the method is not defined within the class itself.
     *
     * If the method exists on the `$items` collection (which is an instance of Laravel's
     * `Collection`), it will forward the call to that collection.
     *
     * @param string $method The name of the method being called.
     * @param array  $parameters The parameters to be passed to the method being called.
     *
     * @return mixed The result of the method call on the internal collection, or null if the method does not exist.
     */
    public function __call(string $method, array $parameters)
    {
        if (method_exists($this->items, $method)) {
            return $this->items->{$method}(...$parameters);
        }

        // If the method exists within the class itself, proceed with its execution
        return $this->{$method}(...$parameters);
    }
}
