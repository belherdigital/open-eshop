<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract plugin controller class. 
 * It adds hooks to the construct, before and after methods.
 *
 * example to execute functions instead of the current action:
 * 
 * To go along before/after the action is called:
 * 
 *
 * @package    OC/Plugin
 * @category   Controller
 * @author     Chema <chema@open-classifieds.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */
abstract class Kohana_Controller  {

	/**
	 * @var  Request  Request that created the controller
	 */
	public $request;

	/**
	 * @var  Response The response that will be returned from controller
	 */
	public $response;
	
	/**
	 * @var Hook name
	 */
	protected $hook;
	
	/**
	 * Creates a new controller instance. Each controller must be constructed
	 * with the request object that created it.
	 *
	 * @param   Request   $request  Request that created the controller
	 * @param   Response  $response The request's response
	 * @return  void
	 */
	public function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;
		
		$this->hook = $this->request->controller().'_'.$this->request->action();
		///var_dump($this->hook);
		Hook::do_action($this->hook,$this->request->param());
	}

	/**
	 * Automatically executed before the controller action.
	 * 
	 * @return  void
	 */
	public function before()
	{
		Hook::do_action($this->hook.'_before',$this->request->param());
	}

	/**
	 * Automatically executed after the controller action. 
	 *
	 * @return  void
	 */
	public function after()
	{
		Hook::do_action($this->hook.'_after',$this->request->param());
	}

} // End Controller
