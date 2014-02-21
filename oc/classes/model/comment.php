<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Blog/Forum Comments
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Comments extends ORM {

    /**
     * @var  string  Table name
     */
    protected $_table_name = 'comments';

    /**
     * @var  string  PrimaryKey field name
     */
    protected $_primary_key = 'id_comment';


    protected $_belongs_to = array(
        'user'       => array('model'       => 'user','foreign_key' => 'id_user'),
        'forum'       => array('model'       => 'forum','foreign_key' => 'id_forum'),
        'post'       => array('model'       => 'post','foreign_key' => 'id_post'),
    );



    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   
       
    }


    public function exclude_fields()
    {
    }

}