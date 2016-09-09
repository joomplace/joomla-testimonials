<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomplace\Component\Testimonials\Admin;

use \ReflectionMethod;

defined('_JEXEC') or die;

class Controller extends \JControllerBase{

	protected $_controllers = array();

	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since   12.1
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute(){

		$app = $this->getApplication();
		$input = $this->getInput();

		$controller = $input->getString('controller','dashboard');
		$action = $input->getString('action','index');

		jimport('controller.'.$controller,JPATH_ADMINISTRATOR.DS.'components/com_testimonials/');

		$controllerClass     = $this->getController(__NAMESPACE__.'\\Controller\\'.$controller);


		$arguments = array();
		$ref = new ReflectionMethod($controllerClass, $action);
		foreach( $ref->getParameters() as $param) {
			if($input->get($param->name,null)===null){
				trigger_error("Need to define $param->name", E_USER_ERROR);
			}
			$arguments[] = $input->get($param->name);
		}
		call_user_func_array(array($controllerClass, $action), $arguments);


		return true;
	}

	/**
	 * @param $controllerClassName
	 *
	 * @return mixed
	 *
	 * @since version
	 */
	public function getController($controllerClassName)
	{
		/*
		 * Let's restrict double creation throw out app since we have no single tone
		 */
		if (!$this->_controllers[$controllerClassName])
		{
			$this->_controllers[$controllerClassName] = new $controllerClassName();
		}

		return $this->_controllers[$controllerClassName];
	}

}
