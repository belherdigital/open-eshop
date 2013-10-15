<?php defined('SYSPATH') or die('No direct script access.');

class Pagination extends Kohana_Pagination 
{

	/**
	 * Title used in the links
	 */
	protected $title;
	
	/**
	 * Query parameters setter
	 * 
	 * @param	array	Query parameters to set
	 * @return	$this	Chainable as setter
	 */
	public function query_params($params = NULL)
	{
		if( ! empty($params))
		{
			Request::current()->query($params);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * sets/gets the title
	 * @param string $title
	 * @return string
	 */
	public function title($title = NULL)
	{
		if ($title !== NULL)
			$this->title = $title;
			
		return $this->title;
	}
	
}