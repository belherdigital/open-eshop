<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cron extends Controller {


    public function action_run()
    {
        $this->auto_render = FALSE;
        $this->template = View::factory('js');
        $this->template->content = Cron::run();
    }


    /**
     * just added for testing purposes
     * @return [type] [description]
     */
    public static function log()
    {
        Kohana::$log->add(Log::ERROR, Date::unix2mysql());
    }


} // End Welcome
