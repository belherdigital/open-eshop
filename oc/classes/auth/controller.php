<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Front end controller for OC user/admin auth in the app
 *
 * @package    OC
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

class Auth_Controller extends Controller
{

	/**
	 *
	 * Contruct that checks you are loged in before nothing else happens!
	 */
	function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;


		//login control, don't do it for auth controller so we dont loop
		if ($this->request->controller()!='auth')
		{
			
			$url_bread = Route::url('oc-panel',array('controller'  => 'home'));
			Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Panel'))->set_url($url_bread));
				
			//check if user is login
			if (!Auth::instance()->logged_in( $request->controller(), $request->action(), $request->directory()))
			{
				Alert::set(Alert::ERROR, __('You do not have permissions to access '.$request->controller().' '.$request->action()));
				$url = Route::get('oc-panel')->uri(
                    array(
													 'controller' => 'auth', 
                     'action'     => 'login')
                    );
				$this->redirect($url);
			}

            //in case we are loading another theme since we use the allow query we force the configs of the selected theme
            if (Theme::$theme != Core::config('appearance.theme') AND Core::config('appearance.allow_query_theme')=='1') 
                Theme::initialize(Core::config('appearance.theme'));

		}

		//the user was loged in and with the right permissions
        parent::__construct($request,$response);
		
		
	}


	/**
	 * Initialize properties before running the controller methods (actions),
	 * so they are available to our action.
	 * @param  string $template view to use as template
	 * @return void           
	 */
	public function before($template = NULL)
	{
        Theme::checker();
        
        $this->maintenance();

        //Gets a coupon if selected
        Model_Coupon::current();
	
		if($this->auto_render===TRUE)
		{

            // Load the template
            $this->template = ($template===NULL)?'oc-panel/main':$template;
            //if its and ajx request I want only the content
            if(Core::get('rel')=='ajax')
                $this->template = 'oc-panel/content';
            $this->template = View::factory($this->template);
                
            // Initialize empty values
            $this->template->title            = __('Panel').' - '.core::config('general.site_name');
            $this->template->meta_keywords    = '';
            $this->template->meta_description = '';
            $this->template->meta_copyright   = 'Open eShop '.Core::VERSION;
            $this->template->header           = '';
            $this->template->content          = '';
            $this->template->footer           = '';
            $this->template->styles           = array();
            $this->template->scripts          = array();
            $this->template->user             = Auth::instance()->get_user();

            //non ajax request
            if (Core::get('rel')!='ajax')
            {
                $this->template->header           = View::factory('oc-panel/header');
                $this->template->footer           = View::factory('oc-panel/footer');



                // use CDN or local files
                $use_cdn = Core::use_cdn_for_css_js();
                //default theme
                if (Theme::get('admin_theme')=='bootstrap')
                {
                    Theme::$styles = array(
                        $use_cdn?'//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css':'css/bootstrap.3.2.0.min.css' => 'screen',
                        $use_cdn?'//cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css':'css/sceditor.1.4.3.themes.default.min.css' => 'screen',
                        $use_cdn?'//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js':'css/chosen.1.1.0.jquery.min.css' => 'screen',
                        $use_cdn?'//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css':'css/bootstrap-tagsinput.0.4.2.css'=>'screen',
                        'css/loadingbar.css'=>'screen', 
                        'css/icon-picker.min.css'=>'screen', 
                        'css/font-awesome.min.css'=>'screen', 
                        'css/summernote.css'=>'screen', 
                        'css/admin-styles.css?v='.Core::VERSION => 'screen',
                    );
                }
                else
                //other colors from Bootswatch http://www.bootstrapcdn.com/#bootswatch_tab
                {
                     Theme::$styles = array(
                         $use_cdn? '//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css':'css/bootstrap.3.2.0.min.css' => 'screen',
                         ($use_cdn?'//netdna.bootstrapcdn.com/bootswatch/3.2.0/':'css/bootswatch/3.2.0/').Theme::get('admin_theme','cerulean').'/bootstrap.min.css' => 'screen',
                         $use_cdn?'//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js':'css/chosen.1.1.0.jquery.min.css' => 'screen',
                         $use_cdn?'//cdn.jsdelivr.net/sceditor/1.4.3/themes/default.min.css':'css/sceditor.1.4.3.themes.default.min.css' => 'screen',
                         $use_cdn?'//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css':'css/bootstrap-tagsinput.0.4.2.css'=>'screen',
                        'css/loadingbar.css'=>'screen', 
                        'css/icon-picker.min.css'=>'screen', 
                        'css/font-awesome.min.css'=>'screen', 
                        'css/summernote.css'=>'screen', 
                        'css/admin-styles.css?v='.Core::VERSION => 'screen',
                     );
                }
            
                Theme::$scripts['footer'] = array(
                    $use_cdn?'//code.jquery.com/jquery-1.10.2.min.js':'@TODO',
                    $use_cdn?'//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js':'@TODO',
                    $use_cdn?'//cdn.jsdelivr.net/chosen/1.1.0/chosen.jquery.min.js':'css/chosen.1.1.0.jquery.min.js' => 'screen',
                    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'chosen')),
                    'http://'.((Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.'.Core::DOMAIN.'':'eshop.lo').'/embed.js',
                    'js/oc-panel/theme.init.js?v='.Core::VERSION,
                    'js/jquery.sceditor.min.js?v=144',
                    'js/summernote.min.js',
                    'js/jquery.validate.min.js',
                    Route::url('jslocalization', array('controller'=>'jslocalization', 'action'=>'validate')),
                    'js/jquery.cookie.min.js',
                    'js/iconPicker.min.js',
                    'js/oc-panel/sidebar.js?v='.Core::VERSION,
                    $use_cdn?'http://cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.min.js':'js/bootstrap-tagsinput.0.4.2.min.js',
                    'js/form.js?v='.Core::VERSION,
                );
            }
		}
		
		
	}


    /**
     * Fill in default values for our properties before rendering the output.
     */
    public function after()
    {
        //ajax request
        if (Core::get('rel')=='ajax')
        {
            // Add defaults to template variables.
            //$this->template->styles  = $this->template->styles;
            $this->template->scripts = array_reverse($this->template->scripts);
            $this->response->body($this->template->render());
        }
        else
            parent::after();
    }


}