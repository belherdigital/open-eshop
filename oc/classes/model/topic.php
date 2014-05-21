<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Forum Posts, extends topic, so we can use it at controller topic
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Topic extends Model_Post {

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
        

        $form->fields['id_forum']['display_as']  = 'select';
        $form->fields['id_forum']['caption']  = 'name';
        
    }


    public function exclude_fields()
    {
        return array('created','ip_address');
    }
}