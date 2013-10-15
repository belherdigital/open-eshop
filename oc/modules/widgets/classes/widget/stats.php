<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site stats widget
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Stats extends Widget
{

	public function __construct()
	{	

		$this->title = __('Stats');
		$this->description = __('Display site stats');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
                                                        'default'   => __('Stats'),
						 		  						'label'		=> __('Title displayed'),
														'required'	=> FALSE),
						 		);
	}



    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->text_title);
    }


    /**
     * Automatically executed before the widget action. Can be used to set
     * class properties, do authorization checks, and execute other custom code.
     *
     * @return  void
     */
    public function before()
    {
        //try to get the Info from the cache
        $info = Core::cache(Route::url('sitejson'));

        //not cached :(
        if ($info === NULL)
        {
            $info = json_decode(Core::curl_get_contents(Route::url('sitejson')));
            Core::cache(Route::url('sitejson'),$info);
        }

        $this->info = $info;
    }

}