<?php defined('SYSPATH') or die('No direct script access.');
/**
* I18n class for php-gettext
*
* @package    I18n
* @category   Translations
* @author     Chema <chema@garridodiaz.com>
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
        return money_format(core::config('general.number_format'), $number);
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