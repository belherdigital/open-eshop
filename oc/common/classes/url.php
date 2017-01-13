<?php defined('SYSPATH') or die('No direct script access.');
/**
 * URL helper class.
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class URL extends Kohana_URL {


    /**
     * Convert a phrase to a URL-safe title. Overwriten original to ascii only depending on language
     *
     *     echo URL::title('My Blog Post'); // "my-blog-post"
     *
     * @param   string   $title       Phrase to convert
     * @param   string   $separator   Word separator (any single character)
     * @param   boolean  $ascii_only  Transliterate to ASCII?
     * @return  string
     * @uses    UTF8::transliterate_to_ascii
     */
    public static function title($title, $separator = '-', $ascii_only = NULL)
    {
        //using the slugify function to get rid and replace special chars
        $res = self::slugify($title,$separator);
   
        //in case sludigy returns empty because the usage of CJK characters....somewhere in the title
        return (strlen($res)==0 AND strlen($title)>0) ? self::cjk_slugify($title,$separator):$res;      
    }

    /**
     * Fetches an absolute site URL based on a URI segment.
     *
     *     echo URL::site('foo/bar');
     *
     * @param   string  $uri        Site URI to convert
     * @param   mixed   $protocol   Protocol string or [Request] class to use protocol from
     * @param   boolean $index      Include the index_page in the URL
     * @return  string
     * @uses    URL::base
     */
    public static function site($uri = '', $protocol = NULL, $index = TRUE)
    {
        // Chop off possible scheme, host, port, user and pass parts
        $path = preg_replace('~^[-a-z0-9+.]++://[^/]++/?~', '', trim($uri, '/'));

        // Encode all non-ASCII characters, as per RFC 1738
        if(mb_detect_encoding($path,'ASCII')===TRUE)
        {
            $path = parent::title($path, '-', TRUE);
        }

        // Concat the URL
        return URL::base($protocol, $index).$path;
    }

    /**
     * returns the current url we are visiting with querystring included
     * @return string
     */
    public static function current()
    {
        //in case is  CLI
        if (!isset($_SERVER['QUERY_STRING']) OR Request::$current == NULL OR defined('SUPPRESS_REQUEST'))
            return URL::base();

        try {
            //default case using KO functions
            $query_string = (isset($_SERVER['QUERY_STRING']) AND !empty($_SERVER['QUERY_STRING']))? '?'.$_SERVER['QUERY_STRING']:'';
            return URL::base().Request::current()->uri().$query_string;
        } catch (Exception $e) {
            //in case theres no request
            return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
    }

    /**
     * gets the domain name from a full domain, strips subdomains.
     * @param  string $domain 
     * @return string         
     */
    public static function get_domain($domain)
    {
        if (!class_exists('Novutec\DomainParser\Parser'))
            require Kohana::find_file('vendor/DomainParser', 'Parser');

        $Parser = new Novutec\DomainParser\Parser();
        $fqdn_domain = $Parser->parse($domain)->fqdn;

        if (!empty($fqdn_domain) AND $fqdn_domain != NULL )
            return $fqdn_domain;
        //failback in case the FQDN parser fails see https://github.com/open-classifieds/open-eshop/issues/508
        elseif ( preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
            return $regs['domain']; 
           
        //something went really wrong :S
        return FALSE;
    }


    /**
     * from https://github.com/keyvanakbary/slugifier/blob/v3.0.0/src/slugifier.php
     */

    public static $chars_map = array(
        // Latin
        '°' => '0', 'æ' => 'ae', 'ǽ' => 'ae', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Å' => 'A', 'Ǻ' => 'A',
        'Ă' => 'A', 'Ǎ' => 'A', 'Æ' => 'AE', 'Ǽ' => 'AE', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'å' => 'a',
        'ǻ' => 'a', 'ă' => 'a', 'ǎ' => 'a', 'ª' => 'a', '@' => 'at', 'Ĉ' => 'C', 'Ċ' => 'C', 'ĉ' => 'c', 'ċ' => 'c',
        '©' => 'c', 'Ð' => 'Dj', 'Đ' => 'D', 'ð' => 'dj', 'đ' => 'd', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
        'Ĕ' => 'E', 'Ė' => 'E', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ĕ' => 'e', 'ė' => 'e', 'ƒ' => 'f',
        'Ĝ' => 'G', 'Ġ' => 'G', 'ĝ' => 'g', 'ġ' => 'g', 'Ĥ' => 'H', 'Ħ' => 'H', 'ĥ' => 'h', 'ħ' => 'h', 'Ì' => 'I',
        'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Ǐ' => 'I', 'Į' => 'I', 'Ĳ' => 'IJ', 'ì' => 'i',
        'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ĩ' => 'i', 'ĭ' => 'i', 'ǐ' => 'i', 'į' => 'i', 'ĳ' => 'ij', 'Ĵ' => 'J',
        'ĵ' => 'j', 'Ĺ' => 'L', 'Ľ' => 'L', 'Ŀ' => 'L', 'ĺ' => 'l', 'ľ' => 'l', 'ŀ' => 'l', 'Ñ' => 'N', 'ñ' => 'n',
        'ŉ' => 'n', 'Ò' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ō' => 'O', 'Ŏ' => 'O', 'Ǒ' => 'O', 'Ő' => 'O', 'Ơ' => 'O',
        'Ø' => 'O', 'Ǿ' => 'O', 'Œ' => 'OE', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ō' => 'o', 'ŏ' => 'o', 'ǒ' => 'o',
        'ő' => 'o', 'ơ' => 'o', 'ø' => 'o', 'ǿ' => 'o', 'º' => 'o', 'œ' => 'oe', 'Ŕ' => 'R', 'Ŗ' => 'R', 'ŕ' => 'r',
        'ŗ' => 'r', 'Ŝ' => 'S', 'Ș' => 'S', 'ŝ' => 's', 'ș' => 's', 'ſ' => 's', 'Ţ' => 'T', 'Ț' => 'T', 'Ŧ' => 'T',
        'Þ' => 'TH', 'ţ' => 't', 'ț' => 't', 'ŧ' => 't', 'þ' => 'th', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ũ' => 'U',
        'Ŭ' => 'U', 'Ű' => 'U', 'Ų' => 'U', 'Ư' => 'U', 'Ǔ' => 'U', 'Ǖ' => 'U', 'Ǘ' => 'U', 'Ǚ' => 'U', 'Ǜ' => 'U',
        'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ũ' => 'u', 'ŭ' => 'u', 'ű' => 'u', 'ų' => 'u', 'ư' => 'u', 'ǔ' => 'u',
        'ǖ' => 'u', 'ǘ' => 'u', 'ǚ' => 'u', 'ǜ' => 'u', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ý' => 'Y', 'Ÿ' => 'Y', 'Ŷ' => 'Y',
        'ý' => 'y', 'ÿ' => 'y', 'ŷ' => 'y',

        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'I', 'Θ' => 'Th', 'Ι' => 'I',
        'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => 'Ks', 'Ο' => 'O', 'Π' => 'P', 'Ρ' => 'R', 'Σ' => 'S',
        'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'Ph', 'Χ' => 'Ch', 'Ψ' => 'Ps', 'Ω' => 'O', 'Ϊ' => 'I', 'Ϋ' => 'Y', 'ά' => 'a',
        'έ' => 'e', 'ή' => 'i', 'ί' => 'i', 'ΰ' => 'Y', 'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e',
        'ζ' => 'z', 'η' => 'i', 'θ' => 'th', 'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => 'ks',
        'ο' => 'o', 'π' => 'p', 'ρ' => 'r', 'ς' => 's', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'ph', 'χ' => 'x',
        'ψ' => 'ps', 'ω' => 'o', 'ϊ' => 'i', 'ϋ' => 'y', 'ό' => 'o', 'ύ' => 'y', 'ώ' => 'o', 'ϐ' => 'b', 'ϑ' => 'th',
        'ϒ' => 'Y',

        // Turkish
        'Ç' => 'C', 'Ğ' => 'G', 'İ' => 'I', 'Ş' => 'S', 'ç' => 'c', 'ğ' => 'g', 'ı' => 'i', 'ş' => 's',

        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u', 'ž' => 'z',

        // Arabic
        'أ' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'g', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'th',
        'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't', 'ظ' => 'th', 'ع' => 'aa',
        'غ' => 'gh', 'ف' => 'f', 'ق' => 'k', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n', 'ه' => 'h', 'و' => 'o',
        'ي' => 'y',

        // Vietnamese
        'ạ' => 'a', 'ả' => 'a', 'ầ' => 'a', 'ấ' => 'a', 'ậ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ằ' => 'a', 'ắ' => 'a',
        'ặ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ẹ' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ề' => 'e', 'ế' => 'e', 'ệ' => 'e',
        'ể' => 'e', 'ễ' => 'e', 'ị' => 'i', 'ỉ' => 'i', 'ọ' => 'o', 'ỏ' => 'o', 'ồ' => 'o', 'ố' => 'o', 'ộ' => 'o',
        'ổ' => 'o', 'ỗ' => 'o', 'ờ' => 'o', 'ớ' => 'o', 'ợ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ụ' => 'u', 'ủ' => 'u',
        'ừ' => 'u', 'ứ' => 'u', 'ự' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ỳ' => 'y', 'ỵ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y',
        'Ạ' => 'A', 'Ả' => 'A', 'Ầ' => 'A', 'Ấ' => 'A', 'Ậ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ằ' => 'A', 'Ắ' => 'A',
        'Ặ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ẹ' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ề' => 'E', 'Ế' => 'E', 'Ệ' => 'E',
        'Ể' => 'E', 'Ễ' => 'E', 'Ị' => 'I', 'Ỉ' => 'I', 'Ọ' => 'O', 'Ỏ' => 'O', 'Ồ' => 'O', 'Ố' => 'O', 'Ộ' => 'O',
        'Ổ' => 'O', 'Ỗ' => 'O', 'Ờ' => 'O', 'Ớ' => 'O', 'Ợ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ụ' => 'U', 'Ủ' => 'U',
        'Ừ' => 'U', 'Ứ' => 'U', 'Ự' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ỳ' => 'Y', 'Ỵ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y',

        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z',

        // Latvian
        'Ā' => 'A', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'I', 'Ķ' => 'K', 'Ļ' => 'L', 'Ņ' => 'N', 'Ū' => 'U', 'ā' => 'a',
        'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n', 'ū' => 'u',

        // German
        'Ä' => 'AE', 'Ö' => 'OE', 'Ü' => 'UE', 'ß' => 'ss', 'ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue',

        // Ukrainian
        'Ґ' => 'G', 'І' => 'I', 'Ї' => 'Ji', 'Є' => 'Ye', 'ґ' => 'g', 'і' => 'i', 'ї' => 'ji', 'є' => 'ye',

        // Serbian
        'ђ' => 'dj', 'ј' => 'j', 'љ' => 'lj', 'њ' => 'nj', 'ћ' => 'c', 'џ' => 'dz', 'Ђ' => 'Dj', 'Ј' => 'j',
        'Љ' => 'Lj', 'Њ' => 'Nj', 'Ћ' => 'C', 'Џ' => 'Dz',

        // Russian
        'Ъ' => '', 'Ь' => '', 'А' => 'A', 'Б' => 'B', 'Ц' => 'C', 'Ч' => 'Ch', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E',
        'Э' => 'E', 'Ф' => 'F', 'Г' => 'G', 'Х' => 'H', 'И' => 'I', 'Й' => 'J', 'Я' => 'Ja', 'Ю' => 'Ju', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Ш' => 'Sh', 'Щ' => 'Shch',
        'Т' => 'T', 'У' => 'U', 'В' => 'V', 'Ы' => 'Y', 'З' => 'Z', 'Ж' => 'Zh', 'ъ' => '', 'ь' => '', 'а' => 'a',
        'б' => 'b', 'ц' => 'c', 'ч' => 'ch', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'э' => 'e', 'ф' => 'f', 'г' => 'g',
        'х' => 'h', 'и' => 'i', 'й' => 'j', 'я' => 'ja', 'ю' => 'ju', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'ш' => 'sh', 'щ' => 'shch', 'т' => 't', 'у' => 'u', 'в' => 'v',
        'ы' => 'y', 'з' => 'z', 'ж' => 'zh',

        // Other
        '¹' => '1', '²' => '2', '³' => '3', '¶' => 'P'
    );

    
        /*//detect CJK encodign depending string
        $cjk_encoding = array('Big5','EUC-JP','EUC-KR','GB18030','GB2312','ISO 2022-JP','KS C 5861','Shift-JIS');
        //d($cjk_encoding);
    
        $encoding_detected = mb_detect_encoding($title,implode(',',$cjk_encoding));

        if (in_array($encoding_detected,$cjk_encoding))
            d('found'.$encoding_detected);SS
        else
            d('not found');*/

    public static function slugify($text, $separator = '-', array $modifier = array())
    {
        $normalized = strtolower(strtr($text, $modifier + self::$chars_map));
        $cleaned = preg_replace($unwantedChars = '/([^a-z0-9]|-)+/', $separator, $normalized);

        return trim($cleaned, $separator);
    }

    /**
     * Slugify for CJK characters. removes emojis and unwanted chars from texts
     * @param  string $text 
     * @return string
     * @see http://stackoverflow.com/a/12824140/514629
     */
    public static function cjk_slugify($text, $separator = '-') 
    {
        $clean_text = '';

        // default operations with string no matter the encoding
        $clean_text = mb_strtolower(trim($text));
        $clean_text = str_replace(array("'",'/',' ','&','+','_','.','=','、','。','！','「','『','』','」','?'),$separator,$clean_text);


        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, $separator, $clean_text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, $separator, $clean_text);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, $separator, $clean_text);

        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, $separator, $clean_text);

        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, $separator, $clean_text);

        // remove duplicate -
        $clean_text = preg_replace('~-+~', $separator, $clean_text);

        // remove - at begining and end
        $clean_text = trim($clean_text,$separator);

        return $clean_text;
    }


} // End url
