<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Captcha extends Kohana_Controller {

	public function action_image()
	{
		$token = $this->request->param('id');

        //removig the & we add to refresh the image.
        if (($amp_pos = strpos($token, '&'))>1)
            $token = substr($token, 0,$amp_pos);
        
		$captcha = new captcha();
		die($captcha->image($token));
	}
}