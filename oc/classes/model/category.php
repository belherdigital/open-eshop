<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@open-classifieds.com>
 * @package		OC
 * @copyright	(c) 2009-2013 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_Category extends ORM {


	/**
	 * Table name to use
	 *
	 * @access	protected
	 * @var		string	$_table_name default [singular model name]
	 */
	protected $_table_name = 'categories';

	/**
	 * Column to use as primary key
	 *
	 * @access	protected
	 * @var		string	$_primary_key default [id]
	 */
	protected $_primary_key = 'id_category';


	/**
	 * @var  array  ORM Dependency/hirerachy
	 */
	protected $_has_many = array(
		'products' => array(
			'model'       => 'product',
			'foreign_key' => 'id_category',
		),
	);

    protected $_belongs_to = array(
        'parent'   => array('model'       => 'Category',
                            'foreign_key' => 'id_category_parent'),
    );


	/**
     * global Model Category instance get from controller so we can access from anywhere like Model_Category::current()
     * @var Model_Location
     */
    protected static $_current = NULL;

    /**
     * returns the current category
     * @return Model_Category
     */
    public static function current()
    {
        //we don't have so let's retrieve
        if (self::$_current === NULL)
        {
            self::$_current = new self();
            if(Request::current()->param('category') != URL::title(__('all')))
            {
                self::$_current = self::$_current->where('seoname', '=', Request::current()->param('category'))
                                                    ->limit(1)->cached()->find();
            }
        }

        return self::$_current;
    }

	/**
	 * Rule definitions for validation
	 *
	 * @return array
	 */
	public function rules()
	{
		return array('id_category'		=> array(array('numeric')),
			        'name'				=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'order'				=> array(),
			        'id_category_parent'=> array(),
			        'parent_deep'		=> array(),
			        'seoname'			=> array(array('not_empty'), array('max_length', array(':value', 145)), ),
			        'description'		=> array(array('max_length', array(':value', 255)), ),
			         );
	}

	/**
	 * Label definitions for validation
	 *
	 * @return array
	 */
	public function labels()
	{
		return array('id_category'			=> __('Id'),
			        'name'					=> __('Name'),
			        'order'					=> __('Order'),
			        'created'				=> __('Created'),
			        'id_category_parent'	=> __('Parent'),
			        'parent_deep'			=> __('Parent deep'),
			        'seoname'				=> __('Seoname'),
			        'description'			=> __('Description'),
			        );
	}
	
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
                'id_category_parent' => array(
                                array(array($this, 'check_parent'))
                              )
        );
    }

    /**
     * we get the categories in an array and a multidimensional array to know the deep
     * @return array 
     */
    public static function get_all()
    {
        $cats = new self;
        $cats = $cats->order_by('order','asc')->find_all()->cached()->as_array('id_category');

        //transform the cats to an array
        $cats_arr = array();
        foreach ($cats as $cat) 
        {
            $cats_arr[$cat->id_category] =  array('name'               => $cat->name,
                                                  'order'              => $cat->order,
                                                  'id_category_parent' => $cat->id_category_parent,
                                                  'parent_deep'        => $cat->parent_deep,
                                                  'seoname'            => $cat->seoname,
                                                  'id'                 => $cat->id_category,
                                                );
        }

        //for each category we get his siblings
        $cats_s = array();
        foreach ($cats as $cat) 
             $cats_s[$cat->id_category_parent][] = $cat->id_category;
        

        //last build multidimensional array
        if (count($cats_s)>1)
            $cats_m = self::multi_cats($cats_s);
        else
            $cats_m = array();
        
        return array($cats_arr,$cats_m);
    }

    /**
     * gets a multidimensional array wit the categories
     * @param  array  $cats_s      id_category->array(id_siblings)
     * @param  integer $id_category 
     * @param  integer $deep        
     * @return array               
     */
    public static function multi_cats($cats_s,$id_category = 1, $deep = 0)
    {    
        $ret = NULL;
        //we take all the siblings and try to set the grandsons...
        //we check that the id_category sibling has other siblings
        if (isset($cats_s[$id_category]))
        {
            foreach ($cats_s[$id_category] as $id_sibling) 
            {
                //we check that the id_category sibling has other siblings
                if (isset($cats_s[$id_sibling]))
                {
                    if (is_array($cats_s[$id_sibling]))
                    {
                        $ret[$id_sibling] = self::multi_cats($cats_s,$id_sibling,$deep+1);
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
	 * 
	 */
	
	public static function category_parent()
	{
		$parent = new self;
		$list = $parent->where('id_category_parent','=',1)->find_all();
		
		$list_parent = array();
		foreach ($list as $l) 
		{
			$list_parent[$l->id_category] = $l->name;	
		}
		return $list_parent;
	}

	/**
	 * counts the categories ads
	 * @return array
	 */
	public static function get_category_count()
	{

        $cats = DB::select('c.*')
                ->select(array(DB::select('COUNT("id_product")')
                        ->from(array('products','a'))
                        ->where('a.id_category','=',DB::expr(core::config('database.default.table_prefix').'c.id_category'))
                        ->where('a.status','=',Model_Product::STATUS_ACTIVE)
                        ->group_by('id_category'), 'count'))
                ->from(array('categories', 'c'))
                ->order_by('order','asc')
                ->as_object()
                ->cached()
                ->execute();

        $cats_count = array();
        $parent_count = array();

        foreach ($cats as $c) 
        {
            $cats_count[$c->id_category] = array('id_category'    => $c->id_category,
                                    'seoname'           => $c->seoname,
                                    'name'          => $c->name,
                                    'id_category_parent'        => $c->id_category_parent,
                                    'parent_deep'   => $c->parent_deep,
                                    'order'         => $c->order,
                                    'has_siblings'  => FALSE,
                                    'count'         => (is_numeric($c->count))?$c->count:0
                                    );
            //counting the ads the parent have
            if ($c->id_category_parent!=0)
            {
                if (!isset($parent_count[$c->id_category_parent]))
                    $parent_count[$c->id_category_parent] = 0;

                $parent_count[$c->id_category_parent]+=$c->count;
            }
            
        }

        foreach ($parent_count as $id_category => $count) 
        {
            //attaching the count to the parents so we know each parent how many ads have
            $cats_count[$id_category]['count'] += $count;
            $cats_count[$id_category]['has_siblings'] = TRUE;
        }
            
		
		return $cats_count;
	}

    /**
     * returns all the siblings ids+ the idcategory, used to filter the ads
     * @return array
     */
	public function get_siblings_ids()
    {
        $cats = array();

        if ($this->loaded())
        {
            $cats[] = $this->id_category;

            $cat_ids = new self();
            $cat_ids = $cat_ids->where('id_category_parent','=',$this->id_category)->cached()->find_all();

            foreach ($cat_ids as $c) 
            {
                $cats[] = $c->id_category;
            }
        }

        return $cats;
    }
    
	/**
	 * 
	 * formmanager definitions
	 * 
	 */
	public function form_setup($form)
	{	
		$form->fields['description']['display_as'] = 'textarea';

        $form->fields['id_category_parent']['display_as']   = 'select';
        $form->fields['id_category_parent']['caption']      = 'name';   

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

        //this are reserved categories names used in the routes.php
        $banned_names = array('blog','faq','forum','oc-panel','rss','oc-error','user','api',URL::title(__('all')));
        //same name as a route..shit!
        if (in_array($seoname, $banned_names))
            $seoname = URL::title(__('category')).'-'.$seoname; 

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
        return (is_numeric($id_parent))? $id_parent:1;
    }

    protected $_table_columns =  
array (
  'id_category' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category',
    'column_default' => NULL,
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 1,
    'display' => '10',
    'comment' => '',
    'extra' => 'auto_increment',
    'key' => 'PRI',
    'privileges' => 'select,insert,update,references',
  ),
  'name' => 
  array (
    'type' => 'string',
    'column_name' => 'name',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'order' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'order',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 3,
    'display' => '2',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'created' => 
  array (
    'type' => 'string',
    'column_name' => 'created',
    'column_default' => 'CURRENT_TIMESTAMP',
    'data_type' => 'timestamp',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'comment' => '',
    'extra' => 'on update CURRENT_TIMESTAMP',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'id_category_parent' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_category_parent',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 5,
    'display' => '10',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'parent_deep' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'parent_deep',
    'column_default' => '0',
    'data_type' => 'int unsigned',
    'is_nullable' => false,
    'ordinal_position' => 6,
    'display' => '2',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'seoname' => 
  array (
    'type' => 'string',
    'column_name' => 'seoname',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 7,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => 'UNI',
    'privileges' => 'select,insert,update,references',
  ),
  'description' => 
  array (
    'type' => 'string',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 8,
    'character_maximum_length' => '255',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);

} // END Model_Category