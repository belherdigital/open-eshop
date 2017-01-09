<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Crontab Model
 *
 * @author      Chema <chema@open-classifieds.com>
 * @package     Core
 * @copyright   (c) 2009-2013 Open Classifieds Team
 * @license     GPL v3
 */

class Model_Crontab extends Cron {


    /**
     * 
     * formmanager definitions
     * 
     */
    public function form_setup($form)
    {   
        $form->fields['description']['display_as'] = 'textarea';
    }


    public function exclude_fields()
    {
        return array('date_created');
    }

}