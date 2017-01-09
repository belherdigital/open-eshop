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


    /**
     * Generates the full URL for a certain page.
     *
     * @param   integer  page number
     * @return  string   page URL
     */
    public function url($page = 1)
    {
        $url = mb_strtolower(parent::url($page));

        //removing the parameter rel=ajax just in case
        $url = str_replace(array('&rel=ajax','rel=ajax&','rel=ajax'), '', $url);

        return $url;
    }
	
}