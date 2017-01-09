<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Text helper class
 *
 * @package    OC
 * @category   Text
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class OC_Text extends Kohana_Text {


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
                                '<ul style="list-style: disc; padding:10px 10px 0px 40px;">','</ul>',
                                '<li style="list-style: inherit;">','</li>',
                                '<ol style="list-style: decimal; padding:10px 10px 0px 40px;">','</ol>',
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
                                 '#\[color=(.+)](.+)\[/color\]#Usi',
                                 '#\[size=(.+)](.+)\[/size\]#Usi',
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
                                 '$2',// '<span style="font-size: $1px">$2</span>',
                                 "<blockquote>$2</blockquote>",
                                 "<blockquote>$3</blockquote>",
                                 '<a rel="nofollow" target="_blank" href="$1">$1</a>',
                                 '<a rel="nofollow" target="_blank" href="$1">$2</a>',
                                 '<a href="mailto: $1">$1</a>',
                                 '<a href="mailto: $1">$2</a>',
                                 '<img src="$1" alt="$1" />',
                                 '<img src="$2" alt="$1" />',
                                 '<code>$2</code>',
                                 '<iframe width="100%" height="315" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
                                 '<iframe width="100%" height="315" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>',
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
     * @param boolean $advanced his var describes if the parser run in advanced mode (only *simple* bbcode is parsed)..
     * @param boolean $specialchars if true transform specal chars
     * @return string
     */
    public static function bb2html($text,$advanced = FALSE, $specialchars = TRUE, $nl2br = TRUE)
    {
        //special chars
        if ($specialchars === TRUE)
            $text  = htmlspecialchars($text, ENT_QUOTES, Kohana::$charset);

        /**
         *
         * Parses basic bbcode, used str_replace since seems to be the fastest
         */
        $text = str_replace(self::$basic_bbcode, self::$basic_html, $text);

        //advanced BBCODE
        if ($advanced === TRUE)
            $text = preg_replace(self::$advanced_bbcode, self::$advanced_html, $text);
       
        //before return convert line breaks to HTML
        if ($nl2br === TRUE)
            $text = Text::nl2br($text);

        return $text;
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
        return str_replace(array("\\r\\n","\r\\n","r\\n","\r\n", "\n", "\r"), '<br />', $var);
    }

    /**
     *
     * removes line breaks from text
     * @param string $var
     */
    public static function removenl($var)
    {
        return str_replace(array("\\r\\n","\r\\n","r\\n","\r\n", "\n", "\r"), '', $var);
    }

    /**
     * mb_ucfirst doesnt exists...so lets create it ;)
     */
    public static function ucfirst($str, $enc = NULL) 
    {
        return mb_strtoupper(mb_substr($str, 0, 1)).mb_substr($str, 1, mb_strlen($str)); 
    }

    /**
     * Truncate a string up to a number of characters while preserving whole words and HTML tags
     * CakePHP method https://github.com/cakephp/cakephp/blob/5eafb81819454358e55b1e6d296e5e81b8d2d5e3/src/Utility/Text.php
     * 
     * @param string  $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param string  $ending Ending to be appended to the trimmed string.
     * @param boolean $exact If false, $text will not be cut mid-word.
     * @param boolean $considerHtml If true, HTML tags would be handled correctly.
     * @return string Trimmed string.
     */
    public static function truncate_html($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
    {
        if ($considerHtml)
        {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
            {
                return $text;
            }

            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';

            foreach ($lines as $line_matchings)
            {

                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if ( ! empty($line_matchings[1]))
                {

                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1]))
                    {
                        // do nothing
                        // if tag is a closing tag
                    }
                    elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings))
                    {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                        unset($open_tags[$pos]);
                        }
                    // if tag is an opening tag
                    }
                    elseif (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings))
                    {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }

                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }

                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));

                if ($total_length+$content_length> $length)
                {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE))
                    {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity)
                        {
                            if ($entity[1]+1-$entities_length <= $left)
                            {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            }
                            else
                            {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                }
                else
                {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }

                // if the maximum length is reached, get off the loop
                if ($total_length>= $length)
                {
                    break;
                }
            }
        }
        else
        {
            if (strlen($text) <= $length)
            {
                return $text;
            }
            else
            {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if ( ! $exact)
        {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');

            if (isset($spacepos))
            {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;

        if ($considerHtml)
        {
            // close all unclosed html-tags
            foreach ($open_tags as $tag)
            {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }


}