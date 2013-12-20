<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller Market seettings
 */


class Controller_Panel_Market extends Auth_Controller {


    public function action_index()
    {
        
        // validation active 
        Breadcrumbs::add(Breadcrumb::factory()->set_title(__('Market')));  
        $this->template->title = __('Market');  

        $market_url = (Kohana::$environment!== Kohana::DEVELOPMENT)? 'market.open-eshop.com':'eshop.lo';
        $this->template->scripts['footer'][] = 'http://'.$market_url.'/embed.js';

        $market = Core::get_market();


        $this->template->content = View::factory('oc-panel/pages/market/index', array('market' => $market));
    }



}//end of controller