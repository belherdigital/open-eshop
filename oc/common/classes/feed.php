<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Date helper class
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2015 Open Classifieds Team
 * @license    GPL v3
*/
class Feed extends Kohana_Feed {

    /**
     * Parses a remote feed into an array.
     *
     * @param   string  $feed   remote feed URL
     * @param   integer $limit  item limit to fetch
     * @param   integer $cache_expire_time in seconds when cache expires
     * @return  array
     */
    public static function parse($feed, $limit = 0, $cache_expire_time = NULL)
    {
        //in case theres no expire time set to 24h
        if ($cache_expire_time === NULL)
            $cache_expire_time = 24*60*60;
        
        // Check if SimpleXML is installed
        if ( ! function_exists('simplexml_load_file'))
            throw new Kohana_Exception('SimpleXML must be installed!');

        // Make limit an integer
        $limit = (int) $limit;

        // Disable error reporting while opening the feed
        $error_level = error_reporting(0);

        // Allow loading by filename or raw XML string
        if (Valid::url($feed))
        {
            //mod! force usage of curl with timeout and cached!
            $feed_result = Core::cache($feed,NULL,$cache_expire_time);
            
            //not cached :(
            if ($feed_result === NULL)
            {
                $feed_result = Core::curl_get_contents($feed,5);
                Core::cache($feed,$feed_result,$cache_expire_time);
            }

            $feed = $feed_result;
        }
        elseif (is_file($feed))
        {
            // Get file contents
            $feed = file_get_contents($feed);
        }

        // Load the feed
        $feed = simplexml_load_string($feed, 'SimpleXMLElement', LIBXML_NOCDATA);

        // Restore error reporting
        error_reporting($error_level);

        // Feed could not be loaded
        if ($feed === FALSE)
            return array();

        $namespaces = $feed->getNamespaces(TRUE);

        // Detect the feed type. RSS 1.0/2.0 and Atom 1.0 are supported.
        $feed = isset($feed->channel) ? $feed->xpath('//item') : $feed->entry;

        $i = 0;
        $items = array();

        foreach ($feed as $item)
        {
            if ($limit > 0 AND $i++ === $limit)
                break;
            $item_fields = (array) $item;

            // get namespaced tags
            foreach ($namespaces as $ns)
            {
                $item_fields += (array) $item->children($ns);
            }
            $items[] = $item_fields;
        }

        return $items;
    }



}
