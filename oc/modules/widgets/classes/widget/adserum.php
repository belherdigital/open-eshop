<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Adserum banners, earn money!
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Adserum extends Widget
{

    public $formats =  array(
                            '1' => array('name'  => '300x250',
                                         'height'=>250,
                                         'width' =>300),
                            '2' => array('name'  => '160x600',
                                         'height'=>600,
                                         'width' =>160),
                            '3' => array('name'  => '200x200',
                                         'height'=>200,
                                         'width' =>200),
                            '4' => array('name'  => '120x600',
                                         'height'=>600,
                                         'width' =>120),
                            '5' => array('name'  => '250x250',
                                         'height'=>250,
                                         'width' =>250),
                            '6' => array('name'  => '336x280',
                                         'height'=>280,
                                         'width' =>336),
                            '7' => array('name'  => '468x60',
                                         'height'=>60,
                                         'width' =>468),
                            '8' => array('name'  => '728x90',
                                         'height'=>90,
                                         'width' =>728),

                            );

	public function __construct()
	{	



		$this->title 		= 'Adserum.com';
		$this->description 	= '<a target="_blankd" href="http://adserum.com?utm_source='.URL::base().'&utm_medium=banner_oc&utm_campaign='.date('Y-m-d').'">'.
                            __('Earn money from your site').'</a>';

		$this->fields = array(	'format' => array(    'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Banner Format'),
                                                        'options'   => $this->get_options(),
                                                        'default'   => '200x200',
                                                        'required'  => TRUE),

						 		'id_publisher'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Adserum publisher ID'),
						 		  						'default'   => '6',
														'required'	=> FALSE),

                                'ads_title'  => array(  'type'      => 'text',
                                                        'display'   => 'text',
                                                        'label'     => __('Title displayed'),
                                                        'default'   => '',
                                                        'required'  => FALSE),
						 		);
	}

	/**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return parent::title($this->ads_title);
    }

	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{

        $this->width  = $this->formats[$this->format]['width'];
        $this->height = $this->formats[$this->format]['height'];
	}

    private function get_options()
    {
        $formats = array();
        foreach ($this->formats as $key => $value) 
        {
            $formats[$key] = $value['name'];
        }
        return $formats;
    }


}