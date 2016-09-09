<?php
/**
 * @package     Joomplace\Component\Testimonials\Admin\Controller
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomplace\Component\Testimonials\Admin\Controller;

use \Joomplace\Component\Testimonials\Admin\Framework\Controller as Controller;

class Dashboard extends Controller
{

	public function index(){
		echo $this->getClassName();
	}

	public function echoVar($var){

		$this->render('dashboard',$var);
	}

}