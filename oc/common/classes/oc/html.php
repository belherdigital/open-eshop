<?php defined('SYSPATH') or die('No direct script access.');
/**
 * HTML helper class. Provides generic methods for generating various HTML
 * tags and making output HTML safe.
 *
 * @package    OC
 * @category   Helpers
 * @author     Oliver <oliver@open-classifieds.com>
 * @copyright  (c) 2009-2016 Open Classifieds Team
 * @license    GPL v3
 */

class OC_HTML extends Kohana_HTML {

    /**
     * Creates a <picture> tag with <source> elements and final <img> element.
     *
     *     echo HTML::picture('media/img/logo.png', array('w' => 179, 'h' => 179), array('1200px' => array('w' => '169', 'h' => '169'), '992px' => array('w' => '132', 'h' => '132'), '768px' => array('w' => '138', 'h' => '138'), '480px' => array('w' => '324', 'h' => '324'), '320px' => array('w' => '180', 'h' => '180')), array('alt' => 'My Company'))
     *
     * @param   string  $file       file name
     * @param   array   $size       resize image with Core::imagefly
     * @param   array   $sources    image sources with size and media braking point
     * @param   array   $attributes default attributes
     * @return  string
     * @uses    Core::imagefly
     * @uses    HTML::image
     * @uses    HTML::attributes
     */
    public static function picture($file, array $size = NULL, array $sources = NULL, array $attributes = NULL)
    {
        if (empty($sources))
            return '';

        $ret = '';

        foreach ($sources as $key => $value)
        {
            $media  = '(min-width: '.$key.')';
            $srcset = Core::imagefly($file, isset($value['w']) ? $value['w'] : NULL, isset($value['h']) ? $value['h'] : NULL);
            $ret   .= '<source'.HTML::attributes(compact('media', 'srcset')).'>';
        }

        $image = HTML::image(Core::imagefly($file, isset($size['w']) ? $size['w'] : NULL, isset($size['h']) ? $size['h'] : NULL), $attributes);

        return '<picture>'.$ret.$image.'</picture>';
    }

} // End OC_HTML
