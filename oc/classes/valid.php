<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Extended functionality for Kohana Valid
 *
 * @package    OC
 * @category   Security
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Valid extends Kohana_Valid{
    
    
	/**
	* Check an email address for correct format.
	*
	* @param   string   email address
	* @param   boolean  strict RFC compatibility and valid domain with MX
	* @return  boolean
	*/
	public static function email($email, $strict = FALSE)
	{
		//strict validation
		if ($strict===TRUE)
		{
			//check the RFC compatibility and MX
			return (parent::email($email,TRUE))? Valid::email_domain($email):FALSE;
		}
		//just normal validation
		else
		{
			return parent::email($email);
		}
	}
}
