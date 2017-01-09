<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Kohana Image
 *
 * @package    OC
 * @category   Image
 */

class Upload extends Kohana_Upload {


	/**
	* Image nudity detector based on flesh color quantity.
	*
	* @param array $file uploaded file data
	* @param string $threshold Threshold of flesh color in image to consider in pornographic. See page 302
	* @return boolean
	*/

	public static function not_nude_image(array $file, $threshold = .5) {
	    
	    if (Upload::not_empty($file))
	    {
		    $image = Image::factory($file['tmp_name']);

		    if ($image->is_nude_image($threshold))
		    	return FALSE;
		}

		return TRUE;
	}
}
