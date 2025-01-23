<?php

declare(strict_types=1);

namespace Maginium\Installer\Helpers;

use Illuminate\Support\Collection;

/**
 * Language class extends Laravel's Collection to manage a collection of languages.
 *
 * This class manages a collection of languages, where each language is represented by a code and its full name.
 *
 * @mixin Collection
 */
class Language
{
    /**
     * The items contained in the collection.
     *
     * @var Collection
     */
    protected $items = [];

    /**
     * Constructor to initialize the collection of languages.
     */
    public function __construct()
    {
        $this->items = collect($this->getLanguages());
    }

    /**
     * Get the full name of a language by its code.
     *
     * @param string $code The language code (e.g., 'USD').
     *
     * @return string|null The full name of the language or null if not found.
     */
    public function getLanguageByCode(string $code): ?string
    {
        return $this->items->get($code);
    }

    /**
     * Check if a given language code exists in the collection.
     *
     * @param string $code The language code (e.g., 'USD').
     *
     * @return bool True if the language exists, false otherwise.
     */
    public function isValidLanguage(string $code): bool
    {
        return $this->items->has($code);
    }

    /**
     * Get a list of all available languages.
     *
     * @return array The list of languages with their codes as keys.
     */
    public function getLanguageList(): array
    {
        return $this->items->all();
    }

    /**
     * Add a new language to the collection.
     *
     * @param string $code The language code (e.g., 'INR').
     * @param string $name The language name (e.g., 'Indian Rupee').
     */
    public function addLanguage(string $code, string $name): void
    {
        $this->items->put($code, $name);
    }

    /**
     * Remove a language from the collection by its code.
     *
     * @param string $code The language code (e.g., 'INR').
     */
    public function removeLanguage(string $code): void
    {
        $this->items->forget($code);
    }

    /**
     * The list of languages.
     *
     * @var array<string, string>
     */
    private function getLanguages(): array
    {
        return [
            [
                'id' => 'aa',
                'name' => 'Afar',
            ],
            [
                'id' => 'ab',
                'name' => 'Abkhazian',
            ],
            [
                'id' => 'ae',
                'name' => 'Avestan',
            ],
            [
                'id' => 'af',
                'name' => 'Afrikaans',
            ],
            [
                'id' => 'ak',
                'name' => 'Akan',
            ],
            [
                'id' => 'am',
                'name' => 'Amharic',
            ],
            [
                'id' => 'an',
                'name' => 'Aragonese',
            ],
            [
                'id' => 'ar_AE',
                'name' => 'Arabic (UAE)',
            ],
            [
                'id' => 'ar_BH',
                'name' => 'Arabic (Bahrain)',
            ],
            [
                'id' => 'ar_DZ',
                'name' => 'Arabic (Algeria)',
            ],
            [
                'id' => 'ar_EG',
                'name' => 'Arabic (Egypt)',
            ],
            [
                'id' => 'ar_IQ',
                'name' => 'Arabic (Iraq)',
            ],
            [
                'id' => 'ar_JO',
                'name' => 'Arabic (Jordan)',
            ],
            [
                'id' => 'ar_KW',
                'name' => 'Arabic (Kuwait)',
            ],
            [
                'id' => 'ar_LB',
                'name' => 'Arabic (Lebanon)',
            ],
            [
                'id' => 'ar_LY',
                'name' => 'Arabic (Libya)',
            ],
            [
                'id' => 'ar_MA',
                'name' => 'Arabic (Morocco)',
            ],
            [
                'id' => 'ar_OM',
                'name' => 'Arabic (Oman)',
            ],
            [
                'id' => 'ar_QA',
                'name' => 'Arabic (Qatar)',
            ],
            [
                'id' => 'ar_SA',
                'name' => 'Arabic (Saudi Arabia)',
            ],
            [
                'id' => 'ar_SY',
                'name' => 'Arabic (Syria)',
            ],
            [
                'id' => 'ar_TN',
                'name' => 'Arabic (Tunisia)',
            ],
            [
                'id' => 'ar_YE',
                'name' => 'Arabic (Yemen)',
            ],
            [
                'id' => 'ar',
                'name' => 'Arabic',
            ],
            [
                'id' => 'as',
                'name' => 'Assamese',
            ],
            [
                'id' => 'av',
                'name' => 'Avaric',
            ],
            [
                'id' => 'ay',
                'name' => 'Aymara',
            ],
            [
                'id' => 'az',
                'name' => 'Azerbaijani',
            ],
            [
                'id' => 'ba',
                'name' => 'Bashkir',
            ],
            [
                'id' => 'be',
                'name' => 'Belarusian',
            ],
            [
                'id' => 'bg',
                'name' => 'Bulgarian',
            ],
            [
                'id' => 'bh',
                'name' => 'Bihari',
            ],
            [
                'id' => 'bi',
                'name' => 'Bislama',
            ],
            [
                'id' => 'bm',
                'name' => 'Bambara',
            ],
            [
                'id' => 'bn',
                'name' => 'Bengali',
            ],
            [
                'id' => 'bo',
                'name' => 'Tibetan',
            ],
            [
                'id' => 'br',
                'name' => 'Breton',
            ],
            [
                'id' => 'bs',
                'name' => 'Bosnian',
            ],
            [
                'id' => 'ca',
                'name' => 'Catalan',
            ],
            [
                'id' => 'ce',
                'name' => 'Chechen',
            ],
            [
                'id' => 'ch',
                'name' => 'Chamorro',
            ],
            [
                'id' => 'co',
                'name' => 'Corsican',
            ],
            [
                'id' => 'cr',
                'name' => 'Cree',
            ],
            [
                'id' => 'cs',
                'name' => 'Czech',
            ],
            [
                'id' => 'cu',
                'name' => 'Church Slavic',
            ],
            [
                'id' => 'cv',
                'name' => 'Chuvash',
            ],
            [
                'id' => 'cy',
                'name' => 'Welsh',
            ],
            [
                'id' => 'da',
                'name' => 'Danish',
            ],
            [
                'id' => 'de_AT',
                'name' => 'German (Austria)',
            ],
            [
                'id' => 'de_CH',
                'name' => 'German (Switzerland)',
            ],
            [
                'id' => 'de_DE',
                'name' => 'German (Germany)',
            ],
            [
                'id' => 'de_LI',
                'name' => 'German (Liechtenstein)',
            ],
            [
                'id' => 'de_LU',
                'name' => 'German (Luxembourg)',
            ],
            [
                'id' => 'de',
                'name' => 'German',
            ],
            [
                'id' => 'div',
                'name' => 'Divehi',
            ],
            [
                'id' => 'dv',
                'name' => 'Divehi',
            ],
            [
                'id' => 'dz',
                'name' => 'Dzongkha',
            ],
            [
                'id' => 'ee',
                'name' => 'Ewe',
            ],
            [
                'id' => 'el',
                'name' => 'Greek',
            ],
            [
                'id' => 'en_AU',
                'name' => 'English (Australia)',
            ],
            [
                'id' => 'en_BZ',
                'name' => 'English (Belize)',
            ],
            [
                'id' => 'en_CA',
                'name' => 'English (Canada)',
            ],
            [
                'id' => 'en_CB',
                'name' => 'English (Caribbean)',
            ],
            [
                'id' => 'en_GB',
                'name' => 'English (United Kingdom)',
            ],
            [
                'id' => 'en_IE',
                'name' => 'English (Ireland)',
            ],
            [
                'id' => 'en_JM',
                'name' => 'English (Jamaica)',
            ],
            [
                'id' => 'en_NZ',
                'name' => 'English (New Zealand)',
            ],
            [
                'id' => 'en_PH',
                'name' => 'English (Philippines)',
            ],
            [
                'id' => 'en_TT',
                'name' => 'English (Trinidad & Tobago)',
            ],
            [
                'id' => 'en_US',
                'name' => 'English (United States)',
            ],
            [
                'id' => 'en_ZA',
                'name' => 'English (South Africa)',
            ],
            [
                'id' => 'en_ZW',
                'name' => 'English (Zimbabwe)',
            ],
            [
                'id' => 'en',
                'name' => 'English',
            ],
            [
                'id' => 'eo',
                'name' => 'Esperanto',
            ],
            [
                'id' => 'es_AR',
                'name' => 'Spanish (Argentina)',
            ],
            [
                'id' => 'es_BO',
                'name' => 'Spanish (Bolivia)',
            ],
            [
                'id' => 'es_CL',
                'name' => 'Spanish (Chile)',
            ],
            [
                'id' => 'es_CO',
                'name' => 'Spanish (Colombia)',
            ],
            [
                'id' => 'es_CR',
                'name' => 'Spanish (Costa Rica)',
            ],
            [
                'id' => 'es_DO',
                'name' => 'Spanish (Dominican Republic)',
            ],
            [
                'id' => 'es_EC',
                'name' => 'Spanish (Ecuador)',
            ],
            [
                'id' => 'es_ES',
                'name' => 'Spanish (Spain)',
            ],
            [
                'id' => 'es_GT',
                'name' => 'Spanish (Guatemala)',
            ],
            [
                'id' => 'es_HN',
                'name' => 'Spanish (Honduras)',
            ],
            [
                'id' => 'es_MX',
                'name' => 'Spanish (Mexico)',
            ],
            [
                'id' => 'es_NI',
                'name' => 'Spanish (Nicaragua)',
            ],
            [
                'id' => 'es_PA',
                'name' => 'Spanish (Panama)',
            ],
            [
                'id' => 'es_PE',
                'name' => 'Spanish (Peru)',
            ],
            [
                'id' => 'es_PR',
                'name' => 'Spanish (Puerto Rico)',
            ],
            [
                'id' => 'es_PY',
                'name' => 'Spanish (Paraguay)',
            ],
            [
                'id' => 'es_SV',
                'name' => 'Spanish (El Salvador)',
            ],
            [
                'id' => 'es_US',
                'name' => 'Spanish (United States)',
            ],
            [
                'id' => 'es_UY',
                'name' => 'Spanish (Uruguay)',
            ],
            [
                'id' => 'es_VE',
                'name' => 'Spanish (Venezuela)',
            ],
            [
                'id' => 'es',
                'name' => 'Spanish',
            ],
            [
                'id' => 'et',
                'name' => 'Estonian',
            ],
            [
                'id' => 'eu',
                'name' => 'Basque',
            ],
            [
                'id' => 'fa',
                'name' => 'Persian',
            ],
            [
                'id' => 'ff',
                'name' => 'Fulah',
            ],
            [
                'id' => 'fi',
                'name' => 'Finnish',
            ],
            [
                'id' => 'fj',
                'name' => 'Fijian',
            ],
            [
                'id' => 'fo',
                'name' => 'Faroese',
            ],
            [
                'id' => 'fr_BE',
                'name' => 'French (Belgium)',
            ],
            [
                'id' => 'fr_CA',
                'name' => 'French (Canada)',
            ],
            [
                'id' => 'fr_CH',
                'name' => 'French (Switzerland)',
            ],
            [
                'id' => 'fr_FR',
                'name' => 'French (France)',
            ],
            [
                'id' => 'fr_LU',
                'name' => 'French (Luxembourg)',
            ],
            [
                'id' => 'fr_MC',
                'name' => 'French (Monaco)',
            ],
            [
                'id' => 'fr',
                'name' => 'French',
            ],
            [
                'id' => 'fy',
                'name' => 'Frisian',
            ],
            [
                'id' => 'ga',
                'name' => 'Irish',
            ],
            [
                'id' => 'gd',
                'name' => 'Scottish Gaelic',
            ],
            [
                'id' => 'gl',
                'name' => 'Galician',
            ],
            [
                'id' => 'gn',
                'name' => 'Guarani',
            ],
            [
                'id' => 'gu',
                'name' => 'Gujarati',
            ],
            [
                'id' => 'gv',
                'name' => 'Manx',
            ],
            [
                'id' => 'ha',
                'name' => 'Hausa',
            ],
            [
                'id' => 'he',
                'name' => 'Hebrew',
            ],
            [
                'id' => 'hi',
                'name' => 'Hindi',
            ],
            [
                'id' => 'ho',
                'name' => 'Hiri Motu',
            ],
            [
                'id' => 'hr_BA',
                'name' => 'Croatian (Bosnia and Herzegovina)',
            ],
            [
                'id' => 'hr_HR',
                'name' => 'Croatian (Croatia)',
            ],
            [
                'id' => 'hr',
                'name' => 'Croatian',
            ],
            [
                'id' => 'ht',
                'name' => 'Haitian',
            ],
            [
                'id' => 'hu',
                'name' => 'Hungarian',
            ],
            [
                'id' => 'hy',
                'name' => 'Armenian',
            ],
            [
                'id' => 'hz',
                'name' => 'Herero',
            ],
            [
                'id' => 'ia',
                'name' => 'Interlingua',
            ],
            [
                'id' => 'id',
                'name' => 'Indonesian',
            ],
            [
                'id' => 'ie',
                'name' => 'Interlingue',
            ],
            [
                'id' => 'ig',
                'name' => 'Igbo',
            ],
            [
                'id' => 'ii',
                'name' => 'Sichuan Yi',
            ],
            [
                'id' => 'ik',
                'name' => 'Inupiaq',
            ],
            [
                'id' => 'in',
                'name' => 'Indonesian',
            ],
            [
                'id' => 'io',
                'name' => 'Ido',
            ],
            [
                'id' => 'is',
                'name' => 'Icelandic',
            ],
            [
                'id' => 'it_CH',
                'name' => 'Italian (Switzerland)',
            ],
            [
                'id' => 'it_IT',
                'name' => 'Italian (Italy)',
            ],
            [
                'id' => 'it',
                'name' => 'Italian',
            ],
            [
                'id' => 'iu',
                'name' => 'Inuktitut',
            ],
            [
                'id' => 'iw',
                'name' => 'Hebrew',
            ],
            [
                'id' => 'ja',
                'name' => 'Japanese',
            ],
            [
                'id' => 'ji',
                'name' => 'Yiddish',
            ],
            [
                'id' => 'jv',
                'name' => 'Javanese',
            ],
            [
                'id' => 'jw',
                'name' => 'Javanese',
            ],
            [
                'id' => 'ka',
                'name' => 'Georgian',
            ],
            [
                'id' => 'kg',
                'name' => 'Kongo',
            ],
            [
                'id' => 'ki',
                'name' => 'Kikuyu',
            ],
            [
                'id' => 'kj',
                'name' => 'Kwanyama',
            ],
            [
                'id' => 'kk',
                'name' => 'Kazakh',
            ],
            [
                'id' => 'kl',
                'name' => 'Kalaallisut',
            ],
            [
                'id' => 'km',
                'name' => 'Khmer',
            ],
            [
                'id' => 'kn',
                'name' => 'Kannada',
            ],
            [
                'id' => 'ko',
                'name' => 'Korean',
            ],
            [
                'id' => 'kok',
                'name' => 'Konkani',
            ],
            [
                'id' => 'kr',
                'name' => 'Kanuri',
            ],
            [
                'id' => 'ks',
                'name' => 'Kashmiri',
            ],
            [
                'id' => 'ku',
                'name' => 'Kurdish',
            ],
            [
                'id' => 'kv',
                'name' => 'Komi',
            ],
            [
                'id' => 'kw',
                'name' => 'Cornish',
            ],
            [
                'id' => 'ky',
                'name' => 'Kyrgyz',
            ],
            [
                'id' => 'kz',
                'name' => 'Kazakh',
            ],
            [
                'id' => 'la',
                'name' => 'Latin',
            ],
            [
                'id' => 'lb',
                'name' => 'Luxembourgish',
            ],
            [
                'id' => 'lg',
                'name' => 'Ganda',
            ],
            [
                'id' => 'li',
                'name' => 'Limburgish',
            ],
            [
                'id' => 'ln',
                'name' => 'Lingala',
            ],
            [
                'id' => 'lo',
                'name' => 'Lao',
            ],
            [
                'id' => 'ls',
                'name' => 'Sotho',
            ],
            [
                'id' => 'lt',
                'name' => 'Lithuanian',
            ],
            [
                'id' => 'lu',
                'name' => 'Luba-Katanga',
            ],
            [
                'id' => 'lv',
                'name' => 'Latvian',
            ],
            [
                'id' => 'mg',
                'name' => 'Malagasy',
            ],
            [
                'id' => 'mh',
                'name' => 'Marshallese',
            ],
            [
                'id' => 'mi',
                'name' => 'Māori',
            ],
            [
                'id' => 'mk',
                'name' => 'Macedonian',
            ],
            [
                'id' => 'ml',
                'name' => 'Malayalam',
            ],
            [
                'id' => 'mn',
                'name' => 'Mongolian',
            ],
            [
                'id' => 'mo',
                'name' => 'Moldavian',
            ],
            [
                'id' => 'mr',
                'name' => 'Marathi',
            ],
            [
                'id' => 'ms_BN',
                'name' => 'Malay (Brunei)',
            ],
            [
                'id' => 'ms_MY',
                'name' => 'Malay (Malaysia)',
            ],
            [
                'id' => 'ms',
                'name' => 'Malay',
            ],
            [
                'id' => 'mt',
                'name' => 'Maltese',
            ],
            [
                'id' => 'my',
                'name' => 'Burmese',
            ],
            [
                'id' => 'na',
                'name' => 'Nauru',
            ],
            [
                'id' => 'nb',
                'name' => 'Norwegian Bokmål',
            ],
            [
                'id' => 'nd',
                'name' => 'North Ndebele',
            ],
            [
                'id' => 'ne',
                'name' => 'Nepali',
            ],
            [
                'id' => 'ng',
                'name' => 'Ndonga',
            ],
            [
                'id' => 'nl_BE',
                'name' => 'Dutch (Belgium)',
            ],
            [
                'id' => 'nl_NL',
                'name' => 'Dutch (Netherlands)',
            ],
            [
                'id' => 'nl',
                'name' => 'Dutch',
            ],
            [
                'id' => 'nn',
                'name' => 'Norwegian Nynorsk',
            ],
            [
                'id' => 'no',
                'name' => 'Norwegian',
            ],
            [
                'id' => 'nr',
                'name' => 'South Ndebele',
            ],
            [
                'id' => 'ns',
                'name' => 'Northern Sotho',
            ],
            [
                'id' => 'nv',
                'name' => 'Navajo',
            ],
            [
                'id' => 'ny',
                'name' => 'Nyanja',
            ],
            [
                'id' => 'oc',
                'name' => 'Occitan',
            ],
            [
                'id' => 'oj',
                'name' => 'Ojibwe',
            ],
            [
                'id' => 'om',
                'name' => 'Oromo',
            ],
            [
                'id' => 'or',
                'name' => 'Oriya',
            ],
            [
                'id' => 'os',
                'name' => 'Ossetian',
            ],
            [
                'id' => 'pa',
                'name' => 'Punjabi',
            ],
            [
                'id' => 'pi',
                'name' => 'Pali',
            ],
            [
                'id' => 'pl',
                'name' => 'Polish',
            ],
            [
                'id' => 'ps',
                'name' => 'Pashto',
            ],
            [
                'id' => 'pt_BR',
                'name' => 'Portuguese (Brazil)',
            ],
            [
                'id' => 'pt_PT',
                'name' => 'Portuguese (Portugal)',
            ],
            [
                'id' => 'pt',
                'name' => 'Portuguese',
            ],
            [
                'id' => 'qu_BO',
                'name' => 'Quechua (Bolivia)',
            ],
            [
                'id' => 'qu_EC',
                'name' => 'Quechua (Ecuador)',
            ],
            [
                'id' => 'qu_PE',
                'name' => 'Quechua (Peru)',
            ],
            [
                'id' => 'qu',
                'name' => 'Quechua',
            ],
            [
                'id' => 'rm',
                'name' => 'Romansh',
            ],
            [
                'id' => 'rn',
                'name' => 'Kirundi',
            ],
            [
                'id' => 'ro',
                'name' => 'Romanian',
            ],
            [
                'id' => 'ru',
                'name' => 'Russian',
            ],
            [
                'id' => 'rw',
                'name' => 'Kinyarwanda',
            ],
            [
                'id' => 'sa',
                'name' => 'Sanskrit',
            ],
            [
                'id' => 'sb',
                'name' => 'Samoan',
            ],
            [
                'id' => 'sc',
                'name' => 'Sardinian',
            ],
            [
                'id' => 'sd',
                'name' => 'Sindhi',
            ],
            [
                'id' => 'se_FI',
                'name' => 'Sami (Finland)',
            ],
            [
                'id' => 'se_NO',
                'name' => 'Sami (Norway)',
            ],
            [
                'id' => 'se_SE',
                'name' => 'Sami (Sweden)',
            ],
            [
                'id' => 'se',
                'name' => 'Sami',
            ],
            [
                'id' => 'sg',
                'name' => 'Sango',
            ],
            [
                'id' => 'sh',
                'name' => 'Serbo-Croatian',
            ],
            [
                'id' => 'si',
                'name' => 'Sinhalese',
            ],
            [
                'id' => 'sk',
                'name' => 'Slovak',
            ],
            [
                'id' => 'sl',
                'name' => 'Slovenian',
            ],
            [
                'id' => 'sm',
                'name' => 'Samoan',
            ],
            [
                'id' => 'sn',
                'name' => 'Shona',
            ],
            [
                'id' => 'so',
                'name' => 'Somali',
            ],
            [
                'id' => 'sq',
                'name' => 'Albanian',
            ],
            [
                'id' => 'sr_BA',
                'name' => 'Serbian (Bosnia and Herzegovina)',
            ],
            [
                'id' => 'sr_SP',
                'name' => 'Serbian (Serbia)',
            ],
            [
                'id' => 'sr',
                'name' => 'Serbian',
            ],
            [
                'id' => 'ss',
                'name' => 'Swati',
            ],
            [
                'id' => 'st',
                'name' => 'Sesotho',
            ],
            [
                'id' => 'su',
                'name' => 'Sundanese',
            ],
            [
                'id' => 'sv_FI',
                'name' => 'Swedish (Finland)',
            ],
            [
                'id' => 'sv_SE',
                'name' => 'Swedish (Sweden)',
            ],
            [
                'id' => 'sv',
                'name' => 'Swedish',
            ],
            [
                'id' => 'sw',
                'name' => 'Swahili',
            ],
            [
                'id' => 'sx',
                'name' => 'Saxon',
            ],
            [
                'id' => 'syr',
                'name' => 'Syriac',
            ],
            [
                'id' => 'ta',
                'name' => 'Tamil',
            ],
            [
                'id' => 'te',
                'name' => 'Telugu',
            ],
            [
                'id' => 'tg',
                'name' => 'Tajik',
            ],
            [
                'id' => 'th',
                'name' => 'Thai',
            ],
            [
                'id' => 'ti',
                'name' => 'Tigrinya',
            ],
            [
                'id' => 'tk',
                'name' => 'Turkmen',
            ],
            [
                'id' => 'tl',
                'name' => 'Tagalog',
            ],
            [
                'id' => 'tn',
                'name' => 'Tswana',
            ],
            [
                'id' => 'to',
                'name' => 'Tonga',
            ],
            [
                'id' => 'tr',
                'name' => 'Turkish',
            ],
            [
                'id' => 'ts',
                'name' => 'Tswana',
            ],
            [
                'id' => 'tt',
                'name' => 'Tatar',
            ],
            [
                'id' => 'tw',
                'name' => 'Twi',
            ],
            [
                'id' => 'ty',
                'name' => 'Tahitian',
            ],
            [
                'id' => 'ug',
                'name' => 'Uighur',
            ],
            [
                'id' => 'uk',
                'name' => 'Ukrainian',
            ],
            [
                'id' => 'ur',
                'name' => 'Urdu',
            ],
            [
                'id' => 'us',
                'name' => 'Uzbek',
            ],
            [
                'id' => 'uz',
                'name' => 'Uzbek',
            ],
            [
                'id' => 've',
                'name' => 'Venda',
            ],
            [
                'id' => 'vi',
                'name' => 'Vietnamese',
            ],
            [
                'id' => 'vo',
                'name' => 'Volapük',
            ],
            [
                'id' => 'wa',
                'name' => 'Walloon',
            ],
            [
                'id' => 'wo',
                'name' => 'Wolof',
            ],
            [
                'id' => 'xh',
                'name' => 'Xhosa',
            ],
            [
                'id' => 'yi',
                'name' => 'Yiddish',
            ],
            [
                'id' => 'yo',
                'name' => 'Yoruba',
            ],
            [
                'id' => 'za',
                'name' => 'Zhuang',
            ],
            [
                'id' => 'zh_CN',
                'name' => 'Chinese (Simplified)',
            ],
            [
                'id' => 'zh_HK',
                'name' => 'Chinese (Hong Kong)',
            ],
            [
                'id' => 'zh_MO',
                'name' => 'Chinese (Macau)',
            ],
            [
                'id' => 'zh_SG',
                'name' => 'Chinese (Singapore)',
            ],
            [
                'id' => 'zh_TW',
                'name' => 'Chinese (Taiwan)',
            ],
            [
                'id' => 'zh',
                'name' => 'Chinese',
            ],
            [
                'id' => 'zu',
                'name' => 'Zulu',
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
