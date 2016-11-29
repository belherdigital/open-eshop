<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Controller extends Kohana_Controller
{
    public $template = 'main';

     /**
     * user if its loged in
     * @var Model_User
     */
    public $user = NULL;

    /**
     * @var  boolean  auto render template
     */
    public $auto_render = TRUE;

    /**
     * global image get from controller so we can access from anywhere like Controller::$image; used for facebook metas
     */
    public static $image = NULL;

    /**
     * global category get form controller so we can access form anywhere like Controller::$category;
     * @var Model_Category DEPRECATED
     */
    public static $category = NULL;

    /**
     * global coupon get form controller so we can access form anywhere like Controller::$coupon;
     * @var Model_Coupon DEPRECATED
     */
    public static $coupon = NULL;

    /**
     *
     * Contruct that checks you are loged in before nothing else happens!
     */
    function __construct(Request $request, Response $response)
    {
        //setting the user
        $this->user = Auth::instance()->get_user();

        parent::__construct($request,$response);

        //check 2 step
        if ( strtolower($this->request->controller())!='auth' AND
            Auth::instance()->logged_in() AND
            core::config('general.google_authenticator')==TRUE AND 
            Auth::instance()->get_user()->google_authenticator!='' AND 
            Cookie::get('google_authenticator')!=Auth::instance()->get_user()->id_user )
        {
            //redirect to 2step page
            $url = Route::url('oc-panel',array('controller'=>'auth','action'=>'2step')).'?auth_redirect='.URL::current();
            $this->redirect($url);
        }
    }
    
    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before($template = NULL)
    {
        parent::before();

        Theme::checker();

        $this->maintenance();
        $this->private_site();
        
        //get category, deprecated, to keep backwards compatibility with themes
        self::$category = Model_Category::current();
                
        //Gets a coupon if selected
        self::$coupon = Model_Coupon::current();

        //get the affiliate if any
        Model_Affiliate::current();

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
            $this->template->meta_copyright   = 'Open eShop '.Core::VERSION;
            $this->template->content          = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();

            //we can not cache this view since theres dynamic parts
            //$this->template->header  = View::factory('header');

            //setting inner views try to get from fragment
            // if (Auth::instance()->logged_in())
            //     $this->template->header  = View::fragment('header_front_login','header');
            // else
                $this->template->header  = View::factory('header');
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
            // Add custom CSS if enabld and front controller
            if (is_subclass_of($this,'Auth_Controller')===FALSE AND ($custom_css = Theme::get_custom_css())!==FALSE )
                Theme::$styles = array_merge(Theme::$styles,array($custom_css => 'screen',));

            //cookie consent
            if (Core::config('general.cookie_consent')==1)
            {
                Theme::$styles = array_merge(Theme::$styles,array('css/jquery.cookiebar.css' => 'screen',));

                $this->template->scripts['footer'][] = 'js/jquery.cookiebar.js';
                $this->template->scripts['footer'][] = Route::url('default',array('controller'=>'jslocalization','action'=>'cookieconsent'));
            }
            
            // Add defaults to template variables.
            $this->template->styles  = array_merge_recursive(Theme::$styles, $this->template->styles);
            $this->template->scripts = array_reverse(array_merge_recursive(Theme::$scripts,$this->template->scripts));
            
            //in case theres no description given
            if ($this->template->meta_description == '')
                $this->template->meta_description = $this->template->title;

            //title concatenate the site name
            if ($this->template->title != '')
                $this->template->title .= ' - ';

            $this->template->title .= core::config('general.site_name');

            //auto generate keywords and description from content
            seo::$charset = Kohana::$charset;

            $this->template->title = seo::text($this->template->title, 70);
            
            //not meta keywords given
            //remember keywords are useless :( http://googlewebmastercentral.blogspot.com/2009/09/google-does-not-use-keywords-meta-tag.html
            if ($this->template->meta_keywords == '')
                $this->template->meta_keywords = seo::keywords($this->template->meta_description);
            
            $this->template->meta_description = seo::text($this->template->meta_description);
            
        }
        
        //no cache for logged users / actions, so we can use varnish or whatever ;)
        if ($this->user != NULL)
            $this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');

        $this->response->body($this->template->render());       
       
    }

    /**
     * in case you set up general.maintenance to TRUE
     * @return void 
     */
    public function maintenance()
    {
        //maintenance mode
        if (core::config('general.maintenance')==1 AND strtolower($this->request->controller())!='auth' AND strtolower($this->request->controller())!='api')
        {
            $user = Auth::instance()->get_user();

            if (isset($user->id_role) AND $user->id_role==Model_Role::ROLE_ADMIN)
            {
                Alert::set(Alert::INFO, __('You are in maintenance mode, only you can see the website'), NULL, 'maintenance');
            }
            else
                $this->redirect(Route::url('maintenance'));
        }
    }    
         
    /**
     * in case you set up general.private_site to TRUE
     * @return void 
     */
    public function private_site()
    {
        //private_site
        if (core::config('general.private_site')==1 AND $this->user==FALSE AND (strtolower($this->request->action())!='login') )
        {
            $this->auto_render = FALSE;
            $this->response->status(403);
            $this->template = View::factory('pages/error/403');
            $this->after();
            // Return the response
            die($this->response);
        }
    }  
        
    
}