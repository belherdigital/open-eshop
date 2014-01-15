<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Blog/Forum Posts
 *
 * @author      Chema <chema@garridodiaz.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Post extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'posts';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_post';

    /**
     * @var  array  ORM Dependency/hirerachy
     */
    protected $_has_many = array(
        'comments' => array(
            'model'       => 'comment',
            'foreign_key' => 'id_comment',
        ),
    );

    protected $_belongs_to = array(
        'user'       => array('model'       => 'user','foreign_key' => 'id_user'),
        'forum'       => array('model'       => 'forum','foreign_key' => 'id_forum'),
    );

    public function filters()
    {
        return array(
                'seotitle' => array(
                                array(array($this, 'gen_seotitle'))
                              ),
                
        );
    }


    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   
        $form->fields['id_user']['value']       =  auth::instance()->get_user()->id_user;
        $form->fields['id_user']['display_as']  = 'hidden';

        $form->fields['seotitle']['display_as']  = 'hidden';
        
    }


    public function exclude_fields()
    {
        return array('created','ip_address','id_forum');
    }


    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     * 
     */
    public function gen_seotitle($seotitle)
    {
        //in case seotitle is really small or null
        if (strlen($seotitle)<3)
            $seotitle = $this->title;

        $seotitle = URL::title($seotitle);

        if ($seotitle != $this->seotitle)
        {
            $cat = new self;
            //find a user same seotitle
            $s = $cat->where('seotitle', '=', $seotitle)->limit(1)->find();

            //found, increment the last digit of the seotitle
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seotitle.'-'.$cont;
                    $cat = new self;
                    unset($s);
                    $s = $cat->where('seotitle', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seotitle = $attempt;
                    }
                    else
                    {
                        $cont++;
                    }
                }
            }
        }
        

        return $seotitle;
    }



    /**
     * prints the disqus script from the view for blogs!
     * @return string HTML or false in case not loaded
     */
    public function disqus()
    {
        if($this->loaded())
        {
            if ($this->status == 1 AND strlen(core::config('general.blog_disqus'))>0 )
            {
                return View::factory('pages/disqus',
                                array('disqus'=>core::config('general.blog_disqus')))
                        ->render();
            }
        }
    
        return FALSE;
    }
}