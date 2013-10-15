<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Text helper class
 *
 * @package    OC
 * @category   Text
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Text extends Kohana_Text {


        /**
         * This array contains the main static bbcode
         * @var array $basic_bbcode
         */
        static $basic_bbcode = array(
                                '[b]', '[/b]',
                                '[i]', '[/i]',
                                '[u]', '[/u]',
                                '[s]','[/s]',
                                '[ul]','[/ul]',
                                '[li]', '[/li]',
                                '[ol]', '[/ol]',
                                '[table]','[/table]',
                                '[tr]','[/tr]',
                                '[td]','[/td]',
                                '[justify]','[/justify]',
                                '[center]', '[/center]',
                                '[left]', '[/left]',
                                '[right]', '[/right]',
        );

        /**
         * This array contains the main static bbcode's html
         * @var array $basic_html
         */
        static $basic_html = array(
                                '<b>', '</b>',
                                '<i>', '</i>',
                                '<u>', '</u>',
                                '<s>', '</s>',
                                '<ul>','</ul>',
                                '<li>','</li>',
                                '<ol>','</ol>',
                                '<table>','</table>',
                                '<tr>','</tr>',
                                '<td>','</td>',
                                '<div align="justify">','</div>',
                                '<div style="text-align: center;">', '</div>',
                                '<div style="text-align: left;">',   '</div>',
                                '<div style="text-align: right;">',  '</div>',
        );

        /**
         * This array contains the advanced static bbcode
         * @var array $advanced_bbcode
         */
        static $advanced_bbcode = array(
                                 '#\[color=([a-zA-Z]*|\#?[0-9a-fA-F]{6})](.+)\[/color\]#Usi',
                                 '#\[size=([0-9][0-9]?)](.+)\[/size\]#Usi',
                                 '#\[quote](\r\n)?(.+?)\[/quote]#si',
                                 '#\[quote=(.*?)](\r\n)?(.+?)\[/quote]#si',
                                 '#\[url](.+)\[/url]#Usi',
                                 '#\[url=(.+)](.+)\[/url\]#Usi',
                                 '#\[email]([\w\.\-]+@[a-zA-Z0-9\-]+\.?[a-zA-Z0-9\-]*\.\w{1,4})\[/email]#Usi',
                                 '#\[email=([\w\.\-]+@[a-zA-Z0-9\-]+\.?[a-zA-Z0-9\-]*\.\w{1,4})](.+)\[/email]#Usi',
                                 '#\[img](.+)\[/img]#Usi',
                                 '#\[img=(.+)](.+)\[/img]#Usi',
                                 '#\[code](\r\n)?(.+?)(\r\n)?\[/code]#si',
                                 '#\[youtube]http://www.youtube.com/watch\?v=(.+)\[/youtube]#Usi',
                                 '#\[youtube](.+)\[/youtube]#Usi',
                                 '#\[font=(.+)](.+)\[/font\]#Usi',
        );

        /**
         * This array contains the advanced static bbcode's html
         * @var array $advanced_html
         */
        static $advanced_html = array(
                                 '<span style="color: $1">$2</span>',
                                 // '<span style="font-size: $1px">$2</span>',
                                 '$2',
                                 "<blockquote>$2</blockquote>",
                                 "<blockquote>$3</blockquote>",
                                 '<a rel="nofollow" target="_blank" href="$1">$1</a>',
                                 '<a rel="nofollow" target="_blank" href="$1">$2</a>',
                                 '<a href="mailto: $1">$1</a>',
                                 '<a href="mailto: $1">$2</a>',
                                 '<img src="$1" alt="$1" />',
                                 '<img src="$1" alt="$2" />',
                                 '<code>$2</code>',
                                 '<iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
                                 '<iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
                                 '<font face="$1">$2</font>'
        );

	/**
	 *
	 * This function parses BBcode tag to HTML code (XHTML transitional 1.0)
	 *
	 * It parses (only if it is in valid format e.g. an email must to be
	 * as example@example.ext or similar) the text with BBcode and
	 * translates in the relative html code.
	 *
	 * @param string $text
	 * @param boolean $advanced his var describes if the parser run in advanced mode (only *simple* bbcode is parsed).
	 * @return string
	 */
	public static function bb2html($text,$advanced=FALSE)
    {

		//special chars
		$text  = htmlspecialchars($text, ENT_QUOTES, Kohana::$charset);

		/**
		 *
		 * Parses basic bbcode, used str_replace since seems to be the fastest
		 */
		$text = str_replace(self::$basic_bbcode, self::$basic_html, $text);

		//advanced BBCODE
		if ($advanced)
		{
			$text = preg_replace(self::$advanced_bbcode, self::$advanced_html, $text);
		}
       
		//before return convert line breaks to HTML
		return Text::nl2br($text);
	}

    /**
     * html 2 bbcode basic, usage for migration tool
     * @param  string $text 
     * @return string       
     */
    public static function html2bb($text)
    {
        //br to nl
        $text = preg_replace('#<br\s*/?>#i', "\n", $text);

        /**
         *
         * Parses basic bbcode, used str_replace since seems to be the fastest
         */
        $text = str_replace(self::$basic_html,self::$basic_bbcode, $text);

        

        //before return strip the rest of tags
        return strip_tags($text);
    }

	/**
	 *
	 * removes bbcode from text
	 * @param string $text
	 * @return string text cleaned
	 */
	public static function removebbcode($text)
	{
		return strip_tags(str_replace(array('[',']'), array('<','>'), $text));
	}

	/**
	 *
	 * Inserts HTML line breaks before all newlines in a string
	 * @param string $var
	 */
	public static function nl2br($var)
	{
		return str_replace(array('\\r\\n','\r\\n','r\\n','\r\n', '\n', '\r'), '<br />', nl2br($var));
	}

}