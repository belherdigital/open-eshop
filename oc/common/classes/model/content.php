<?php defined('SYSPATH') or die('No direct script access.');
/**
 * content
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */
class Model_Content extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'content';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_content';

    public function filters()
    {
        return array(
                'seotitle' => array(
                                array(array($this, 'gen_seotitle'))
                              ),
                
        );
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

        //this are reserved pages names used in the routes.php
        $banned_names = array(URL::title(__('search')),URL::title(__('contact')),URL::title(__('maintenance')),URL::title(__('publish new')),URL::title(__('map')));
        //same name as a route..shit!
        if (in_array($seotitle, $banned_names))
            $seotitle = URL::title(__('page')).'-'.$seotitle; 

        if ($seotitle != $this->seotitle)
        {
            $cat = new self;
            //find a user same seotitle
            $s = $cat->where('seotitle', '=', $seotitle)->where('locale', '=', $this->locale)->limit(1)->find();

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
     * get the model filtered
     * @param  string $seotitle
     * @param  string $type
     */
    public static function get_by_title($seotitle, $type = 'page')
    {   
        //we remove the '.' replace them by _ since its not allowed as seo title. Just in case we forgot somewhere in the code a '.'
        $seotitle = str_replace('.', '-', $seotitle);

        $content = new self();
        
        // if visitor or user with ROLE_USER display content with STATUS_ACTIVE
        if (! Auth::instance()->logged_in() OR 
            (Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_USER))
            $content->where('status','=', 1);

        $content = $content->where('seotitle','=', $seotitle)
                 ->where('locale','=', i18n::$locale)
                 ->where('type','=', $type)
                 ->limit(1)->cached()->find();

        //was not found try first translation in english
        if (!$content->loaded())
        {

            // if visitor or user with ROLE_USER display content with STATUS_ACTIVE
            if (! Auth::instance()->logged_in() OR 
                (Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_USER))
                $content->where('status','=', 1);
                
            $content = $content->where('seotitle','=', $seotitle)
                 ->where('locale','=', i18n::$locale_default)
                 ->where('type','=', $type)
                 ->limit(1)->cached()->find();
        }
        
        //was not found try first translation with that seotitle
        if (!$content->loaded())
        {

            // if visitor or user with ROLE_USER display content with STATUS_ACTIVE
            if (! Auth::instance()->logged_in() OR 
                (Auth::instance()->logged_in() AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_USER))
                $content->where('status','=', 1);
                
            $content = $content->where('seotitle','=', $seotitle)
                 ->where('type','=', $type)
                 ->limit(1)->cached()->find();
        }

        return $content;
    }

    /**
     * get the model filtered
     * @param  string $seotitle
     * @param  array $replace try to find the matches and replace them
     * @param  string $type
     */
    public static function text($seotitle, $replace = NULL, $type = 'page')
    {
        if ($replace===NULL) $replace = array();
        $content = self::get_by_title($seotitle, $type);
        if ($content->loaded())
        {
            if (Auth::instance()->logged_in())
            {
                $user = Auth::instance()->get_user();
                //adding extra replaces
                $replace+= array('[USER.NAME]' =>  $user->name,
                                 '[USER.EMAIL]' =>  $user->email
                                );
            }

            return str_replace(array_keys($replace), array_values($replace), $content->description);
        }
        return FALSE;

    }

    public static function get_pages()
    {
      $pages = new self;
      $pages = $pages ->select('seotitle','title')
                        ->where('type','=', 'page')
                        ->where('status','=', 1)
                        ->order_by('order','asc')
                        ->cached()
                        ->find_all();
      return $pages;
    }
    
    public static function get_contents($type, $locale = NULL)
    {
      if($locale == NULL)
        $locale = core::config('i18n.locale');

      $pages = new self;
      $pages = $pages ->select('seotitle','title')
                        ->where('type','=', $type)
                        ->where('locale','=', $locale)
                        ->order_by('order','asc')
                        ->find_all();
      return $pages;
    }

    public function form_setup($form)
    {
        $form->fields['order']['display_as']   = 'select';
        $form->fields['order']['options']      = range(0, 30);

        $form->fields['locale']['display_as']  = 'select';
        $form->fields['locale']['options']     = i18n::get_languages();

        $form->fields['seotitle']['display_as']  = 'hidden';
    }

    public function exclude_fields()
    {
        return array('created');
    }

    /**
     * is used to create contets if they dont exist
     * @param array
     * @return boolean 
     */
    public static function content_array($contents)
    {
        $return = FALSE;
        foreach ($contents as $c => $value) 
        {
            // get config from DB
            $cont = new self();
            $cont->where('seotitle','=',$value['seotitle'])
                  ->limit(1)->find();

            // if do not exist (not loaded) create them, else do nothing
            if (!$cont->loaded())
            {
                $cont->order = $value['order'];
                $cont->title = $value['title'];
                $cont->seotitle = $value['seotitle'];
                $cont->description = $value['description'];
                $cont->from_email = $value['from_email'];
                $cont->type = $value['type'];
                $cont->status = $value['status'];
                $cont->save();

                $return = TRUE;
            }
        }   

        return $return;
    }


    public static function copy($from_locale,$to_locale,$type)
    {
        //get the contents for type locale
        $contents = self::get_contents($type,$from_locale);
        
        $i = 0;

        foreach ($contents as $content) {

            $to_locale_content = new self();
            
            $to_locale_content = $to_locale_content->where('seotitle','=', $content->seotitle)
                ->where('locale','=', $to_locale)
                ->where('type','=', $type)
                ->limit(1)->cached()->find();

            if ( ! $to_locale_content->loaded())
            {
                $to_locale_content = new self();
                $to_locale_content->locale      = $to_locale;
                $to_locale_content->order       = $content->order;
                $to_locale_content->title       = $content->title;
                $to_locale_content->seotitle    = $content->seotitle;
                $to_locale_content->description = $content->description;
                $to_locale_content->from_email  = $content->from_email;
                $to_locale_content->created     = $content->created;
                $to_locale_content->type        = $content->type;
                $to_locale_content->status      = $content->status;

                try 
                {
                    $to_locale_content->save();
                    $i++;
                } 
                catch (Exception $e) 
                {
                    Alert::set(Alert::ERROR, $e->getMessage());
                }
            }
        }

        Core::delete_cache();

        if ($i > 0)
            return TRUE;

        return FALSE;

    }

    protected $_table_columns =  
array (
  'id_content' => 
  array (
    'type' => 'int',
    'min' => '0',
    'max' => '4294967295',
    'column_name' => 'id_content',
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
   'locale' => 
  array (
    'type' => 'string',
    'column_name' => 'locale',
    'column_default' => 'en_UK',
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 2,
    'character_maximum_length' => '8',
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
  'title' => 
  array (
    'type' => 'string',
    'column_name' => 'title',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 4,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'seotitle' => 
  array (
    'type' => 'string',
    'column_name' => 'seotitle',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => false,
    'ordinal_position' => 5,
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
    'character_maximum_length' => '65535',
    'column_name' => 'description',
    'column_default' => NULL,
    'data_type' => 'text',
    'is_nullable' => true,
    'ordinal_position' => 6,
    'collation_name' => 'utf8_general_ci',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'from_email' => 
  array (
    'type' => 'string',
    'column_name' => 'from_email',
    'column_default' => NULL,
    'data_type' => 'varchar',
    'is_nullable' => true,
    'ordinal_position' => 7,
    'character_maximum_length' => '145',
    'collation_name' => 'utf8_general_ci',
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
    'ordinal_position' => 8,
    'comment' => '',
    'extra' => 'on update CURRENT_TIMESTAMP',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'type' => 
  array (
    'type' => 'string',
    'column_name' => 'type',
    'column_default' => NULL,
    'data_type' => 'enum',
    'is_nullable' => false,
    'ordinal_position' => 9,
    'collation_name' => 'utf8_general_ci',
    'options' => 
    array (
      0 => 'page',
      1 => 'email',
      2 => 'help',
    ),
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
  'status' => 
  array (
    'type' => 'int',
    'min' => '-128',
    'max' => '127',
    'column_name' => 'status',
    'column_default' => '0',
    'data_type' => 'tinyint',
    'is_nullable' => false,
    'ordinal_position' => 10,
    'display' => '1',
    'comment' => '',
    'extra' => '',
    'key' => '',
    'privileges' => 'select,insert,update,references',
  ),
);
} // END Model_Content