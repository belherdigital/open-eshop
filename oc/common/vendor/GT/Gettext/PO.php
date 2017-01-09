<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * File::Gettext
 *
 * PHP versions 4 and 5
 *
 * @category  FileFormats
 * @package   File_Gettext
 * @author    Michael Wallner <mike@php.net>
 * @copyright 2004-2005 Michael Wallner
 * @license   BSD, revised
 * @version   CVS: $Id$
 * @link      http://pear.php.net/package/File_Gettext
 */

/**
 * Requires File_Gettext
 */


/**
 * File_Gettext_PO
 *
 * GNU PO file reader and writer.
 *
 * @category  FileFormats
 * @package   File_Gettext
 * @author    Michael Wallner <mike@php.net>
 * @copyright 2004-2005 Michael Wallner
 * @license   BSD, revised
 * @link      http://pear.php.net/package/File_Gettext
 */
class File_Gettext_PO extends File_Gettext
{
    /**
     * Constructor
     *
     * @param string $file path to GNU PO file
     *
     * @access  public
     * @return  object      File_Gettext_PO
     */
    function __construct($file = '')
    {
        $this->file = $file;
    }

    /**
     * Load PO file
     *
     * @param string $file File path to load
     *
     * @access  public
     * @return  mixed   Returns true on success or PEAR_Error on failure.
     */
    function load($file = null)
    {
        $this->strings = array();

        if (!isset($file)) {
            $file = $this->file;
        }

        // load file
        if (!$contents = @file($file)) {
           //    return parent::raiseError($php_errormsg . ' ' . $file);
        }
        $contents = implode('', $contents);

        // match all msgid/msgstr entries
        $matched = preg_match_all('/msgid\s+((?:".*(?<!\\\\)"\s*)+)\s+' .
                                  'msgstr\s+((?:".*(?<!\\\\)"\s*)+)/',
                                  $contents, $matches);
        unset($contents);

        if (!$matched) {
            return parent::raiseError('No msgid/msgstr entries found');
        }

        // get all msgids and msgtrs
        for ($i = 0; $i < $matched; $i++) {
            $msgid = substr(rtrim($matches[1][$i]), 1, -1);
            $msgstr = substr(rtrim($matches[2][$i]), 1, -1);

            $this->strings[parent::prepare($msgid)] = parent::prepare($msgstr);
        }

        // check for meta info
        if (isset($this->strings[''])) {
            $this->meta = parent::meta2array($this->strings['']);
            unset($this->strings['']);
        }

        return true;
    }

    /**
     * Save PO file
     *
     * @param string $file File path to write to
     *
     * @access  public
     * @return  mixed   Returns true on success or PEAR_Error on failure.
     */
    function save($file = null)
    {
        if (!isset($file)) {
            $file = $this->file;
        }

        // open PO file
        if (!is_resource($fh = @fopen($file, 'w'))) {
            return parent::raiseError($php_errormsg . ' ' . $file);
        }
        // lock PO file exclusively
        if (!@flock($fh, LOCK_EX)) {
            @fclose($fh);
            return parent::raiseError($php_errmsg . ' ' . $file);
        }

        // write meta info
        if (count($this->meta)) {
            $meta = 'msgid ""' . "\nmsgstr " . '""' . "\n";
            foreach ($this->meta as $k => $v) {
                $meta .= '"' . $k . ': ' . $v . '\n"' . "\n";
            }
            fwrite($fh, $meta . "\n");
        }
        // write strings
        foreach ($this->strings as $o => $t) {
            fwrite($fh,
                'msgid "'  . parent::prepare($o, true) . '"' . "\n" .
                'msgstr "' . parent::prepare($t, true) . '"' . "\n\n");
        }

        //done
        @flock($fh, LOCK_UN);
        @fclose($fh);
        return true;
    }
}
?>
