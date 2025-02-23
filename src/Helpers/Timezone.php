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

namespace Maginium\Installer\Helpers;

use Illuminate\Support\Collection;

/**
 * Timezone class extends Laravel's Collection to manage a collection of timezones.
 *
 * This class manages a collection of timezones, where each timezone is represented by a code and its full name.
 *
 * @mixin Collection
 */
class Timezone
{
    /**
     * The items contained in the collection.
     *
     * @var Collection
     */
    protected $items = [];

    /**
     * Constructor to initialize the collection of timezones.
     */
    public function __construct()
    {
        $this->items = collect($this->getTimezones());
    }

    /**
     * Get the full name of a timezone by its code.
     *
     * @param string $code The timezone code (e.g., 'USD').
     *
     * @return string|null The full name of the timezone or null if not found.
     */
    public function getTimezoneByCode(string $code): ?string
    {
        return $this->items->get($code);
    }

    /**
     * Check if a given timezone code exists in the collection.
     *
     * @param string $code The timezone code (e.g., 'USD').
     *
     * @return bool True if the timezone exists, false otherwise.
     */
    public function isValidTimezone(string $code): bool
    {
        return $this->items->has($code);
    }

    /**
     * Get a list of all available timezones.
     *
     * @return array The list of timezones with their codes as keys.
     */
    public function getTimezoneList(): array
    {
        return $this->items->all();
    }

    /**
     * Add a new timezone to the collection.
     *
     * @param string $code The timezone code (e.g., 'INR').
     * @param string $name The timezone name (e.g., 'Indian Rupee').
     */
    public function addTimezone(string $code, string $name): void
    {
        $this->items->put($code, $name);
    }

    /**
     * Remove a timezone from the collection by its code.
     *
     * @param string $code The timezone code (e.g., 'INR').
     */
    public function removeTimezone(string $code): void
    {
        $this->items->forget($code);
    }

    /**
     * The list of timezones.
     *
     * @var array<string, string>
     */
    private function getTimezones(): array
    {
        return [
            [
                'id' => 'Africa/Algiers',
                'name' => 'Algeria (+01:00)',
            ],
            [
                'id' => 'Africa/Gaborone',
                'name' => 'Botswana (+02:00)',
            ],
            [
                'id' => 'Africa/Douala',
                'name' => 'Cameroon (+01:00)',
            ],
            [
                'id' => 'Africa/Bangui',
                'name' => 'Central African Republic (+01:00)',
            ],
            [
                'id' => 'Africa/Ndjamena',
                'name' => 'Chad (+01:00)',
            ],
            [
                'id' => 'Africa/Kinshasa',
                'name' => 'Democratic Republic of the Congo (+01:00)',
            ],
            [
                'id' => 'Africa/Djibouti',
                'name' => 'Djibouti (+03:00)',
            ],
            [
                'id' => 'Africa/Cairo',
                'name' => 'Egypt (+02:00)',
            ],
            [
                'id' => 'Africa/Malabo',
                'name' => 'Equatorial Guinea (+01:00)',
            ],
            [
                'id' => 'Africa/Asmara',
                'name' => 'Eritrea (+03:00)',
            ],
            [
                'id' => 'Africa/Addis_Ababa',
                'name' => 'Ethiopia (+03:00)',
            ],
            [
                'id' => 'Africa/Libreville',
                'name' => 'Gabon (+01:00)',
            ],
            [
                'id' => 'Africa/Banjul',
                'name' => 'Gambia (+00:00)',
            ],
            [
                'id' => 'Africa/Accra',
                'name' => 'Ghana (+00:00)',
            ],
            [
                'id' => 'Africa/Conakry',
                'name' => 'Guinea (+00:00)',
            ],
            [
                'id' => 'Africa/Bissau',
                'name' => 'Guinea-Bissau (+00:00)',
            ],
            [
                'id' => 'Africa/Abidjan',
                'name' => 'Ivory Coast (+00:00)',
            ],
            [
                'id' => 'Africa/Nairobi',
                'name' => 'Kenya (+03:00)',
            ],
            [
                'id' => 'Africa/Maseru',
                'name' => 'Lesotho (+02:00)',
            ],
            [
                'id' => 'Africa/Monrovia',
                'name' => 'Liberia (+00:00)',
            ],
            [
                'id' => 'Africa/Tripoli',
                'name' => 'Libya (+02:00)',
            ],
            [
                'id' => 'Africa/Blantyre',
                'name' => 'Malawi (+02:00)',
            ],
            [
                'id' => 'Africa/Bamako',
                'name' => 'Mali (+00:00)',
            ],
            [
                'id' => 'Africa/Nouakchott',
                'name' => 'Mauritania (+00:00)',
            ],
            [
                'id' => 'Africa/Casablanca',
                'name' => 'Morocco (+01:00)',
            ],
            [
                'id' => 'Africa/Maputo',
                'name' => 'Mozambique (+02:00)',
            ],
            [
                'id' => 'Africa/Windhoek',
                'name' => 'Namibia (+01:00)',
            ],
            [
                'id' => 'Africa/Niamey',
                'name' => 'Niger (+01:00)',
            ],
            [
                'id' => 'Africa/Lagos',
                'name' => 'Nigeria (+01:00)',
            ],
            [
                'id' => 'Africa/Brazzaville',
                'name' => 'Republic of the Congo (+01:00)',
            ],
            [
                'id' => 'Africa/Kigali',
                'name' => 'Rwanda (+02:00)',
            ],
            [
                'id' => 'Africa/Sao_Tome',
                'name' => 'Sao Tome and Principe (+00:00)',
            ],
            [
                'id' => 'Africa/Dakar',
                'name' => 'Senegal (+00:00)',
            ],
            [
                'id' => 'Africa/Freetown',
                'name' => 'Sierra Leone (+00:00)',
            ],
            [
                'id' => 'Africa/Mogadishu',
                'name' => 'Somalia (+03:00)',
            ],
            [
                'id' => 'Africa/Johannesburg',
                'name' => 'South Africa (+02:00)',
            ],
            [
                'id' => 'Africa/Juba',
                'name' => 'South Sudan (+03:00)',
            ],
            [
                'id' => 'Africa/Khartoum',
                'name' => 'Sudan (+03:00)',
            ],
            [
                'id' => 'Africa/Mbabane',
                'name' => 'Swaziland (+02:00)',
            ],
            [
                'id' => 'Africa/Dar_es_Salaam',
                'name' => 'Tanzania (+03:00)',
            ],
            [
                'id' => 'Africa/Lome',
                'name' => 'Togo (+00:00)',
            ],
            [
                'id' => 'Africa/Tunis',
                'name' => 'Tunisia (+01:00)',
            ],
            [
                'id' => 'Africa/Kampala',
                'name' => 'Uganda (+03:00)',
            ],
            [
                'id' => 'Africa/El_Aaiun',
                'name' => 'Western Sahara (+00:00)',
            ],
            [
                'id' => 'Africa/Lusaka',
                'name' => 'Zambia (+02:00)',
            ],
            [
                'id' => 'Africa/Harare',
                'name' => 'Zimbabwe (+02:00)',
            ],
            [
                'id' => 'America/Nassau',
                'name' => 'Bahamas (-04:00)',
            ],
            [
                'id' => 'America/Belize',
                'name' => 'Belize (-06:00)',
            ],
            [
                'id' => 'America/Noronha',
                'name' => 'Brazil (-02:00)',
            ],
            [
                'id' => 'America/Tortola',
                'name' => 'British Virgin Islands (-04:00)',
            ],
            [
                'id' => 'America/St_Johns',
                'name' => 'Canada (-02:30)',
            ],
            [
                'id' => 'America/Cayman',
                'name' => 'Cayman Islands (-05:00)',
            ],
            [
                'id' => 'America/Santiago',
                'name' => 'Chile (-04:00)',
            ],
            [
                'id' => 'America/Bogota',
                'name' => 'Colombia (-05:00)',
            ],
            [
                'id' => 'America/Costa_Rica',
                'name' => 'Costa Rica (-06:00)',
            ],
            [
                'id' => 'America/Havana',
                'name' => 'Cuba (-04:00)',
            ],
            [
                'id' => 'America/Curacao',
                'name' => 'Curaçao (-04:00)',
            ],
            [
                'id' => 'America/Dominica',
                'name' => 'Dominica (-04:00)',
            ],
            [
                'id' => 'America/Santo_Domingo',
                'name' => 'Dominican Republic (-04:00)',
            ],
            [
                'id' => 'America/Guayaquil',
                'name' => 'Ecuador (-05:00)',
            ],
            [
                'id' => 'America/El_Salvador',
                'name' => 'El Salvador (-06:00)',
            ],
            [
                'id' => 'America/Cayenne',
                'name' => 'French Guiana (-03:00)',
            ],
            [
                'id' => 'America/Godthab',
                'name' => 'Greenland (-02:00)',
            ],
            [
                'id' => 'America/Grenada',
                'name' => 'Grenada (-04:00)',
            ],
            [
                'id' => 'America/Guadeloupe',
                'name' => 'Guadeloupe (-04:00)',
            ],
            [
                'id' => 'America/Guatemala',
                'name' => 'Guatemala (-06:00)',
            ],
            [
                'id' => 'America/Guyana',
                'name' => 'Guyana (-04:00)',
            ],
            [
                'id' => 'America/Port-au-Prince',
                'name' => 'Haiti (-05:00)',
            ],
            [
                'id' => 'America/Tegucigalpa',
                'name' => 'Honduras (-06:00)',
            ],
            [
                'id' => 'America/Jamaica',
                'name' => 'Jamaica (-05:00)',
            ],
            [
                'id' => 'America/Martinique',
                'name' => 'Martinique (-04:00)',
            ],
            [
                'id' => 'America/Mexico_City',
                'name' => 'Mexico (-05:00)',
            ],
            [
                'id' => 'America/Montserrat',
                'name' => 'Montserrat (-04:00)',
            ],
            [
                'id' => 'America/Managua',
                'name' => 'Nicaragua (-06:00)',
            ],
            [
                'id' => 'America/Panama',
                'name' => 'Panama (-05:00)',
            ],
            [
                'id' => 'America/Asuncion',
                'name' => 'Paraguay (-04:00)',
            ],
            [
                'id' => 'America/Lima',
                'name' => 'Peru (-05:00)',
            ],
            [
                'id' => 'America/Puerto_Rico',
                'name' => 'Puerto Rico (-04:00)',
            ],
            [
                'id' => 'America/St_Kitts',
                'name' => 'Saint Kitts and Nevis (-04:00)',
            ],
            [
                'id' => 'America/St_Lucia',
                'name' => 'Saint Lucia (-04:00)',
            ],
            [
                'id' => 'America/Marigot',
                'name' => 'Saint Martin (-04:00)',
            ],
            [
                'id' => 'America/Miquelon',
                'name' => 'Saint Pierre and Miquelon (-02:00)',
            ],
            [
                'id' => 'America/St_Vincent',
                'name' => 'Saint Vincent and the Grenadines (-04:00)',
            ],
            [
                'id' => 'America/Lower_Princes',
                'name' => 'Sint Maarten (-04:00)',
            ],
            [
                'id' => 'America/Paramaribo',
                'name' => 'Suriname (-03:00)',
            ],
            [
                'id' => 'America/Port_of_Spain',
                'name' => 'Trinidad and Tobago (-04:00)',
            ],
            [
                'id' => 'America/Grand_Turk',
                'name' => 'Turks and Caicos Islands (-04:00)',
            ],
            [
                'id' => 'America/St_Thomas',
                'name' => 'U.S. Virgin Islands (-04:00)',
            ],
            [
                'id' => 'America/New_York',
                'name' => 'United States (-04:00)',
            ],
            [
                'id' => 'America/Montevideo',
                'name' => 'Uruguay (-03:00)',
            ],
            [
                'id' => 'Europe/Vatican',
                'name' => 'Vatican (+02:00)',
            ],
            [
                'id' => 'America/Caracas',
                'name' => 'Venezuela (-04:30)',
            ],
            [
                'id' => 'Arctic/Longyearbyen',
                'name' => 'Svalbard and Jan Mayen (+02:00)',
            ],
            [
                'id' => 'Asia/Thimphu',
                'name' => 'Bhutan (+06:00)',
            ],
            [
                'id' => 'Asia/Phnom_Penh',
                'name' => 'Cambodia (+07:00)',
            ],
            [
                'id' => 'Asia/Shanghai',
                'name' => 'China (+08:00)',
            ],
            [
                'id' => 'Asia/Nicosia',
                'name' => 'Cyprus (+03:00)',
            ],
            [
                'id' => 'Asia/Dili',
                'name' => 'East Timor (+09:00)',
            ],
            [
                'id' => 'Asia/Tbilisi',
                'name' => 'Georgia (+04:00)',
            ],
            [
                'id' => 'Asia/Hong_Kong',
                'name' => 'Hong Kong (+08:00)',
            ],
            [
                'id' => 'Asia/Kolkata',
                'name' => 'India (+05:30)',
            ],
            [
                'id' => 'Asia/Jakarta',
                'name' => 'Indonesia (+07:00)',
            ],
            [
                'id' => 'Asia/Tehran',
                'name' => 'Iran (+04:30)',
            ],
            [
                'id' => 'Asia/Baghdad',
                'name' => 'Iraq (+03:00)',
            ],
            [
                'id' => 'Asia/Jerusalem',
                'name' => 'Israel (+03:00)',
            ],
            [
                'id' => 'Asia/Tokyo',
                'name' => 'Japan (+09:00)',
            ],
            [
                'id' => 'Asia/Amman',
                'name' => 'Jordan (+03:00)',
            ],
            [
                'id' => 'Asia/Almaty',
                'name' => 'Kazakhstan (+06:00)',
            ],
            [
                'id' => 'Asia/Kuwait',
                'name' => 'Kuwait (+03:00)',
            ],
            [
                'id' => 'Asia/Bishkek',
                'name' => 'Kyrgyzstan (+06:00)',
            ],
            [
                'id' => 'Asia/Vientiane',
                'name' => 'Laos (+07:00)',
            ],
            [
                'id' => 'Asia/Beirut',
                'name' => 'Lebanon (+03:00)',
            ],
            [
                'id' => 'Asia/Macau',
                'name' => 'Macao (+08:00)',
            ],
            [
                'id' => 'Asia/Kuala_Lumpur',
                'name' => 'Malaysia (+08:00)',
            ],
            [
                'id' => 'Asia/Ulaanbaatar',
                'name' => 'Mongolia (+08:00)',
            ],
            [
                'id' => 'Asia/Rangoon',
                'name' => 'Myanmar (+06:30)',
            ],
            [
                'id' => 'Asia/Kathmandu',
                'name' => 'Nepal (+05:45)',
            ],
            [
                'id' => 'Asia/Pyongyang',
                'name' => 'North Korea (+09:00)',
            ],
            [
                'id' => 'Asia/Muscat',
                'name' => 'Oman (+04:00)',
            ],
            [
                'id' => 'Asia/Karachi',
                'name' => 'Pakistan (+05:00)',
            ],
            [
                'id' => 'Asia/Gaza',
                'name' => 'Palestinian Territory (+02:00)',
            ],
            [
                'id' => 'Asia/Manila',
                'name' => 'Philippines (+08:00)',
            ],
            [
                'id' => 'Asia/Qatar',
                'name' => 'Qatar (+03:00)',
            ],
            [
                'id' => 'Asia/Riyadh',
                'name' => 'Saudi Arabia (+03:00)',
            ],
            [
                'id' => 'Asia/Singapore',
                'name' => 'Singapore (+08:00)',
            ],
            [
                'id' => 'Asia/Seoul',
                'name' => 'South Korea (+09:00)',
            ],
            [
                'id' => 'Asia/Colombo',
                'name' => 'Sri Lanka (+05:30)',
            ],
            [
                'id' => 'Asia/Damascus',
                'name' => 'Syria (+03:00)',
            ],
            [
                'id' => 'Asia/Taipei',
                'name' => 'Taiwan (+08:00)',
            ],
            [
                'id' => 'Asia/Dushanbe',
                'name' => 'Tajikistan (+05:00)',
            ],
            [
                'id' => 'Asia/Bangkok',
                'name' => 'Thailand (+07:00)',
            ],
            [
                'id' => 'Asia/Ashgabat',
                'name' => 'Turkmenistan (+05:00)',
            ],
            [
                'id' => 'Asia/Samarkand',
                'name' => 'Uzbekistan (+05:00)',
            ],
            [
                'id' => 'Asia/Ho_Chi_Minh',
                'name' => 'Vietnam (+07:00)',
            ],
            [
                'id' => 'Asia/Aden',
                'name' => 'Yemen (+03:00)',
            ],
            [
                'id' => 'Atlantic/Cape_Verde',
                'name' => 'Cape Verde (-01:00)',
            ],
            [
                'id' => 'Atlantic/Stanley',
                'name' => 'Falkland Islands (-03:00)',
            ],
            [
                'id' => 'Atlantic/Faroe',
                'name' => 'Faroe Islands (+01:00)',
            ],
            [
                'id' => 'Atlantic/Reykjavik',
                'name' => 'Iceland (+00:00)',
            ],
            [
                'id' => 'Atlantic/St_Helena',
                'name' => 'Saint Helena (+00:00)',
            ],
            [
                'id' => 'Atlantic/South_Georgia',
                'name' => 'South Georgia and the South Sandwich Islands (-02:00)',
            ],
            [
                'id' => 'Europe/Minsk',
                'name' => 'Belarus (+03:00)',
            ],
            [
                'id' => 'Europe/Zagreb',
                'name' => 'Croatia (+02:00)',
            ],
            [
                'id' => 'Europe/Prague',
                'name' => 'Czech Republic (+02:00)',
            ],
            [
                'id' => 'Europe/Copenhagen',
                'name' => 'Denmark (+02:00)',
            ],
            [
                'id' => 'Europe/Tallinn',
                'name' => 'Estonia (+03:00)',
            ],
            [
                'id' => 'Europe/Helsinki',
                'name' => 'Finland (+03:00)',
            ],
            [
                'id' => 'Europe/Paris',
                'name' => 'France (+02:00)',
            ],
            [
                'id' => 'Europe/Berlin',
                'name' => 'Germany (+02:00)',
            ],
            [
                'id' => 'Europe/Gibraltar',
                'name' => 'Gibraltar (+02:00)',
            ],
            [
                'id' => 'Europe/Athens',
                'name' => 'Greece (+03:00)',
            ],
            [
                'id' => 'Europe/Guernsey',
                'name' => 'Guernsey (+01:00)',
            ],
            [
                'id' => 'Europe/Budapest',
                'name' => 'Hungary (+02:00)',
            ],
            [
                'id' => 'Europe/Dublin',
                'name' => 'Ireland (+01:00)',
            ],
            [
                'id' => 'Europe/Isle_of_Man',
                'name' => 'Isle of Man (+01:00)',
            ],
            [
                'id' => 'Europe/Rome',
                'name' => 'Italy (+02:00)',
            ],
            [
                'id' => 'Europe/Jersey',
                'name' => 'Jersey (+01:00)',
            ],
            [
                'id' => 'Europe/Riga',
                'name' => 'Latvia (+03:00)',
            ],
            [
                'id' => 'Europe/Vaduz',
                'name' => 'Liechtenstein (+02:00)',
            ],
            [
                'id' => 'Europe/Vilnius',
                'name' => 'Lithuania (+03:00)',
            ],
            [
                'id' => 'Europe/Luxembourg',
                'name' => 'Luxembourg (+02:00)',
            ],
            [
                'id' => 'Europe/Skopje',
                'name' => 'Macedonia (+02:00)',
            ],
            [
                'id' => 'Europe/Malta',
                'name' => 'Malta (+02:00)',
            ],
            [
                'id' => 'Europe/Chisinau',
                'name' => 'Moldova (+03:00)',
            ],
            [
                'id' => 'Europe/Monaco',
                'name' => 'Monaco (+02:00)',
            ],
            [
                'id' => 'Europe/Podgorica',
                'name' => 'Montenegro (+02:00)',
            ],
            [
                'id' => 'Europe/Amsterdam',
                'name' => 'Netherlands (+02:00)',
            ],
            [
                'id' => 'Europe/Oslo',
                'name' => 'Norway (+02:00)',
            ],
            [
                'id' => 'Europe/Warsaw',
                'name' => 'Poland (+02:00)',
            ],
            [
                'id' => 'Europe/Lisbon',
                'name' => 'Portugal (+01:00)',
            ],
            [
                'id' => 'Europe/Bucharest',
                'name' => 'Romania (+03:00)',
            ],
            [
                'id' => 'Europe/Kaliningrad',
                'name' => 'Russia (+03:00)',
            ],
            [
                'id' => 'Europe/San_Marino',
                'name' => 'San Marino (+02:00)',
            ],
            [
                'id' => 'Europe/Belgrade',
                'name' => 'Serbia (+02:00)',
            ],
            [
                'id' => 'Europe/Bratislava',
                'name' => 'Slovakia (+02:00)',
            ],
            [
                'id' => 'Europe/Ljubljana',
                'name' => 'Slovenia (+02:00)',
            ],
            [
                'id' => 'Europe/Madrid',
                'name' => 'Spain (+02:00)',
            ],
            [
                'id' => 'Europe/Stockholm',
                'name' => 'Sweden (+02:00)',
            ],
            [
                'id' => 'Europe/Zurich',
                'name' => 'Switzerland (+02:00)',
            ],
            [
                'id' => 'Europe/Istanbul',
                'name' => 'Turkey (+03:00)',
            ],
            [
                'id' => 'Europe/Kiev',
                'name' => 'Ukraine (+03:00)',
            ],
            [
                'id' => 'Europe/London',
                'name' => 'United Kingdom (+01:00)',
            ],
            [
                'id' => 'Indian/Chagos',
                'name' => 'British Indian Ocean Territory (+06:00)',
            ],
            [
                'id' => 'Indian/Christmas',
                'name' => 'Christmas Island (+07:00)',
            ],
            [
                'id' => 'Indian/Cocos',
                'name' => 'Cocos Islands (+06:30)',
            ],
            [
                'id' => 'Indian/Comoro',
                'name' => 'Comoros (+03:00)',
            ],
            [
                'id' => 'Indian/Kerguelen',
                'name' => 'French Southern Territories (+05:00)',
            ],
            [
                'id' => 'Indian/Antananarivo',
                'name' => 'Madagascar (+03:00)',
            ],
            [
                'id' => 'Indian/Maldives',
                'name' => 'Maldives (+05:00)',
            ],
            [
                'id' => 'Indian/Mauritius',
                'name' => 'Mauritius (+04:00)',
            ],
            [
                'id' => 'Indian/Mayotte',
                'name' => 'Mayotte (+03:00)',
            ],
            [
                'id' => 'Indian/Reunion',
                'name' => 'Reunion (+04:00)',
            ],
            [
                'id' => 'Indian/Mahe',
                'name' => 'Seychelles (+04:00)',
            ],
            [
                'id' => 'Pacific/Rarotonga',
                'name' => 'Cook Islands (-10:00)',
            ],
            [
                'id' => 'Pacific/Fiji',
                'name' => 'Fiji (+12:00)',
            ],
            [
                'id' => 'Pacific/Tahiti',
                'name' => 'French Polynesia (-10:00)',
            ],
            [
                'id' => 'Pacific/Guam',
                'name' => 'Guam (+10:00)',
            ],
            [
                'id' => 'Pacific/Tarawa',
                'name' => 'Kiribati (+12:00)',
            ],
            [
                'id' => 'Pacific/Majuro',
                'name' => 'Marshall Islands (+12:00)',
            ],
            [
                'id' => 'Pacific/Chuuk',
                'name' => 'Micronesia (+10:00)',
            ],
            [
                'id' => 'Pacific/Nauru',
                'name' => 'Nauru (+12:00)',
            ],
            [
                'id' => 'Pacific/Noumea',
                'name' => 'New Caledonia (+11:00)',
            ],
            [
                'id' => 'Pacific/Auckland',
                'name' => 'New Zealand (+12:00)',
            ],
            [
                'id' => 'Pacific/Niue',
                'name' => 'Niue (-11:00)',
            ],
            [
                'id' => 'Pacific/Norfolk',
                'name' => 'Norfolk Island (+11:30)',
            ],
            [
                'id' => 'Pacific/Saipan',
                'name' => 'Northern Mariana Islands (+10:00)',
            ],
            [
                'id' => 'Pacific/Palau',
                'name' => 'Palau (+09:00)',
            ],
            [
                'id' => 'Pacific/Port_Moresby',
                'name' => 'Papua New Guinea (+10:00)',
            ],
            [
                'id' => 'Pacific/Pitcairn',
                'name' => 'Pitcairn (-08:00)',
            ],
            [
                'id' => 'Pacific/Apia',
                'name' => 'Samoa (+13:00)',
            ],
            [
                'id' => 'Pacific/Guadalcanal',
                'name' => 'Solomon Islands (+11:00)',
            ],
            [
                'id' => 'Pacific/Fakaofo',
                'name' => 'Tokelau (+14:00)',
            ],
            [
                'id' => 'Pacific/Tongatapu',
                'name' => 'Tonga (+13:00)',
            ],
            [
                'id' => 'Pacific/Funafuti',
                'name' => 'Tuvalu (+12:00)',
            ],
            [
                'id' => 'Pacific/Johnston',
                'name' => 'United States Minor Outlying Islands (-10:00)',
            ],
            [
                'id' => 'Pacific/Efate',
                'name' => 'Vanuatu (+11:00)',
            ],
            [
                'id' => 'Pacific/Wallis',
                'name' => 'Wallis and Futuna (+12:00)',
            ],
            [
                'id' => 'UTC',
                'name' => 'UTC',
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
