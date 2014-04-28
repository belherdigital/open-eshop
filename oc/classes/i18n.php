<?php defined('SYSPATH') or die('No direct script access.');
/**
* I18n class for php-gettext
*
* @package    I18n
* @category   Translations
* @author     Chema <chema@open-classifieds.com>
* @copyright  (c) 2009-2013 Open Classifieds Team
* @license    GPL v3
*/


class I18n extends Kohana_I18n {

    public static $locale;
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
    public static function initialize($locale = 'en_UK', $charset = 'utf-8', $domain = 'messages')
    {        	
        /**
         * setting the statics so later we can access them from anywhere
         */
        
        //we allow to choose lang from the url
        if (Core::config('i18n.allow_query_language')==1)
        {
            if(Core::get('language')!==NULL)
            {
                $locale  = Core::get('language');
            }
            elseif (Cookie::get('user_language')!==NULL)
            {
                $locale = Cookie::get('user_language');
            }
            Cookie::set('user_language',$locale, Core::config('auth.lifetime'));
        }
     
        self::$lang    = $locale;//used in i18n kohana
        self::$locale  = $locale;
        self::$charset = $charset;
        self::$domain  = $domain;
        
        //time zone set in the config
        date_default_timezone_set(Kohana::$config->load('i18n')->timezone);
        
        //Kohana core charset, used in the HTML templates as well
        Kohana::$charset  = self::$charset;
                
        /**
         * In Windows LC_ALL are not recognized sometimes
         * So we check if LC_ALL is defined to avoid bugs,
         * and force using gettext
         */
        if(defined('LC_ALL'))
            $locale_res = setlocale(LC_ALL, self::$locale);
        else
            $locale_res = FALSE;

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
            
            T_setlocale(LC_ALL, self::$locale);
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

        return $languages;
    }

    /**
     * get the path for the original/base translation path
     * @return array
     */
    public static function get_language_path()
    {
        return DOCROOT.'languages/messages.po';
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
        else
            return _($string);
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
        'CZK' => array('Kc',2,'.',',',1),          //  Czech Koruna
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
        'PLN' => array(NULL,2,'.',' ',0),          //  Poland, Zloty
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