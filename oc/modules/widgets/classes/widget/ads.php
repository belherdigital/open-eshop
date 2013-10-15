<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Ads widget reader
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */


class Widget_Ads extends Widget
{

	public function __construct()
	{	

		$this->title 		= __('Ads');
		$this->description 	= __('Ads reader');

		$this->fields = array(	'ads_type' => array(    'type'      => 'text',
                                                        'display'   => 'select',
                                                        'label'     => __('Type ads to display'),
                                                        'options'   => array('latest'    => __('Latest Ads'),
                                                                             'popular'   => __('Popular Ads last month'),
                                                                             'featured'  => __('Featured Ads'),
                                                                            ), 
                                                        'default'   => 5,
                                                        'required'  => TRUE),

                                'ads_limit' => array( 	'type'		=> 'numeric',
														'display'	=> 'select',
														'label'		=> __('Number of ads to display'),
														'options'   => array_combine(range(1,50),range(1,50)), 
														'default'	=> 5,
														'required'	=> TRUE),

						 		'ads_title'  => array(	'type'		=> 'text',
						 		  						'display'	=> 'text',
						 		  						'label'		=> __('Ads title displayed'),
						 		  						'default'   => 'Latest Ads',
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
        $ads = new Model_Ad();
        $ads->where('status','=', Model_Ad::STATUS_PUBLISHED);

        switch ($this->ads_type) 
        {
            case 'popular':
                $id_ads = array_keys(Model_Visit::popular_ads());
                if (count($id_ads)>0)
                    $ads->where('id_ad','IN', $id_ads);
         
                break;
            case 'featured':
                $ads->where('featured','IS NOT', NULL)
                ->where('featured','>', DB::expr('NOW()'))
                ->order_by('featured','desc');
                break;
            case 'latest':
            default:
                $ads->order_by('published','desc');
                break;
        }

        $ads = $ads->limit($this->ads_limit)->cached()->find_all();
        //die(print_r($ads));
		$this->ads = $ads;
	}


}