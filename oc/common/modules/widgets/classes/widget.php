<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Abstract class Widget to use in all the other widgets
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Widget
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 *
 * widget storage:
 * group: placeholder
 * name: placeholder name (unique)
 * value: json array in unique name order array('rss_09w0w309','text_ierijereijr')
 *
 * widget single storage
 * group: widget
 * name: rss_39394349349
 * value: json array: type of class, created, data('field_name'=>'value','rss_url'=>'http://rss.com')
 * 
 */

abstract class Widget{

	/**
	 * array fields the widget have, defined in the construct
	 * ex:
	 * array(	  'rss_items' => array( 'type'		=> 'numeric',
	 *													'display'	=> 'select',
	 *													'label'		=> __('# items to display'),
	 *													'options'   => range(1,10), 
	 *													'required'	=> TRUE),);
	 * @var array
	 */
	public $fields = array();


	/**
	 * data stored for each field
	 * @var array
	 */
	public $data = array();
	

	/**
	 * limit placeholders for this widget 
	 * (leave empty array for NO restrictions ) @TODO
	 * 
	 * @var array
	 */
	public $banned_placeholder = array();


	/**
	 * @var  widget title
	 */
	public $title = 'Widget Title';

	/**
	 * @var  description what the widget does
	 */
	public $description = 'Widget description';


	/**
	 * @var  unique name of the widget in the configs, used to retrieve it later
	 */
	public $widget_name = NULL;

	/**
	 * @var  placeholder where the widget is stored
	 */
	public $placeholder = NULL;

	/**
	 * @var  when was created/saved
	 */
	public $created = NULL;


	/**
	 * @var  easy way to know if the widget is loaded
	 */
	public $loaded = FALSE;


	public function __construct(){}

    /**
     * generates an instance of the correct widget
     * @param  string  $widget_name 
     * @param  boolean $load      if you wnat the widget to be loaded from DB
     * @return Widget               
     */
    public static function factory($widget_name, $load = TRUE)
    {
        //search for widget config
        $widget_data = core::config('widget.'.$widget_name);

        //found and with data!
        if($widget_data!==NULL AND !empty($widget_data) AND $widget_data !== '[]')
        { 
            $widget_data = json_decode($widget_data, TRUE);
            
            if (!class_exists($widget_data['class']))
                return NULL;
            
            //creating an instance of that widget
            $widget = new $widget_data['class'];

            //populate the data we got
            if ($load)
                $widget->load($widget_name, $widget_data);

            return $widget;
        }

        return NULL;
    }

	/**
	 * gets the fields value form the DB config
	 * @param  string $widget_name 
	 * @param array $widget_data optional
	 * @return boolean              
	 */
	public function load($widget_name,array $widget_data = NULL)
	{
		
		//search for widget config
		if ($widget_data==NULL OR !is_array($widget_data))
		{
			$widget_data = core::config('widget.'.$widget_name);
			//found and with data!
			if($widget_data!==NULL AND !empty($widget_data) AND $widget_data !== '[]')
			{ 
				$widget_data = json_decode($widget_data,TRUE);
			}
			else return FALSE;
		}

		//populate the data we got
		$this->placeholder  = $widget_data['placeholder'];
		$this->created 		= $widget_data['created'];
		$this->data 		= $widget_data['data'];
		$this->widget_name 	= $widget_name;
		$this->loaded 		= TRUE;

		return TRUE;

	}

	/**
	 * saves current widget data into the DB config
	 * @param string $old_placeholder
	 * @return boolean 
	 */
	public function save($old_placeholder = NULL)
	{
		//stores $data array as json in the config. We need the placeholder?
		$save = array('class'		=> get_class($this),
					  'created'		=> time(),
					  'placeholder'	=> $this->placeholder,
					  'data'		=> $this->data
					);

		//since was not loaded we assume is new o generate a new name that doesn't exists
		if(!$this->loaded)
			$this->widget_name = $this->gen_name();

		// save widget to DB
   		$confw = new Model_Config();
   		$confw->where('group_name','=','widget')
		   			->where('config_key','=',$this->widget_name)
		   			->limit(1)->find();
   		if (!$confw->loaded())
   		{
   			$confw->group_name = 'widget';
   			$confw->config_key = $this->widget_name;
   		}
   		
   		$confw->config_value = json_encode($save);

   		try {
   			$confw->save();

   			//remove from previous placeholder, only if they are different
        	if ($this->placeholder !== $old_placeholder AND $old_placeholder!==NULL)
        	{
        		$confp = new Model_Config();

		   		$confp->where('group_name','=','placeholder')
		   			->where('config_key','=',$old_placeholder)
		   			->limit(1)->find();
		   			
		   		if ($confp->loaded())
		   		{
		   			$confp->group_name = 'placeholder';
		   			$confp->config_key = $old_placeholder;
		   			//remove the key
		   			$wid = json_decode($confp->config_value);
		   			$key = array_search($this->widget_name, $wid);

		   			if ($key!==FALSE)
		   				unset($wid[$key]);

		   			$confp->config_value = json_encode($wid);

		   			$confp->save();
		   		}

        	}

   			//adding the widget to the placeholder
   			//get widgets in the placeholder array
   			$w_placeholders = widgets::get($this->placeholder, TRUE);
   			//if name exists in placeholder don't change anything
   			if (!in_array($this->widget_name, $w_placeholders))
   			{
   				//if doesnt exists add it to the end and save
   				$w_placeholders[] = $this->widget_name;

   				// save palceholder to DB
		   		$confp = new Model_Config();
		   		$confp->where('group_name','=','placeholder')
		   			->where('config_key','=',$this->placeholder)
		   			->limit(1)->find();
		   		if (!$confp->loaded())
		   		{
		   			$confp->group_name = 'placeholder';
		   			$confp->config_key = $this->placeholder;
		   		}
		   		
		   		$confp->config_value = json_encode($w_placeholders);
		   		$confp->save();

   			}

        	
        	$this->loaded = TRUE;
   			return TRUE;
   		} 
   		catch (Exception $e) {
  			throw HTTP_Exception::factory(500,$e->getMessage());		
   		}

   		return FALSE;
		
	}


    /**
     * delete current widget data from the DB config
     * @return boolean 
     */
    public function delete()
    {

        if($this->loaded)
        {
            // save widget to DB
            $confw = new Model_Config();
            $confw->where('group_name','=','widget')
                        ->where('config_key','=',$this->widget_name)
                        ->limit(1)->find();
            if ($confw->loaded())
            {
                try {
                    $confw->delete();
                    //remove from previous placeholder, only if they are different
                    $confp = new Model_Config();
                    $confp->where('group_name','=','placeholder')
                        ->where('config_key','=',$this->placeholder)
                        ->limit(1)->find();
                        
                    if ($confp->loaded())
                    {
                        //remove the key
                        $wid = json_decode($confp->config_value);
                        if (is_array($wid))
                        {
                            $wid = array_diff($wid, array($this->widget_name));
                            $confp->config_value = json_encode(array_values($wid));
                            $confp->save();
                        }
                        
                    }
                    
                    $this->data = array();
                    $this->loaded = FALSE;
                    return TRUE;
                } 
                catch (Exception $e) {
                    throw HTTP_Exception::factory(500,$e->getMessage());     
                }
            }
        }
        return FALSE;        
    }

	/**
	 * unload the widget
	 * @return void
	 */
	public function unload()
	{
		$this->data 		= array();
		$this->loaded 		= FALSE;
		$this->widget_name 	= NULL;
	}


	/**
	 * renders the widget view with the data
	 * @return string HTML 
	 */		
	public function render()
	{
		if ($this->loaded)
		{
			$this->before();

			//get the view file (check if exists in the theme if not default), and inject the widget
			$out = View::factory('widget/'.strtolower(get_class($this)),array('widget' => $this));

			$this->after();

			return $out;
		}
		
		return FALSE;
	}

	/**
	 * renders the form view to fill the data and then saves it
	 * @return string html
	 */
	public function form()
	{		

		//for each field reder html_tag
		$tags = array();
		foreach ($this->fields as $name => $options) 
		{
			$value = (isset($this->data[$name]))?$this->data[$name]:NULL;
			$tags[] = FORM::form_tag($name, $options, $value);
		}

		//render view
		return View::factory('oc-panel/pages/widgets/form_widget', array( 'widget' => $this, 
																		  'tags'   => $tags
																		 )
							);
	}

	/**
	 * generates a name for this widget
	 * @return string
	 */
	public function gen_name()
	{
		return get_class($this).'_'.time();
	}

	/**
	 * returns the name of the widget class
	 * @return string 
	 */
	public function id_name()
	{
		return ($this->widget_name)?$this->widget_name:get_class($this);
	}

    /**
     * get the title for the widget
     * @param string $title we will use it for the loaded widgets
     * @return string 
     */
    public function title($title = NULL)
    {
        return ($title!==NULL)?$title:NULL;
    }

	/**
	 * Automatically executed before the widget action. Can be used to set
	 * class properties, do authorization checks, and execute other custom code.
	 *
	 * @return  void
	 */
	public function before()
	{
		// Nothing by default
	}

	/**
	 * Automatically executed after the widget action. Can be used to apply
	 * transformation to the request response, add extra output, and execute
	 * other custom code.
	 *
	 * @return  void
	 */
	public function after()
	{
		// Nothing by default
	}

	/**
	 * Magic methods to set get
	 */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;        
    }

	public function __get($name)
    {
        return (array_key_exists($name, $this->data)) ? $this->data[$name] : NULL;
    }
}