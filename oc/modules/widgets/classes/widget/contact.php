<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Site stats widget
 *
 * @author      Slobodan <slobodan.josifovic@gmail.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Contact extends Widget
{

	public function __construct()
	{	

		$this->title = __('Contact');
		$this->description = __('Contact form');

		$this->fields = array(	
								'text_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
                                                        'default'   => __('Contact'),
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
        $ad = new Model_Ad();
        $ad->where('seotitle','=',Request::current()->param('seotitle'))
           ->limit(1)
           ->find();

        if($ad->loaded())
        {
            $this->id_ad = $ad->id_ad;
        }

    }

}