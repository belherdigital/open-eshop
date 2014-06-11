<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Forum extends Controller {

    public static $items_per_page = 20;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Home'))->set_url(Route::url('default')));
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Forums'))->set_url(Route::url('forum-home')));

    }

    /**
     * displays the forums
     * @return [type] [description]
     */
    public function action_index()
    {
        //template header
        $this->template->title            = __('Forum');
        $this->template->meta_description = __('Forum');
        $this->template->styles              = array('css/forums.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/forums.js';
        $forums = Model_Forum::get_forum_count();
            
        $this->template->bind('content', $content);
        $this->template->content = View::factory('pages/forum/home',array('forums'=>$forums));
    }

    /**
     * displays the topics on a forums
     * @return [type] [description]
     */
    public function action_list()
    {
        $this->template->styles              = array('css/forums.css' => 'screen');
        $this->template->scripts['footer'][] = 'js/forums.js';
        $forum = new Model_Forum();
        $forum->where('seoname','=',$this->request->param('forum',NULL))
            ->cached()->limit(1)->find();

        if ($forum->loaded())
        {
            //template header
            $this->template->title            = $forum->name.' - '.__('Forum');
            $this->template->meta_description = $forum->description;
            Breadcrumbs::add(Breadcrumb::factory()->set_title($forum->name));
                        
            //count all topics
            $count = DB::select(array(DB::select(DB::expr('COUNT("id_post")')),'count'))
                        ->from(array('posts', 'p'))
                        ->where('id_post_parent','IS',NULL)
                        ->where('id_forum','=',$forum->id_forum)
                        ->cached()
                        ->execute();
              
            $count = array_keys($count->as_array('count'));
       

            $pagination = Pagination::factory(array(
                        'view'           => 'pagination',
                        'total_items'    => isset($count[0])?$count[0]:0,
            ))->route_params(array(
                        'controller' => $this->request->controller(),
                        'action'     => $this->request->action(),
                        'forum'         => $this->request->param('forum'),
            ));

            $pagination->title($this->template->title);

            //getting all the topic for the forum
            $topics =   DB::select('p.*')
                        ->select(array(DB::select(DB::expr('COUNT("id_post")'))
                            ->from(array('posts','pc'))
                            ->where('pc.id_post_parent','=',DB::expr(core::config('database.default.table_prefix').'p.id_post'))
                            ->where('pc.id_forum','=',$forum->id_forum)
                            ->where('pc.status','=',Model_Post::STATUS_ACTIVE)
                            ->group_by('pc.id_post_parent'), 'count_replies'))
                        ->select(array(DB::select('ps.created')
                            ->from(array('posts','ps'))
                            ->where('ps.id_post','=',DB::expr(core::config('database.default.table_prefix').'p.id_post'))
                            ->or_where('ps.id_post_parent','=',DB::expr(core::config('database.default.table_prefix').'p.id_post'))
                            ->where('ps.id_forum','=',$forum->id_forum)
                            ->where('ps.status','=',Model_Post::STATUS_ACTIVE)
                            ->order_by('ps.created','DESC')
                            ->limit(1), 'last_message'))
                        ->from(array('posts', 'p'))
                        ->where('id_post_parent','IS',NULL)
                        ->where('id_forum','=',$forum->id_forum)
                        ->order_by('last_message','DESC')
                        ->limit($pagination->items_per_page)
                        ->offset($pagination->offset)
                        ->as_object()
                        //->cached()
                        ->execute();

            $pagination = $pagination->render(); 

            $this->template->bind('content', $content);
            $this->template->content = View::factory('pages/forum/list',array('topics'=>$topics,'forum'=>$forum,'pagination'=>$pagination));
        }
        //not found in DB
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }
        
    }

    /**
     * displays the form new topic
     * @return [type] [description]
     */
    public function action_new()
    {
        if (!Auth::instance()->logged_in())
        {
            Alert::set(Alert::ALERT, __('Please login before posting'));
            $this->redirect(Route::url('oc-panel',array('controller'=>'auth','action'=>'login')));
        }

        $forums = Model_Forum::get_forum_count();

        if(count($forums) == 0)
        {
        	if(Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN)
        	{
        		Alert::set(Alert::INFO, __('Please, first create some Forums.'));
        		$this->redirect(Route::url('oc-panel',array('controller'=>'forum','action'=>'index')));
        	}
			else
			{
				Alert::set(Alert::INFO, __('New Topic is not available as a feature.'));
				$this->redirect('default');
			}
        }
        
        $errors = NULL;
        if($this->request->post()) //message submition  
        {
            //captcha check
            if(captcha::check('new-forum'))
            {
                $user = Auth::instance()->get_user();
                //akismet spam filter
                if(!core::akismet($user->name, $user->email,core::post('description')))
                {
                    $validation = Validation::factory($this->request->post())
                                                    ->rule('description', 'not_empty')
                                                    ->rule('description', 'min_length', array(':value', 5))
                                                    ->rule('description', 'max_length', array(':value', 1000))
                                                    ->rule('title', 'not_empty')
                                                    ->rule('title', 'min_length', array(':value', 5))
                                                    ->rule('id_forum', 'numeric');

                    if ($validation->check())
                    {
                        $topic = new Model_Post();
                        $topic->id_user  =  $user->id_user;
                        $topic->id_forum = core::post('id_forum');
                        $topic->title    = Text::banned_words(core::post('title'));
                        $topic->seotitle = $topic->gen_seotitle($topic->title);
                        $topic->description    = Text::banned_words(core::post('description'));
                        $topic->status   = Model_Post::STATUS_ACTIVE;
                        $topic->ip_address   = ip2long(Request::$client_ip);
                        $topic->save();

                        $this->redirect(Route::url('forum-topic',array('forum'=>$topic->forum->seoname,'seotitle'=>$topic->seotitle)));
                    }
                    else
                    {
                        $errors = $validation->errors('ad');
                    }
                }
                else
                {
                    Alert::set(Alert::SUCCESS, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                }
            }
            else
                Alert::set(Alert::ERROR, __('Check the form for errors'));
                    
        }

        //template header
        $this->template->title            = __('New Forum Topic');
        $this->template->meta_description = $this->template->title;
        Breadcrumbs::add(Breadcrumb::factory()->set_title($this->template->title));

        $this->template->styles              = array('css/jquery.sceditor.min.css' => 'screen');
        $this->template->scripts['footer']   = array('js/jquery.sceditor.min.js?v=144','js/forum-new.js');
            
        $this->template->bind('content', $content);
        $this->template->content = View::factory('pages/forum/new',array('forums'=>$forums));
        $content->errors = $errors;
    }


   /**
     *
     * Display single topic with replies and allows to reply
     * @throws HTTP_Exception_404
     */
    public function action_topic()
    {
        $errors = NULL;

        $topic = new Model_Post();
        $topic->where('status','=',Model_Post::STATUS_ACTIVE)
            ->where('seotitle','=',$this->request->param('seotitle',NULL))
            ->where('id_forum','IS NOT',NULL)
            ->where('id_post_parent','IS',NULL)
            ->cached()->limit(1)->find();

        if ($topic->loaded())
        {
            $forum = $topic->forum;

            $errors = $this->add_topic_reply($topic,$forum);

            Breadcrumbs::add(Breadcrumb::factory()->set_title($forum->name)
                ->set_url(Route::url('forum-list',array('forum'=>$forum->seoname))));
            Breadcrumbs::add(Breadcrumb::factory()->set_title($topic->title));

            $this->template->title            = $topic->title.' - '.$forum->name.' - '.__('Forum');
            $this->template->meta_description = $topic->description;

            //getting all the topic replies, pagination
            $replies = new Model_Post();
            $replies = $replies->where('id_post_parent','=',$topic->id_post);
            $replies_count = clone $replies;

            $pagination = Pagination::factory(array(
                        'view'           => 'pagination',
                        'total_items'    => $replies_count->count_all(),
                        'items_per_page' => self::$items_per_page,
            ))->route_params(array(
                        'controller' => $this->request->controller(),
                        'action'     => $this->request->action(),
                        'seotitle'   => $this->request->param('seotitle'),
                        'forum'      => $forum->seoname,
            ));

            $pagination->title($this->template->title);

            $replies = $replies->order_by('created','asc')
                            ->limit($pagination->items_per_page)
                            ->offset($pagination->offset)
                            ->find_all();

            $pagination = $pagination->render(); 


            $previous = new Model_Post();
            $previous = $previous->where('status','=',Model_Post::STATUS_ACTIVE)
                        ->where('id_forum','=',$topic->id_forum)
                        ->where('id_post', '<', $topic->id_post)
                        ->where('id_post_parent','IS',NULL)
                        ->order_by('created','desc')
                        ->limit(1)->find();  

            $next = new Model_Post();
            $next = $next->where('status','=',Model_Post::STATUS_ACTIVE)
                        ->where('id_forum','=',$topic->id_forum)
                        ->where('id_post', '>', $topic->id_post)
                        ->where('id_post_parent','IS',NULL)
                        ->limit(1)->find();

            $this->template->bind('content', $content);
            $this->template->content = View::factory('pages/forum/topic',array('topic'=>$topic,
                                                                                'next'=>$next,
                                                                                'previous'=>$previous,
                                                                                'replies'=>$replies,
                                                                                'errors'=>$errors,
                                                                                'forum'=>$forum,
                                                                                'pagination'=>$pagination));
        }
        //not found in DB
        else
        {
            //throw 404
            throw HTTP_Exception::factory(404,__('Page not found'));
        }

    }

    /**
     * add a repply to a topic
     * @param Model_Post  $topic 
     * @param Model_Forum $forum 
     */
    public function add_topic_reply(Model_Post $topic, Model_Forum $forum)
    {
        //if loged in add styles and check for post
        if (Auth::instance()->logged_in())
        {
            $this->template->styles              = array('css/jquery.sceditor.min.css' => 'screen');
            $this->template->scripts['footer']   = array('js/jquery.sceditor.min.js?v=144','js/forum-new.js');

            $errors = NULL;
            if($this->request->post()) //message submition  
            {
                //captcha check
                if(captcha::check('new-reply-topic'))
                {
                    $user = Auth::instance()->get_user();
                    //akismet spam filter
                    if(!core::akismet($user->name, $user->email,core::post('description')))
                    {
                        $validation = Validation::factory($this->request->post())
                                                        ->rule('description', 'not_empty')
                                                        ->rule('description', 'max_length', array(':value', 1000))
                                                        ->rule('description', 'min_length', array(':value', 5));

                        if ($validation->check())
                        {
                            $reply = new Model_Post();
                            $reply->id_user  =  $user->id_user;
                            $reply->id_forum = $forum->id_forum;
                            $reply->id_post_parent = $topic->id_post;
                            $reply->title    = substr(core::post('description'),0,145);
                            $reply->seotitle = $reply->gen_seotitle($reply->title);
                            $reply->description    = Text::banned_words(core::post('description'));
                            $reply->status   = Model_Post::STATUS_ACTIVE;
                            $reply->ip_address   = ip2long(Request::$client_ip);
                            $reply->save();
                            unset($_POST['description']);
                            Alert::set(Alert::SUCCESS, __('Reply added, thanks!'));
                            
                        }
                        else
                        {
                            $errors = $validation->errors('ad');
                        }
                    }
                    else
                    {
                        Alert::set(Alert::ERROR, __('This email has been considered as spam! We are sorry but we can not send this email.'));
                    }
                }
                else
                    Alert::set(Alert::ERROR, __('Check the form for errors'));
                    
            }

            return $errors;

        }
    }

} // End Forum
