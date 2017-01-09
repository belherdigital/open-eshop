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


    /**
     * Deletes a single record while ignoring relationships.
     *
     * @chainable
     * @throws Kohana_Exception
     * @return ORM
     */
    public function delete()
    {
        if ( ! $this->_loaded)
            throw new Kohana_Exception('Cannot delete :model model because it is not loaded.', array(':model' => $this->_object_name));
       
        //delete replies for that topic
        DB::delete('posts')->where('id_post_parent', '=',$this->id_post)->execute();
        
        
        parent::delete();
    }
}