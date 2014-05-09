<?php defined('SYSPATH') or die('No direct script access.');
/**
 * model forums
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2014 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Forum extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'forums';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_forum';


	/**
	 * @var  array  ORM Dependency/hirerachy
	 */
	protected $_has_many = array(
		'topics' => array(
			'model'       => 'posts',
			'foreign_key' => 'id_forum',
		),
	);

    protected $_belongs_to = array(
        'parent'   => array('model'       => 'Forum',
                            'foreign_key' => 'id_forum_parent'),
    );



	
    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
                'seoname' => array(
                                array(array($this, 'gen_seoname'))
                              ),
                'id_forum_parent' => array(
                                array(array($this, 'check_parent'))
                              )
        );
    }

    /**
     * we get the forums in an array and a multidimensional array to know the deep
     * @return array 
     */
    public static function get_all()
    {
        $forums = new self;
        $forums = $forums->order_by('order','asc')->find_all()->cached()->as_array('id_forum');

        //transform the forums to an array
        $forums_arr = array();

        foreach ($forums as $forum) 
        {
            $forums_arr[$forum->id_forum] =  array('name'              => $forum->name,
                                                  'order'              => $forum->order,
                                                  'id_forum_parent'    => $forum->id_forum_parent,
                                                  'parent_deep'        => $forum->parent_deep,
                                                  'seoname'            => $forum->seoname,
                                                  'id'                 => $forum->id_forum,
                                                );
        }

        //for each forum we get his siblings
        $forums_s = array();
        foreach ($forums as $forum) 
             $forums_s[$forum->id_forum_parent][] = $forum->id_forum;
        
        //last build multidimensional array
        if (count($forums_s)>0)
            $forums_m = self::multi_forums($forums_s);
        else
            $forums_m = array();
        
        //array of forum info and array order
        return array($forums_arr,$forums_m);
    }

    /**
     * gets a multidimensional array wit the forums
     * @param  array  $forums_s      id_forum->array(id_siblings)
     * @param  integer $id_forum 
     * @param  integer $deep        
     * @return array               
     */
    public static function multi_forums($forums_s,$id_forum = 0, $deep = 0)
    {    
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_forum sibling has other siblings
        if (isset($forums_s[$id_forum]))
        {
            foreach ($forums_s[$id_forum] as $id_sibling) 
            {
                //we check that the id_forum sibling has other siblings
                if (isset($forums_s[$id_sibling]))
                {
                    if (is_array($forums_s[$id_sibling]))
                    {
                        $ret[$id_sibling] = self::multi_forums($forums_s,$id_sibling,$deep+1);
                    }
                }
                //no siblings we only set the key
                else 
                    $ret[$id_sibling] = NULL;
                
            }
        }
        return $ret;
    }



	/**
	 * counts the forums topics
	 * @return array
	 */
	public static function get_forum_count()
	{
        $forums = DB::select('f.*')
                ->select(array(DB::select('COUNT("id_post")')
                        ->from(array('posts','p'))
                        ->where('p.id_post_parent','IS', NULL)
                        ->where('p.id_forum','=',DB::expr(core::config('database.default.table_prefix').'f.id_forum'))
                        ->where('p.status','=',Model_Post::STATUS_ACTIVE)
                        ->group_by('id_forum'), 'count'))
                ->select(array(DB::select('created')
                        ->from(array('posts','p'))
                        ->where('p.id_forum','=',DB::expr(core::config('database.default.table_prefix').'f.id_forum'))
                        ->where('p.status','=',Model_Post::STATUS_ACTIVE)
                        ->order_by('created','desc')
                        ->limit(1), 'last_message'))
                ->from(array('forums', 'f'))
                ->order_by('order','asc')
                ->as_object()
                ->cached()
                ->execute();

        $forum_count = array();
        $parent_count = array();

        foreach ($forums as $f) 
        {
            $forum_count[$f->id_forum] = array('id_forum'           => $f->id_forum,
                                                'seoname'           => $f->seoname,
                                                'name'              => $f->name,
                                                'id_forum_parent'   => $f->id_forum_parent,
                                                'parent_deep'       => $f->parent_deep,
                                                'order'             => $f->order,
                                                'has_siblings'      => FALSE,
                                                'count'             => (is_numeric($f->count))?$f->count:0,
                                                'last_message'      => $f->last_message
                                                );
            //counting the ads the parent have
            if ($f->id_forum_parent!=0)
            {
                if (!isset($parent_count[$f->id_forum_parent]))
                    $parent_count[$f->id_forum_parent] = 0;

                $parent_count[$f->id_forum_parent]+=$f->count;
            }
            
        }

        foreach ($parent_count as $id_forum => $count) 
        {
            //attaching the count to the parents so we know each parent how many ads have
            $forum_count[$id_forum]['count'] += $count;
            $forum_count[$id_forum]['has_siblings'] = TRUE;
        }
		
		return $forum_count;
	}


    
	/**
	 * 
	 * formmanager definitions
	 * 
	 */
	public function form_setup($form)
	{	
		$form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_forum_parent']['display_as']   = 'select';
        $form->fields['id_forum_parent']['caption']      = 'name';   

        $form->fields['parent_deep']['display_as']   = 'select';
        $form->fields['parent_deep']['options']      = range(0, 10);
        $form->fields['order']['display_as']   = 'select';
        $form->fields['order']['options']      = range(1, 100);
	}


	public function exclude_fields()
	{
		return array('created');
	}

    /**
     * return the title formatted for the URL
     *
     * @param  string $title
     * 
     */
    public function gen_seoname($seoname)
    {
        //in case seoname is really small or null
        if (strlen($seoname)<3)
            $seoname = $this->name;

        $seoname = URL::title($seoname);

        if ($seoname != $this->seoname)
        {
            $cat = new self;
            //find a user same seoname
            $s = $cat->where('seoname', '=', $seoname)->limit(1)->find();

            //found, increment the last digit of the seoname
            if ($s->loaded())
            {
                $cont = 2;
                $loop = TRUE;
                while($loop)
                {
                    $attempt = $seoname.'-'.$cont;
                    $cat = new self;
                    unset($s);
                    $s = $cat->where('seoname', '=', $attempt)->limit(1)->find();
                    if(!$s->loaded())
                    {
                        $loop = FALSE;
                        $seoname = $attempt;
                    }
                    else
                    {
                        $cont++;
                    }
                }
            }
        }
        

        return $seoname;
    }

    /**
     * rule to verify that we selected a parent if not put the root location
     * @param  integer $id_parent 
     * @return integer                     
     */
    public function check_parent($id_parent)
    {
        return (is_numeric($id_parent))? $id_parent:0;
    }


} // END Model_Category