<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alert helper class.
 *
 *
 * @package    OC
 * @category   Helpers
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
class Alert {

	const SUCCESS	= 'success';
	const ERROR		= 'danger';
	const WARNING	= 'warning';
	const ALERT		= 'warning';
	const INFO		= 'info';

	/**
	 * @var  string  Name of the var used to save the data on session
	 */
	protected static $session_var = 'alert_data';

	/**
	 * @var  string  Template for a error message block
	 */
	public static $tpl = '<div class="alert alert-%s"><h3>%s</h3><p>%s</p></div>';

	/**
	 * Sets a new alert message
	 *
	 * @param  string  $type  of the
	 * @param  string  $text
	 * @param  string  $title
	 * @param  string  $name
	 * @return  bool
	 */
	public static function set($type, $text, $title = NULL, $name = NULL)
	{
		$session = Session::instance();
		$data = $session->get(self::$session_var, array());

		$mydata = array(
            'type' => $type,
            'title'=> $title, 
            'text' => $text
		);

		if ($name !== NULL)
		{
			$data[$name] = $mydata;
		}
		else
		{
            //avoid duplicated alerts
            $duplicated = FALSE;
            foreach ($data as $key => $value) 
            {
                if ($value==$mydata)
                    $duplicated = TRUE;
            }

            if (!$duplicated)
			     $data[] = $mydata;
		}


		$session->set(self::$session_var, $data);

		return TRUE;
	}

	/**
	 * Show HTML Block(s) of error(s).
	 *
	 * @param  string  $name    If received, only shows the alert with this name
	 * @param  bool    $persist  If TRUE, the alert will persist on session
	 * @return  string
	 */
	public static function show($name = NULL, $persist = FALSE)
	{
		$session = Session::instance();
		$data = $session->get(self::$session_var, array());

		$out = '';
		if (($name !== NULL) AND isset($data[$name]))
		{
			$v = $data[$name];
			$out .= self::msg($v['type'], $v['text'], (isset($v['title'])) ? $v['title'] : NULL);
			if (! $persist)
			{
				unset($data[$name]);
			}
		}
		else
		{
			foreach ($data as $k=>$v)
			{
				$out .= self::msg($v['type'], $v['text'], (isset($v['title'])) ? $v['title'] : NULL);
				if ( ! $persist)
				{
					unset($data[$k]);
				}
			}

		}

		// Update the alert data in session
		$session->set(self::$session_var, $data);

		return $out;
	}

	/**
	 * Delete a message that was previously set
	 *
	 * @param  string  $name
	 * @return  bool
	 */
	public static function del($name)
	{
		$session = Session::instance();
		$data = $session->get(self::$session_var, array());

		if (isset($data[$name]))
		{
			unset($data[$name]);
			$session->set(self::$session_var, $data);
		}

		return TRUE;
	}

	/**
	 * Returns the HTML of one alert message
	 *
	 * @param  string  $type  Type of alert message
	 * @param  string  $text  Text of the alert
	 * @param  string  $title  Title to put on the alert box
	 * @return  string  The HTML code of the alert block
	 */
	public static function msg($type, $text, $title = NULL )
	{
		if ($title === NULL)
		{
			switch ($type)
			{
				case self::INFO:
					$title = __('Info');
					break;
				case self::WARNING:
					$title = __('Warning');
					break;
				case self::ALERT:
					$title = __('Warning');
					break;
				case self::ERROR:
					$title = __('Error');
					break;
				case self::SUCCESS:
					$title = __('Success');
					break;
				default:
					$title = NULL;
			}
		}

		return sprintf(self::$tpl, $type, (( ! empty($title)) ? $title: ''), $text).PHP_EOL;
	}

} // End Alert