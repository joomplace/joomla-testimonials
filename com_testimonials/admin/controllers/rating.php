<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');
 
/**
 * Rating Controller
 */
class TestimonialsControllerRating extends JControllerForm
{
	function addVote()
    {
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;
        $voteval = $jinput->getInt('voteval', 0);
        $id = $jinput->getInt('testimonial', 0);

		if ($user->get('id')) {
			$sql = "SELECT COUNT(*) FROM #__tm_rating WHERE user_id='".$user->get('id')."' AND tm_id = '".$id."'";
			$db->setQuery($sql);
			$result = $db->loadResult();

			if ($result > 0) {
				$sql = "UPDATE #__tm_rating SET voteval='".$voteval."' WHERE tm_id='".$id."' AND user_id = '".$user->get('id')."'";
				$db->setQuery($sql);
				$db->query();
			}else {
				$sql = "INSERT into #__tm_rating VALUES('".$id."' , '".$user->get('id')."', '".$voteval."')";
				$db->setQuery($sql);
				$db->query();
			}
		} else {
			$sql = "SELECT COUNT(*) FROM #__tm_rating WHERE user_id='".$_SERVER['REMOTE_ADDR']."' AND tm_id = '".$id."'";
			$db->setQuery($sql);
			$result = $db->loadResult();

			if ($result == 0) {
				$sql = "INSERT into #__tm_rating VALUES('".$id."' , '".$_SERVER['REMOTE_ADDR']."', '".$voteval."')";
				$db->setQuery($sql);
				$db->query();
			}else {
				$out['message'] = JText::_('COM_RATING_YOU_ALREADY_VOTED');
			}
		}

		$sql = "SELECT SUM(voteval) / COUNT(voteval) FROM #__tm_rating WHERE tm_id='".$id."'";
		$db->setQuery($sql);
		$out['value'] = round($db->loadResult());

		echo json_encode($out);
		die;
	}

	function addcomment()
    {
        $jinput = JFactory::getApplication()->input;
		$id = $jinput->getInt('id', 0);
		$text = $jinput->get('text', '', 'STRING');
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$text = nl2br(strip_tags($text, "<p><br><a><b>"));
		$mass = array($user->id, $text);
		$comment = implode('|',$mass);
		
		$db->setQuery("SELECT `id` FROM #__tm_testimonials WHERE `id` = '".$id."' AND `comment` <> ''");
		$exist = $db->loadResult();
				
		$sql = "UPDATE #__tm_testimonials SET comment='".$db->escape($comment)."' WHERE id='".$id."'";
		$db->setQuery($sql);
		$db->query();
		
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
		header("Cache-Control: no-cache, must-revalidate" ); 
		header("Pragma: no-cache" );
		header("Content-type: text/xml");
		
		echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
		echo '<response>' . "\n";
		if(!$exist){
			echo "\t" . "<text></text>". "\n";
			echo "\t" . "<html_exists><![CDATA[<div class='owner_comment'><span class='comment_user_name'><strong>".JText::_('COM_TESTIMONIALS_OWNER_REPLY')."</strong></span><span class='dlt-comment'><img src='".JURI::root(true)."/components/com_testimonials/assets/images/stop.png' alt='".JText::_('COM_TESTIMONIALS_COMMENT_DELETE')."' title='".JText::_('COM_TESTIMONIALS_COMMENT_DELETE')."' onclick='javascript:deleteComment(".$id.");' /></span><br><span class='comment_text'>".$text."</span></div>]]></html_exists>"."\n";
		} else {
			echo "\t" . "<text><![CDATA[".$text."]]></text>". "\n";
			echo "\t" . "<html_exists></html_exists>"."\n";
		}
		echo '</response>' . "\n";
		die;
	}
	
	function deletecomment()
    {
		$id = JFactory::getApplication()->input->getInt('id', 0);
		$db = JFactory::getDBO();
			
		$sql = "UPDATE #__tm_testimonials SET comment='' WHERE id='".$id."'";
		$db->setQuery($sql);
		$db->query();
		
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
		header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
		header("Cache-Control: no-cache, must-revalidate" ); 
		header("Pragma: no-cache" );
		header("Content-type: text/html");
		
		echo "true";
		die;
	}
}