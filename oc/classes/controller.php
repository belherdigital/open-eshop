<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Controller extends Kohana_Controller
{
    public $template = 'main';

    /**
     * @var  boolean  auto render template
     */
    public $auto_render = TRUE;

    /**
     * global category get form controller so we can access form anywhere like Controller::$category;
     * @var Model_Category
     */
    public static $category = NULL;

    /**
     * global coupon get form controller so we can access form anywhere like Controller::$coupon;
     * @var Model_Category
     */
    public static $coupon = NULL;


    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before($template = NULL)
    {
        parent::before();

        Theme::checker();

        $this->maintenance();

        /**
         * selected category
         */
        if($this->request->param('category',NULL) != 'all' )
        {
            $slug_cat   = new Model_Category();
            $seo_cat = $slug_cat->where('seoname', '=', $this->request->param('category'))->limit(1)->cached()->find();
            if ($seo_cat->loaded())
                self::$category = $seo_cat;
        }
        
        /**
         * Deletes a coupon in use
         */
        if(core::request('coupon_delete') != NULL)
        {
            Session::instance()->set('coupon','');
            Alert::set(Alert::INFO, __('Coupon deleted.'));
        }
        //selected coupon
        elseif(core::request('coupon') != NULL OR Session::instance()->get('coupon')!='' )
        {
            $slug_coupon   = new Model_Coupon();
            $coupon = $slug_coupon->where('name', '=', core::request('coupon',Session::instance()->get('coupon')) )
                    ->where('number_coupons','>',0)
                    ->where('valid_date','>',DB::expr('NOW()'))
                    ->where('status','=',1)
                    ->limit(1)->find();
            if ($coupon->loaded())
            {
                self::$coupon = $coupon;
                if (Session::instance()->get('coupon')!=self::$coupon->name)
                {
                    Alert::set(Alert::SUCCESS, __('Coupon added!'));
                    Session::instance()->set('coupon',self::$coupon->name);
                }
            }
            else
                Alert::set(Alert::INFO, __('Coupon not valid, expired or already used.'));
                
        }



        if($this->auto_render===TRUE)
        {
        	// Load the template
            if ($template!==NULL)
                $this->template= $template; 
        	$this->template = View::factory($this->template);
        	
            // Initialize template values
            $this->template->title            = core::config('general.site_name');
            $this->template->meta_keywords    = '';
            $this->template->meta_description = '';
            $this->template->meta_copywrite   = 'Open eShop '.Core::version;
            $this->template->content          = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();

            //we can not cache this view since theres dynamic parts
            //$this->template->header  = View::factory('header');

            //setting inner views try to get from fragment
            if (Auth::instance()->logged_in())
                $this->template->header  = View::fragment('header_front_login','header');
            else
                $this->template->header  = View::factory('header');//             $this->template->header  = View::fragment('header_front','header');
                //no fragment since CSRF gets cached :(

            $this->template->footer = View::fragment('footer_front','footer');
            

        }
    }
    
    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
    	parent::after();
    	if ($this->auto_render === TRUE)
    	{
    		// Add defaults to template variables.
    		$this->template->styles  = array_merge_recursive(Theme::$styles, $this->template->styles);
    		$this->template->scripts = array_reverse(array_merge_recursive(Theme::$scripts,$this->template->scripts));
    		
            if ($this->template->title!='')
                $concat = ' - ';
            else
                $concat = '';

    		$this->template->title.= $concat.core::config('general.site_name');

    		 //auto generate keywords and description from content
    		$seo = new seo($this->template->meta_description, Kohana::$charset);
    		
    		if ($this->template->meta_keywords == '')//not meta keywords given
    		{
    	       $this->template->meta_keywords = $seo->getKeyWords(12);
    		}

    		$this->template->meta_description = $seo->getMetaDescription(150);//die($this->template->meta_description);
    		
    		
    	}
    	$this->response->body($this->template->render());
       
    }

    /**
     * in case you set up general.maintenance to TRUE
     * @return void 
     */
    public function maintenance()
    {
        //maintenance mode
        if (core::config('general.maintenance')==1 AND $this->request->controller()!='auth')
        {
            $user = Auth::instance()->get_user();

            if ($user!==FALSE)
            {           
                if ($user->id_role==Model_Role::ROLE_ADMIN)
                    Alert::set(Alert::INFO, __('You are in maintenance mode, only you can see the website'));
                else
                    $this->request->redirect(Route::url('maintenance'));
            }
            else
                $this->request->redirect(Route::url('maintenance'));
        }
    }    
        
        
    
}