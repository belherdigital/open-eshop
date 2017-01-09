<?php defined('SYSPATH') or die('No direct script access.');
/**
* I18n class for php-gettext
*
* @package    I18n
* @category   Translations
* @author     Chema <chema@open-classifieds.com>
* @copyright  (c) 2009-2014 Open Classifieds Team
* @license    GPL v3
*/


class OC_I18n extends Kohana_I18n {

    public static $locale;
    public static $locale_default = 'en_UK';
    public static $charset;
    public static $domain;
    
    /**
     * forces to use the dropin
     */
    public static $dropin = FALSE;
    

    /**
     * 
     * Initializes the php-gettext
     * Remember to load first php-gettext
     * @param string $locale
     * @param string $charset
     * @param string $domain
     */
    public static function initialize($locale = NULL, $charset = 'utf-8', $domain = 'messages')
    {        	
        if ($locale===NULL)
            $locale = self::$locale_default;

        /**
         * setting the statics so later we can access them from anywhere
         */

        //we allow to choose lang from the url
        if (Core::config('i18n.allow_query_language')==1)
        {            
            if(Core::get('language')!==NULL)
                $locale  = Core::get('language');
            elseif (Cookie::get('user_language')!==NULL)
                $locale = Cookie::get('user_language');

            //does the locale exists?
            if (array_key_exists($locale,self::get_languages()) )
                Cookie::set('user_language',$locale, Core::config('auth.lifetime'));
            else
                $locale = self::$locale_default;
        }
     
        self::$lang    = $locale;//used in i18n kohana
        self::$locale  = $locale;
        self::$charset = $charset;

        //use as domain the custom messages for the user
        if (file_exists($custom_po = self::get_language_custom_path($locale)))
            self::$domain  = 'custom-messages';
        else
            self::$domain  = $domain;

        //time zone set in the config
        date_default_timezone_set(Kohana::$config->load('i18n')->timezone);
        
        //Kohana core charset, used in the HTML templates as well
        Kohana::$charset  = self::$charset;
                
        /**
         * In Windows LC_ALL are not recognized sometimes.
         * So we check if LC_ALL is defined to avoid bugs,
         * and force using gettext
         */
        if(defined('LC_ALL'))
            $locale_res = setlocale(LC_ALL, self::$locale.'.'.self::$charset);
        else
            $locale_res = FALSE;

        // used with a function money_format
        // setlocale(LC_MONETARY, self::$locale); //no need anymore we use LC_ALL

        /**
         * check if gettext exists if not uses gettext dropin
         */
        if ( !function_exists('_') OR $locale_res===FALSE OR empty($locale_res) )
        {
            /**
             * gettext override
             * v 1.0.11
             * https://launchpad.net/php-gettext/
             * We load php-gettext here since Kohana_I18n tries to create the function __() function when we extend it.
             * PHP-gettext already does this.
             */
            require Kohana::find_file('vendor', 'php-gettext/gettext','inc'); 
            
            T_setlocale(LC_ALL, self::$locale.'.'.self::$charset);
            T_bindtextdomain(self::$domain,DOCROOT.'languages');
            T_bind_textdomain_codeset(self::$domain, self::$charset);
            T_textdomain(self::$domain);

            //force to use the gettext dropin
            self::$dropin = TRUE;
            
        }
        /**
         * gettext exists using fallback in case locale doesn't exists
         */
        else
        {
            bindtextdomain(self::$domain,DOCROOT.'languages');
            bind_textdomain_codeset(self::$domain, self::$charset);
            textdomain(self::$domain);
        }
        
    }    

    /**
     * get the language used in the HTML
     * @return string 
     */
    public static function html_lang()
    {
        return substr(core::config('i18n.locale'),0,2);
    }
    
    /**
     * get languages
     * @return array
     */
    public static function get_languages()
    {
        //read folders in theme folder
        $folder = DOCROOT.'languages';

        $languages = array();

        //check directory for langs
        foreach (new DirectoryIterator($folder) as $file) 
        {
            if($file->isDir() AND !$file->isDot())
            {
                $languages[$file->getFilename()] = $file->getFilename();
            }
        }
        
        //alphabetical order
        asort($languages);

        return $languages;
    }

    /**
     * get the path for the original/base translation path
     * @param string $language
     * @return string
     */
    public static function get_language_path($language = NULL)
    {
        if ($language===NULL)
            return DOCROOT.'languages/messages.po';
        else
        {
            if (file_exists($custom_po = self::get_language_custom_path($language)))
                return $custom_po;
            else
                return DOCROOT.'languages/'.$language.'/LC_MESSAGES/messages.po';
        }
    }

    /**
     * get the path for the custom translation path
     * @param string $language
     * @return array
     */
    public static function get_language_custom_path($language)
    {
        return DOCROOT.'languages/'.$language.'/LC_MESSAGES/custom-messages.po';
    }

    /**
     * 
     * Override normal translate
     * @param string $string to translate
     * @param string $lang does nothing, legacy
     */
    public static function get($string, $lang = NULL)
    {
        //using the gettext dropin forced
        if (self::$dropin === TRUE)
            return _gettext($string);
        elseif (is_array($string))
            return _(implode(',',$string));
        else
            return _($string);
    }

    public static $locales = array(
                                    "aa" => "Afar",
                                    "ab" => "Abkhazian",
                                    "ae" => "Avestan",
                                    "af" => "Afrikaans",
                                    "ak" => "Akan",
                                    "am" => "Amharic",
                                    "an" => "Aragonese",
                                    "ar" => "Arabic",
                                    "as" => "Assamese",
                                    "av" => "Avaric",
                                    "ay" => "Aymara",
                                    "az" => "Azerbaijani",
                                    "ba" => "Bashkir",
                                    "be" => "Belarusian",
                                    "bg" => "Bulgarian",
                                    "bh" => "Bihari",
                                    "bi" => "Bislama",
                                    "bm" => "Bambara",
                                    "bn" => "Bengali",
                                    "bo" => "Tibetan",
                                    "br" => "Breton",
                                    "bs" => "Bosnian",
                                    "ca" => "Catalan",
                                    "ce" => "Chechen",
                                    "ch" => "Chamorro",
                                    "co" => "Corsican",
                                    "cr" => "Cree",
                                    "cs" => "Czech",
                                    "cu" => "Church Slavic",
                                    "cv" => "Chuvash",
                                    "cy" => "Welsh",
                                    "da" => "Danish",
                                    "de" => "German",
                                    "dv" => "Dhivehi",
                                    "dz" => "Dzongkha",
                                    "ee" => "Ewe",
                                    "el" => "Greek",
                                    "en" => "English",
                                    "eo" => "Esperanto",
                                    "es" => "Spanish",
                                    "et" => "Estonian",
                                    "eu" => "Basque",
                                    "fa" => "Persian",
                                    "ff" => "Fulah",
                                    "fi" => "Finnish",
                                    "fj" => "Fijian",
                                    "fo" => "Faroese",
                                    "fr" => "French",
                                    "fy" => "Western Frisian",
                                    "ga" => "Irish",
                                    "gd" => "Gaelic",
                                    "gl" => "Galician",
                                    "gn" => "Guarani",
                                    "gu" => "Gujarati",
                                    "gv" => "Manx",
                                    "ha" => "Hausa",
                                    "he" => "Hebrew",
                                    "hi" => "Hindi",
                                    "ho" => "Hiri Motu",
                                    "hr" => "Croatian",
                                    "ht" => "Haitian",
                                    "hu" => "Hungarian",
                                    "hy" => "Armenian",
                                    "hz" => "Herero",
                                    "ia" => "Interlingua",
                                    "id" => "Indonesian",
                                    "ie" => "Interlingue",
                                    "ig" => "Igbo",
                                    "ii" => "Sichuan Yi",
                                    "ik" => "Inupiaq",
                                    "in" => "Indonesian",
                                    "io" => "Ido",
                                    "is" => "Icelandic",
                                    "it" => "Italian",
                                    "iu" => "Inuktitut",
                                    "iw" => "Hebrew",
                                    "ja" => "Japanese",
                                    "ji" => "Yiddish",
                                    "jv" => "Javanese",
                                    "jw" => "Javanese",
                                    "ka" => "Georgian",
                                    "kg" => "Kongo",
                                    "ki" => "Kikuyu",
                                    "kj" => "Kuanyama",
                                    "kk" => "Kazakh",
                                    "kl" => "Kalaallisut",
                                    "km" => "Central Khmer",
                                    "kn" => "Kannada",
                                    "ko" => "Korean",
                                    "kr" => "Kanuri",
                                    "ks" => "Kashmiri",
                                    "ku" => "Kurdish",
                                    "kv" => "Komi",
                                    "kw" => "Cornish",
                                    "ky" => "Kirghiz",
                                    "la" => "Latin",
                                    "lb" => "Luxembourgish",
                                    "lg" => "Ganda",
                                    "li" => "Limburgan",
                                    "ln" => "Lingala",
                                    "lo" => "Lao",
                                    "lt" => "Lithuanian",
                                    "lu" => "Luba-Katanga",
                                    "lv" => "Latvian",
                                    "mg" => "Malagasy",
                                    "mh" => "Marshallese",
                                    "mi" => "Maori",
                                    "mk" => "Macedonian",
                                    "ml" => "Malayalam",
                                    "mn" => "Mongolian",
                                    "mo" => "Moldavian",
                                    "mr" => "Marathi",
                                    "ms" => "Malay",
                                    "mt" => "Maltese",
                                    "my" => "Burmese",
                                    "na" => "Nauru",
                                    "nb" => "Norwegian BokmÃ¥l",
                                    "nd" => "North Ndebele",
                                    "ne" => "Nepali",
                                    "ng" => "Ndonga",
                                    "nl" => "Dutch",
                                    "nn" => "Norwegian Nynorsk",
                                    "no" => "Norwegian",
                                    "nr" => "South Ndebele",
                                    "nv" => "Navajo",
                                    "ny" => "Nyanja",
                                    "oc" => "Occitan",
                                    "oj" => "Ojibwa",
                                    "om" => "Oromo",
                                    "or" => "Oriya",
                                    "os" => "Ossetian",
                                    "pa" => "Panjabi",
                                    "pi" => "Pali",
                                    "pl" => "Polish",
                                    "ps" => "Pushto",
                                    "pt" => "Portuguese",
                                    "qu" => "Quechua",
                                    "rm" => "Romansh",
                                    "rn" => "Rundi",
                                    "ro" => "Romanian",
                                    "ru" => "Russian",
                                    "rw" => "Kinyarwanda",
                                    "sa" => "Sanskrit",
                                    "sc" => "Sardinian",
                                    "sd" => "Sindhi",
                                    "se" => "Northern Sami",
                                    "sg" => "Sango",
                                    "sh" => "Serbo-Croatian",
                                    "si" => "Sinhala",
                                    "sk" => "Slovak",
                                    "sl" => "Slovenian",
                                    "sm" => "Samoan",
                                    "sn" => "Shona",
                                    "so" => "Somali",
                                    "sq" => "Albanian",
                                    "sr" => "Serbian",
                                    "ss" => "Swati",
                                    "st" => "Southern Sotho",
                                    "su" => "Sundanese",
                                    "sv" => "Swedish",
                                    "sw" => "Swahili",
                                    "ta" => "Tamil",
                                    "te" => "Telugu",
                                    "tg" => "Tajik",
                                    "th" => "Thai",
                                    "ti" => "Tigrinya",
                                    "tk" => "Turkmen",
                                    "tl" => "Tagalog",
                                    "tn" => "Tswana",
                                    "to" => "Tonga",
                                    "tr" => "Turkish",
                                    "ts" => "Tsonga",
                                    "tt" => "Tatar",
                                    "tw" => "Twi",
                                    "ty" => "Tahitian",
                                    "ug" => "Uighur",
                                    "uk" => "Ukrainian",
                                    "ur" => "Urdu",
                                    "uz" => "Uzbek",
                                    "ve" => "Venda",
                                    "vi" => "Vietnamese",
                                    "vo" => "VolapÃ¼k",
                                    "wa" => "Walloon",
                                    "wo" => "Wolof",
                                    "xh" => "Xhosa",
                                    "yi" => "Yiddish",
                                    "yo" => "Yoruba",
                                    "za" => "Zhuang",
                                    "zh" => "Chinese",
                                    "zu" => "Zulu");

    public static function get_display_language($locale)
    {
        if (strlen($locale)>2)
            $locale = substr($locale,0,2);

        return (isset(self::$locales[$locale]))?self::$locales[$locale]:$locale;
    }

    public static function get_facebook_language($locale)
    {
        switch ($locale) {
            case 'ar':
                return 'ar_AR';
                break;
            case 'bn_BD':
                return 'bn_IN';
                break;
            case 'in_ID':
                return 'id_ID';
                break;
            case 'no_NO':
                return 'nb_NO';
                break;
            case 'sn_ZW':
                return 'en_US';
                break;
            case 'tr':
                return 'tr_TR';
                break;
            
            default:
                return $locale;
                break;
        }
    }
    
    /**
     * returns the number in the locale format
     * @param  float $number 
     * @return string
     */
    public static function money_format($number)
    {
        $format = core::config('general.number_format');

        //in case not any format standard
        if ($format == NULL)
            $format = '%n';

        if (in_array($format, array_keys(self::$currencies)))
            return self::format_currency($number,$format);
        else
            return money_format($format, $number);
    }

    /**
     * A list of the ISO 4217 currency codes with symbol,format and symbol order
     * 
     * Symbols from 
     * http://character-code.com/currency-html-codes.php
     * http://www.phpclasses.org/browse/file/2054.html
     * https://github.com/yiisoft/yii/blob/633e54866d54bf780691baaaa4a1f847e8a07e23/framework/i18n/data/en_us.php
     * 
     * Formats from 
     * http://www.joelpeterson.com/blog/2011/03/formatting-over-100-currencies-in-php/
     * 
     * Array with key as ISO 4217 currency code
     * 0 - Currency Symbol if there's
     * 1 - Round
     * 2 - Thousands separator
     * 3 - Decimal separator
     * 4 - 0 = symbol in front OR 1 = symbol after currency
     */
    public static $currencies = array(
        'ARS' => array(NULL,2,',','.',0),          //  Argentine Peso
        'AMD' => array(NULL,2,'.',',',0),          //  Armenian Dram
        'AWG' => array(NULL,2,'.',',',0),          //  Aruban Guilder
        'AUD' => array('AU$',2,'.',' ',0),          //  Australian Dollar
        'BSD' => array(NULL,2,'.',',',0),          //  Bahamian Dollar
        'BHD' => array(NULL,3,'.',',',0),          //  Bahraini Dinar
        'BDT' => array(NULL,2,'.',',',0),          //  Bangladesh, Taka
        'BZD' => array(NULL,2,'.',',',0),          //  Belize Dollar
        'BMD' => array(NULL,2,'.',',',0),          //  Bermudian Dollar
        'BOB' => array(NULL,2,'.',',',0),          //  Bolivia, Boliviano
        'BAM' => array(NULL,2,'.',',',0),          //  Bosnia and Herzegovina, Convertible Marks
        'BWP' => array(NULL,2,'.',',',0),          //  Botswana, Pula
        'BRL' => array('R$',2,',','.',0),          //  Brazilian Real
        'BND' => array(NULL,2,'.',',',0),          //  Brunei Dollar
        'CAD' => array('CA$',2,'.',',',0),          //  Canadian Dollar
        'KYD' => array(NULL,2,'.',',',0),          //  Cayman Islands Dollar
        'CLP' => array(NULL,0,'','.',0),           //  Chilean Peso
        'CNY' => array('CN&yen;',2,'.',',',0),          //  China Yuan Renminbi
        'COP' => array(NULL,2,',','.',0),          //  Colombian Peso
        'CRC' => array(NULL,2,',','.',0),          //  Costa Rican Colon
        'HRK' => array(NULL,2,',','.',0),          //  Croatian Kuna
        'CUC' => array(NULL,2,'.',',',0),          //  Cuban Convertible Peso
        'CUP' => array(NULL,2,'.',',',0),          //  Cuban Peso
        'CYP' => array(NULL,2,'.',',',0),          //  Cyprus Pound
        'CZK' => array('K&#269;',0,'',' ',1),          //  Czech Koruna
        'DKK' => array(NULL,2,',','.',0),          //  Danish Krone
        'DOP' => array(NULL,2,'.',',',0),          //  Dominican Peso
        'XCD' => array('EC$',2,'.',',',0),          //  East Caribbean Dollar
        'EGP' => array(NULL,2,'.',',',0),          //  Egyptian Pound
        'SVC' => array(NULL,2,'.',',',0),          //  El Salvador Colon
        'EUR' => array('&euro;',2,',','.',0),          //  Euro
        'ESP' => array('&euro;',2,',','.',1),          //  Euro in spanish format
        'GHC' => array(NULL,2,'.',',',0),          //  Ghana, Cedi
        'GIP' => array(NULL,2,'.',',',0),          //  Gibraltar Pound
        'GTQ' => array(NULL,2,'.',',',0),          //  Guatemala, Quetzal
        'HNL' => array(NULL,2,'.',',',0),          //  Honduras, Lempira
        'HKD' => array('HK$',2,'.',',',0),          //  Hong Kong Dollar
        'HUF' => array('HK$',0,'','.',0),           //  Hungary, Forint
        'ISK' => array('kr',0,'','.',1),           //  Iceland Krona
        'INR' => array('&#2352;',2,'.',',',0),          //  Indian Rupee ₹
        'IDR' => array(NULL,2,',','.',0),          //  Indonesia, Rupiah
        'IRR' => array(NULL,2,'.',',',0),          //  Iranian Rial
        'JMD' => array(NULL,2,'.',',',0),          //  Jamaican Dollar
        'JPY' => array('&yen;',0,'',',',0),           //  Japan, Yen
        'JOD' => array(NULL,3,'.',',',0),          //  Jordanian Dinar
        'KES' => array(NULL,2,'.',',',0),          //  Kenyan Shilling
        'KWD' => array(NULL,3,'.',',',0),          //  Kuwaiti Dinar
        'LVL' => array(NULL,2,'.',',',0),          //  Latvian Lats
        'LBP' => array(NULL,0,'',' ',0),           //  Lebanese Pound
        'LTL' => array('Lt',2,',',' ',1),          //  Lithuanian Litas
        'MKD' => array(NULL,2,'.',',',0),          //  Macedonia, Denar
        'MYR' => array(NULL,2,'.',',',0),          //  Malaysian Ringgit
        'MTL' => array(NULL,2,'.',',',0),          //  Maltese Lira
        'MUR' => array(NULL,0,'',',',0),           //  Mauritius Rupee
        'MXN' => array('MX$',2,'.',',',0),          //  Mexican Peso
        'MZM' => array(NULL,2,',','.',0),          //  Mozambique Metical
        'NPR' => array(NULL,2,'.',',',0),          //  Nepalese Rupee
        'ANG' => array(NULL,2,'.',',',0),          //  Netherlands Antillian Guilder
        'ILS' => array('&#8362;',2,'.',',',0),          //  New Israeli Shekel ₪
        'TRY' => array(NULL,2,'.',',',0),          //  New Turkish Lira
        'NZD' => array('NZ$',2,'.',',',0),          //  New Zealand Dollar
        'NOK' => array('kr',2,',','.',1),          //  Norwegian Krone
        'PKR' => array(NULL,2,'.',',',0),          //  Pakistan Rupee
        'PEN' => array(NULL,2,'.',',',0),          //  Peru, Nuevo Sol
        'UYU' => array(NULL,2,',','.',0),          //  Peso Uruguayo
        'PHP' => array(NULL,2,'.',',',0),          //  Philippine Peso
        'PLN' => array(" zł",2,'.',' ',1),          //  Poland, Zloty
        'GBP' => array('&pound;',2,'.',',',0),          //  Pound Sterling
        'OMR' => array(NULL,3,'.',',',0),          //  Rial Omani
        'RON' => array(NULL,2,',','.',0),          //  Romania, New Leu
        'ROL' => array(NULL,2,',','.',0),          //  Romania, Old Leu
        'RUB' => array(NULL,2,',','.',0),          //  Russian Ruble
        'SAR' => array(NULL,2,'.',',',0),          //  Saudi Riyal
        'SGD' => array(NULL,2,'.',',',0),          //  Singapore Dollar
        'SKK' => array(NULL,2,',',' ',0),          //  Slovak Koruna
        'SIT' => array(NULL,2,',','.',0),          //  Slovenia, Tolar
        'ZAR' => array('R',2,'.',' ',0),          //  South Africa, Rand
        'KRW' => array('&#8361;',0,'',',',0),           //  South Korea, Won ₩
        'SZL' => array(NULL,2,'.',', ',0),         //  Swaziland, Lilangeni
        'SEK' => array('kr',2,',','.',1),          //  Swedish Krona
        'CHF' => array('SFr ',2,'.','\'',0),         //  Swiss Franc 
        'TZS' => array(NULL,2,'.',',',0),          //  Tanzanian Shilling
        'THB' => array('&#3647;',2,'.',',',1),          //  Thailand, Baht ฿
        'TOP' => array(NULL,2,'.',',',0),          //  Tonga, Paanga
        'AED' => array(NULL,2,'.',',',0),          //  UAE Dirham
        'UAH' => array(NULL,2,',',' ',0),          //  Ukraine, Hryvnia
        'USD' => array('$',2,'.',',',0),          //  US Dollar
        'VUV' => array(NULL,0,'',',',0),           //  Vanuatu, Vatu
        'VEF' => array(NULL,2,',','.',0),          //  Venezuela Bolivares Fuertes
        'VEB' => array(NULL,2,',','.',0),          //  Venezuela, Bolivar
        'VND' => array('&#x20ab;',0,'','.',0),           //  Viet Nam, Dong ₫
        'ZWD' => array(NULL,2,'.',' ',0),          //  Zimbabwe Dollar
    );
    
    /**
     * Format the currency value according to the formatter rules.
     * @param  float $number  The numeric currency value.
     * @param  string $currency The 3-letter ISO 4217 currency code indicating the currency to use.
     * @return string    representing the formatted currency value.      
     */
    public static function format_currency($number,$currency = 'USD')
    {
        //in case we dont have the currency...
        if (!in_array($currency, array_keys(self::$currencies)))
            return number_format($number).' '.$currency;

        //rupees weird format
        if ($currency == 'INR')
            $number = self::format_inr($number);
        else 
            $number = number_format($number,self::$currencies[$currency][1],self::$currencies[$currency][2],self::$currencies[$currency][3]);

        //no symbol using default code
        if (self::$currencies[$currency][0] === NULL)
            self::$currencies[$currency][0] = $currency;

        //adding the symbol in the back
        if (self::$currencies[$currency][4]===1)
            $number.= self::$currencies[$currency][0];
        //normally in front
        else
            $number = self::$currencies[$currency][0].$number;

        return $number;
    }

    /**
     * formats to indians rupees ##,##,###.##
     * refactored from http://www.joelpeterson.com/blog/2011/03/formatting-over-100-currencies-in-php/
     * @param  float $input money
     * @return string        formated currency
     */
    public static function format_inr($input)
    {
        $dec = '';
        $pos = strpos($input, '.');
        if ($pos !== FALSE)
        {
            //decimals
            $dec = substr(round(substr($input,$pos),2),1);
            $input = substr($input,0,$pos);
        }

        $num = substr($input,-3); //get the last 3 digits
        $input = substr($input,0, -3); //omit the last 3 digits already stored in $num
        while(strlen($input) > 0) //loop the process - further get digits 2 by 2
        {
            $num = substr($input,-2).','.$num;
            $input = substr($input,0,-2);
        }
        return $num . $dec;
    }
    
    /**
     * formats measurement system (by defautl metric)
     * @param  float    $number number
     * @return string   formated measurement
     */
    public static function format_measurement($number)
    {
        if (Core::config('general.measurement')=="imperial") {
            $str = Num::round($number*0.621371192, 1). ' mi';
        }
        else {
            $str = Num::round($number, 1).' km';
        }
        
        return $str;
    }

    /**
     * format number without the currency symbol
     * @param  float    $number number
     * @return string    formated price
     */
    public static function format_currency_without_symbol($number)
    {
        $currency = core::config('general.number_format');
        
        //we format based on the currency, correct round and decimal point. No thousands separator.
        if (in_array($currency, array_keys(self::$currencies)))
            $number = number_format($number,self::$currencies[$currency][1],self::$currencies[$currency][2],'');
        
        return $number;
    }

    /**
     * get ISO 4217 international currency code
     * @return string    international currency code
     */
    public static function get_intl_currency_symbol()
    {
        $number_format = core::config('general.number_format');

        if (in_array($number_format, array_keys(self::$currencies)))
        {
            // return since it's a ISO 4217 currency codes
            return $number_format;
        }
        else
        {
            list( , , $intl_currency_symbol) = array_values(localeconv());
            return $intl_currency_symbol;
        }
    }

    /**
     * get country code using IP
     * @param  string $ip 
     * @return string     
     */
    public static function ip_country_code($ip = NULL)
    {
        $country_code = NULL;

        //if got the country via cloudflare and not trying to get another country...
        if (!empty($_SERVER["HTTP_CF_IPCOUNTRY"]) AND $ip === NULL) 
        {
            $country_code = $_SERVER["HTTP_CF_IPCOUNTRY"];
        } 
        else
        {
            if ($ip === NULL)
                $ip = Request::$client_ip;

            $country_info = self::ip_details($ip);

            if ($country_info != FALSE)
                $country_code = $country_info['country_code'];
        }

        return $country_code;
    }

    /**
     * get ip geo information using apis from 3rd parties
     * @param  string $ip 
     * @return array     
     */
    public static function ip_details($ip = NULL) 
    {
        if ($ip === NULL)
            $ip = Request::$client_ip;

        if (Kohana::$environment === Kohana::DEVELOPMENT)
            $ip = '8.8.8.8';

        //list of providers to return IP information for free
        $providers = array( 'ipinfo' => array('url'          => "http://ipinfo.io/{$ip}/json",
                                              'country_code' => 'country',
                                              'country'      => 'country',
                                              'city'         => 'city',
                                              'region'       => 'region',
                                              'postal_code'  => 'postal',
                                              'lat'          => 'loc', //"loc": "37.3860,-122.0838",
                                              'lon'          => 'loc'), 

                            'ip-api' => array('url'          => "http://ip-api.com/json/{$ip}",
                                              'country_code' => 'countryCode',
                                              'country'      => 'country',
                                              'city'         => 'city',
                                              'region'       => 'regionName',
                                              'postal_code'  => 'zip',
                                              'lat'          => 'lat',
                                              'lon'          => 'lon'), 

                            'telize' => array('url'          => "http://www.telize.com/geoip/{$ip}",
                                              'country_code' => 'country_code',
                                              'country'      => 'country',
                                              'city'         => 'city',
                                              'region'       => 'region',
                                              'postal_code'  => 'postal_code',
                                              'lat'          => 'latitude',
                                              'lon'          => 'longitude'), 
                            
                            );
        
        //get 1 provider randomly
        $provider = array_rand($providers);
        $provider = $providers[$provider];

        //get the info
        $result = Core::curl_get_contents($provider['url']);
        if ($result==FALSE)
            return FALSE;

        $result = json_decode($result,TRUE);

        //get lat and long in case are in the same ipinfo
        if ($provider['lat'] == $provider['lon'])
        {
            $coords = explode(',',$result[$provider['lat']]);
            //die(var_dump($coords));
            $lat = (float) $coords[0];
            $lon = (float) $coords[1];
        }
        //normal
        else
        {
            $lat = $result[$provider['lat']];
            $lon = $result[$provider['lon']];
        }
        
        //echo $provider['url'];
        //return details
        $details = array( 'country_code' => $result[$provider['country_code']],
                          'country'      => $result[$provider['country']],
                          'city'         => $result[$provider['city']],
                          'region'       => $result[$provider['region']],
                          'postal_code'  => $result[$provider['postal_code']],
                          'lat'          => $lat,
                          'lon'          => $lon);
        return $details;
    }
    
}//end i18n


/**
 * FROM: http://www.php.net/manual/en/function.money-format.php  
 *  We use this to avoid errors that Windows produces. 
 *  money_format function is not supported on Windows OS machines
 */
if ( !function_exists('money_format') )
{
    function money_format($format, $number) 
    { 
        return number_format($number, 2); 
    } 
}

/**
 * Kohana translation/internationalization function. The PHP function
 * [strtr](http://php.net/strtr) is used for replacing parameters.
 *
 *    __('Welcome back, :user', array(':user' => $username));
 *
 * [!!] The target language is defined by [I18n::$lang].
 *
 * @uses    I18n::get
 * @param   string  $string text to translate
 * @param   array   $values values to replace in the translated text
 * @param   string  $lang   source language
 * @return  string
 */
function _e($string, array $values = NULL, $lang = 'en-us')
{
    $original_string   = $string;
    $translated_string = $original_string;

    if ($lang !== I18n::$lang)
    {
        // The message and target languages are different
        // Get the translation for this message
        $string = I18n::get($string);
        $translated_string = $string;
    }

    $string = empty($values) ? $string : strtr($string, $values);

    if (Core::get('edit_translation') !== '1')
        return $string;

    $attributes = [
        'class' => 'editable',
        'data-pk' => $original_string,
        'data-name' => $original_string,
        'data-title' => $original_string,
        'data-value' => $translated_string,
        'data-url' => Route::url('oc-panel', ['controller' => 'translations', 'action' => 'replace' , 'id'=> I18n::$lang]),
    ];

    $compiled_attributes = '';

    foreach ($attributes as $key => $value)
    {
        $compiled_attributes .= ' '.$key;
        $compiled_attributes .= '="'.htmlspecialchars( (string) $value, ENT_QUOTES, Kohana::$charset, TRUE).'"';
    }

    return '<span '.$compiled_attributes.'>'.$string.'</span>';
}
