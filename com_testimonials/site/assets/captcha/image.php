<?php /*** Testimonials Component for Joomla 3* @package Testimonials* @author JoomPlace Team* @copyright Copyright (C) JoomPlace, www.joomplace.com* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html*/defined('_JEXEC') or die('Restricted access');//if(!defined('_JEXEC')) define('_JEXEC', 1);if(!defined('JPATH_BASE')) define('JPATH_BASE', dirname(__FILE__)."/../../../../" );require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'defines.php' );require_once ( JPATH_BASE .DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'framework.php' );$mainframe = JFactory::getApplication('site');$font = JPATH_COMPONENT.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'captcha'.DIRECTORY_SEPARATOR.'monofont.ttf';$br = 255;$bg = 255;$bb = 255;$tr = 20;$tg = 40;$tb = 100;$nr = 100;$ng = 120;$nb = 180;$width = 150;$height = 40;$characters = 6;$possible = '23456789bcdfghjkmnpqrstvwxyz';$code = '';$i = 0;while ($i < $characters) { 	$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);	$i++;}$sessions = JFactory::getSession();$sessions->set("captcha", $code);$font_size = $height * 0.75;$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');$background_color = imagecolorallocate($image, $br, $bg, $bb);$text_color = imagecolorallocate($image, $tr, $tg, $tb);$noise_color = imagecolorallocate($image, $nr, $ng, $nb);for( $i=0; $i<($width*$height)/3; $i++ ) {	imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);}for( $i=0; $i<($width*$height)/150; $i++ ) {	imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);}$textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');$x = ($width - $textbox[4])/2;$y = ($height - $textbox[5])/2;imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code) or die('Error in imagettftext function');header('Content-Type: image/jpeg');imagejpeg($image);imagedestroy($image);?>