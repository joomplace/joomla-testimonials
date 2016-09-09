<?php
/**
 * @package     Joomplace\Component\Testimonials\Admin\Framework
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomplace\Component\Testimonials\Admin\Framework;


class Controller
{
	protected $_classname;

	protected function getClassName(){
		if(!$this->_classname){
			$this->_classname = new ReflectionClass($this);
		}
		return $this->_classname;
	}

	protected function render($view, $vars){

		echo "<pre>";
		print_r($vars);
		echo "</pre>";

	}

}