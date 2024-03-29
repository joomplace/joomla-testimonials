<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

/**
 * Testimonials Table class
 */
class TestimonialsTableTestimonials extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__tm_testimonials', 'id', $db);
    }
        
    public function store($updateNulls = false)
    {
        if(!$this->catid){
            $tag = JFactory::getLanguage()->getTag();
            $options = JHtml::_('category.options','com_testimonials',$config = array('filter.published' => array(1), 'filter.language' => array('*',$tag),'filter.access' =>array(1)));
            $this->catid = $options['0']->value;
        }

        if (JFactory::getApplication()->input->getInt('remove_photo'))
        {
            if (JFactory::getUser()->authorise('core.edit', 'com_testimonials'))
            {
                if ($this->id && $this->photo)
                            {
                                $path = JPATH_SITE.DIRECTORY_SEPARATOR.$this->photo;

                                if (is_file($path) && file_exists($path))
                                {
                                    unlink($path);
                                    $this->photo='';
                                }

                            }
            }
        }
        jimport( 'joomla.filesystem.file' );
        $files_jform = JFactory::getApplication()->input->files->get('jform');
        if (!empty($files_jform['name']))
        {
            //  5MB maximum file size
            $MAXIMUM_FILESIZE = 5 * 1024 * 1024;
            //  Valid file extensions (images)
            $rEFileTypes =  "/^\.(jpg|jpeg|gif|png|bmp|xcf|odg){1}$/i";
            $dir_base = JPATH_BASE."/images/testimonials/";

            $isFile = is_uploaded_file($files_jform['tmp_name']['photofile']);
            if ($isFile)    //  do we have a file?
               {$safe_filename = substr(md5(uniqid(rand(), true)),-5)."_".preg_replace(
                                 array("/\s+/", "/[^-\.\w]+/"),
                                 array("_", ""),
                                 trim($files_jform['name']['photofile']));
                if ($files_jform['size']['photofile'] <= $MAXIMUM_FILESIZE &&
                    preg_match($rEFileTypes, strrchr($safe_filename, '.')))
                  {$isMove = JFile::upload(
                             $files_jform['tmp_name']['photofile'],
                             $dir_base.$safe_filename);}
                  }
               if ($isMove)
                   {
                        if ($this->id && $this->photo)
                        {
                            $path = JPATH_SITE.DIRECTORY_SEPARATOR.$this->photo;

                            if (is_file($path) && file_exists($path))
                            {
                                unlink($path);
                            }
                        }
                        $this->photo = 'images/testimonials/'.$safe_filename;
                   }
        }
        $post_jform = JFactory::getApplication()->input->post->getAssoc('jform');
        if (empty($post_jform['user_id_t']) && empty($post_jform['id'])) {
            $user = JFactory::getUser();
            $this->user_id_t = $user->id;
        }
        $this->ip_addr = $_SERVER['REMOTE_ADDR'];

        $res = parent::store($updateNulls);
        $res_names = array();
        $jform = JFactory::getApplication()->input->get('jform', 'array', 'ARRAY');

        $remove_image = JFactory::getApplication()->input->get('remove_image', '', 'STRING');
        $remove_image = trim($remove_image, '|');
        $remove_image = explode('|', $remove_image);


        if(!empty($jform['exist_images'])){
            $images = explode("|", $jform['exist_images']);
            jimport( 'joomla.filesystem.file' );
        foreach ($images as $key => $image) {
            if (is_array($remove_image) && in_array($image, $remove_image)) {
                if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) JFile::delete(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image);
                if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . "th_" . $image)) JFile::delete(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . "th_" . $image);
                if(JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . "thumb_" . $image)) JFile::delete(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . "thumb_" . $image);
                unset($images[$key]);
            }
            if(!empty($images[$key]) && !JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) unset($images[$key]);
        }
        $tmp = $images;

        if (count($images) == 1 && strlen(trim(array_shift($tmp))) == 0) {
            unset($images);
        }
        if (!empty($images) && !empty($res_names)) {
            $res_names = array_merge($res_names, $images);
        }elseif (empty($res_names) && !empty($images)) {
            $res_names = $images;
        }
        }

        //if (count($res_names) > 0) {
            $db = JFactory::getDBO();
            if (empty($this->id)) {
                $sql = "SELECT id FROM #__tm_testimonials ORDER BY id DESC LIMIT 1";
                $db->setQuery($sql);
                $t_id = $db->loadResult();
            }else {
                $t_id = $this->id;
            }

            $sql = "UPDATE #__tm_testimonials SET images='".implode('|', $res_names)."' WHERE id='".$this->id."'";
            $db->setQuery($sql);
            $db->execute();
        //}
        if ($this->id && $res)
        {
            if (isset($post_jform['tags']))
            {	$tags = $post_jform['tags'];}
            else
            {
                $tags=array(1);
            }
                if (is_array($tags))
                {
                    if(sizeof($tags)>0)
                    {
                        $conformityTable = JTable::getInstance('Conformity', 'TestimonialsTable');
                        $issettags = $conformityTable->loadTagsIds($this->id);
                        if(empty($issettags)) $issettags = array();
                        foreach ( $tags as $tagid )
                        {
                            if($tagid > 0){
                            if (in_array($tagid, $issettags))
                            {
                                unset($issettags[array_search($tagid, $issettags)]);
                            }
                            else
                            {
                                $conformityTable->store($conformityTable->bind(array('id_ti'=>$this->id,'id_tag'=>$tagid)));
                            }
                            }
                        }
                        if (sizeof($issettags)>0)
                            {
                                foreach ( $issettags as $tagid )
                                {
                                    $conformityTable->deleteWithTag($this->id,$tagid);
                                }
                            }
                        }
                }

            $flag = 0;
            $customFieldsTable = JTable::getInstance('Customfields', 'TestimonialsTable');
            $custom = array(JFactory::getApplication()->input->get('customs', 'array', 'ARRAY'));
            $custom_link = array(JFactory::getApplication()->input->get('customs_link', 'array', 'ARRAY'));
            $custom_name = array(JFactory::getApplication()->input->get('customs_name', 'array', 'ARRAY'));
            $url_array = array();

            foreach($custom_link[0] as $key=>$val){
                $url_array[$key] = $val.'|'.$custom_name[0][$key];
                if ($url_array[$key] == '|'){
                    unset($url_array[$key]);
                }
            }

            if (is_array($custom[0]) && is_array($url_array)) {
                $custom[0] = $custom[0] + $url_array;
            }

            if (empty($custom[0]) && $this->id)
            {
                $flag = 1;
                $db = JFactory::getDbo();
                $query = "SELECT * FROM `#__tm_testimonials_items_fields` WHERE `item_id`=".$this->id;
                $db->setQuery($query);
                $properties = $db->loadObjectList();
                $fields = array();
                if($properties){
                    for($i = 0; $i<count($properties); $i++){
                        $fields[$i]["item_id"] = $properties[$i]->item_id;
                        $fields[$i]["field_id"] = $properties[$i]->field_id;
                        $fields[$i]["value"] = $properties[$i]->value;
                    }
                }
                $custom = $fields;
            }

            $customFieldsTable->storefields = array($this->id,$custom,$flag);
            $customFieldsTable->store();

            // Autoapprove
            $params = TestimonialsFEHelper::getParams();
            if (!JFactory::getUser()->authorise('core.publish', 'com_testimonials'))
            {
                $this->publish($this->id, $state = 0);
            }
            if ($params->get('autoapprove')==0 && !JFactory::getUser()->authorise('core.admin', 'com_testimonials'))
            {
                $this->approve($this->id, $state = 0);
            }

            //Send notifications
            $emails = TestimonialsFEHelper::getNotifyUserEmails();
            if (sizeof($emails))
            {
                $config	= JFactory::getConfig();
                $fromname	= $config->get('fromname');
                $mailfrom	= $config->get('mailfrom');
                $sitename	= $config->get('sitename');

                $id = $this->id;
                $subject = stripslashes(JText::_($id?'COM_TESTIMONIALS_MAIL_EDIT_SUBJECT':'COM_TESTIMONIALS_MAIL_NEW_SUBJECT'));
                $message = nl2br(sprintf(stripslashes($id?JText::_('COM_TESTIMONIALS_MAIL_EDIT_MESSAGE'):JText::_('COM_TESTIMONIALS_MAIL_NEW_MESSAGE')), JURI::base(), $this->t_caption, $this->t_author, $this->testimonial));

                if (!$id) // ONLY NEW FE TESTIMONIALS
                {
                    foreach ( $emails as $email )
                    {
                        $mailer = JFactory::getMailer();
                        $mailer->setSender(array($mailfrom,$fromname));
                        $mailer->addRecipient($email);
                        $mailer->setSubject($subject);
                        $mailer->setBody(JHtml::_('content.prepare',str_replace('{testimonial}','{testimonials email|id:'.$id.'|1}',$message)));
                        $send = $mailer->Send();
                        if ( $send !== true ) {
                            echo 'Error sending email: ' . $send->__toString();
                        }
                    }
                }
            }
        }
        $currentTask = JFactory::getApplication()->input->get('task', '');

        if($currentTask != 'saveOrderAjax') $this->reorder();

        return $res;
    }
		
    public function delete($pk = null)
    {
        if (isset($this->photo))
        {
            $tmp_name = strrchr($this->photo, "/");
            $photo_name = substr($tmp_name, 1);
            $th_folder = JPATH_SITE.DIRECTORY_SEPARATOR."images/testimonials".DIRECTORY_SEPARATOR."th_".$photo_name;
            $im_folder = JPATH_SITE.DIRECTORY_SEPARATOR.$this->photo;
            if(file_exists($im_folder) && is_file($im_folder)) unlink($im_folder);
            if(file_exists($th_folder) && is_file($th_folder)) unlink($th_folder);
        }
        $conformityTable = JTable::getInstance('Conformity', 'TestimonialsTable');
        $conformityTable->delete($pk);

        $customFieldsTable = JTable::getInstance('Customfields', 'TestimonialsTable');
        $customFieldsTable->delete($pk);
        return parent::delete($pk);
    }

    function approve($cid=null, $approve=1, $user_id=0)
    {
        $cid = ArrayHelper::toInteger($cid);
        $user_id = (int) $user_id;
        $approve = (int) $approve;
        $k = $this->_tbl_key;

        if (count( $cid ) < 1)
        {
            if ($this->$k) {
                $cid = array( $this->$k );
            } else {
                $this->setError("No items selected.");
                return false;
            }
        }

        $cids = $k . '=' . implode( ' OR ' . $k . '=', $cid );

        $query = 'UPDATE '. $this->_tbl
            . ' SET is_approved = ' . (int) $approve
            . ' WHERE ('.$cids.')'
        ;

        $checkin = in_array( 'checked_out', array_keys($this->getProperties()) );
        if ($checkin)
        {
            $query .= ' AND (checked_out = 0 OR checked_out = '.(int) $user_id.')';
        }

        $this->_db->setQuery( $query );
        if (!$this->_db->query())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        if (count( $cid ) == 1 && $checkin)
        {
            if ($this->_db->getAffectedRows() == 1) {
                $this->checkin( $cid[0] );
                if ($this->$k == $cid[0]) {
                    $this->is_approved = $approve;
                }
            }
        }
        $this->setError('');
        return true;
    }
		
}