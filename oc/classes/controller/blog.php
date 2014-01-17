<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Blog extends Controller {

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Blog'))->set_url(Route::url('blog')));

    }

    public function action_index()
    {
        //if they want to see a single post
        $seotitle = $this->request->param('seotitle',NULL);
        if ($seotitle!==NULL)
            return $this->action_view($seotitle);


        //template header
        $this->template->title            = __('Blog');
        $this->template->meta_description = __('Blog');
        
        $posts = new Model_Post();
        $posts->where('status','=', Model_Post::STATUS_ACTIVE)->where('id_forum','IS',NULL);

        $res_count = $posts->count_all();
        // check if there are some post
        if ($res_count > 0)
        {
   
            // pagination module
            $pagination = Pagination::factory(array(
                    'view'              => 'pagination',
                    'total_items'       => $res_count,
                    'items_per_page'    => core::config('general.products_per_page'),
            ))->route_params(array(
                    'controller'        => $this->request->controller(),
                    'action'            => $this->request->action(),
            ));
           
            Breadcrumbs::add(Breadcrumb::factory()->set_title(__("Page ").$pagination->current_page));

            //we sort all ads with few parameters
            $posts = $posts->order_by('created','desc')
                                ->limit($pagination->items_per_page)
                                ->offset($pagination->offset)
                                ->find_all();
        }
        else
        {
           $posts       = NULL;
           $pagination  = NULL; 
        }
            
        $this->template->bind('content', $content);
        
        $this->template->content = View::factory('pages/blog/listing',array('posts'=>$posts, 
                                                                    'pagination'=>$pagination,
                                                                    'user'=>Auth::instance()->get_user(),
                                                                    ));
        
    }


   /**
     *
     * Display single page
     * @throws HTTP_Exception_404
     */
    public function action_view($seotitle)
    {
        
            $post = new Model_Post();
            $post->where('status','=',Model_Post::STATUS_ACTIVE)
                ->where('seotitle','=',$seotitle)
                ->where('id_forum','IS',NULL)
                ->cached()->limit(1)->find();

            if ($post->loaded())
            {
                Breadcrumbs::add(Breadcrumb::factory()->set_title($post->title));

                $this->template->title            = $post->title;
                $this->template->meta_description = $post->description;

                $previous = new Model_Post();
                $previous = $previous->where('status','=',Model_Post::STATUS_ACTIVE)
                            ->where('id_forum','IS',NULL)
                            ->order_by('created','desc')
                            ->where('id_post', '<', $post->id_post)
                            ->limit(1)->find();  
                $next = new Model_Post();
                $next = $next->where('status','=',Model_Post::STATUS_ACTIVE)
                            ->where('id_forum','IS',NULL)
                            ->where('id_post', '>', $post->id_post)
                            ->limit(1)->find();

                $this->template->bind('content', $content);
                $this->template->content = View::factory('pages/blog/post',array('post'=>$post,'next'=>$next,'previous'=>$previous));
            }
            //not found in DB
            else
            {
                //throw 404
                throw new HTTP_Exception_404();
            }


    }

} // End Blog
