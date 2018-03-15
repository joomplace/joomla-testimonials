<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

class TemplateHelper
{
    protected $document;
    protected $template;
    protected $config;
    protected $width;
    protected $live_site_url;
    protected $template_name;
    protected $nophoto;
    
    function __construct($template='default'){
	$this->document = JFactory::getDocument();
	$this->config = JComponentHelper::getParams('com_testimonials');
	$this->width  = 300;
	$this->live_site_url=JURI::base();
	$this->config->set('addingbyuser', JFactory::getUser()->authorise('core.create', 'com_testimonials'));
	$this->nophoto = false;

	$this->addJQuery();
    }

    static function addJQuery(){
	JHtml::script(JURI::base() . 'components/com_testimonials/assets/jplace.jquery.js');
    }


	function getUserAvatar($uId)
	{
	    $settings = JComponentHelper::getParams ("com_testimonials");
	    $avatar = '';
	    if($uId>0 && ($settings->get('use_cb') == 1 || $settings->get('use_jsoc') == 1 )){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		if($this->config->get('use_cb') == 1){
		    $query->select('CONCAT("images/comprofiler/",compr.avatar) as `avatar`');
		    $query->from('`#__comprofiler` AS `compr`');
		    $query->where('compr.user_id = '.$uId);
		}
		if($this->config->get('use_jsoc') == 1){
		    $query->select('jsoc.thumb as `avatar`');
		    $query->from('`#__community_users` AS `jsoc`');
		    $query->where('jsoc.userid = '.$uId);
		}
		$db->setQuery($query);
		$data = $db->loadObject();
		if(!empty($data->avatar) && file_exists(JPATH_BASE . DIRECTORY_SEPARATOR . $data->avatar)) $avatar = $data->avatar;
	    }
	    return $avatar;

	}

    function showForm($form = null, $item=null, $custom_fields=null, $tags=null) {
	$user		= JFactory::getUser();
	if ($this->config->get('addingbyuser')){
	    $settings = JComponentHelper::getParams ("com_testimonials");
	    $error = JFactory::getApplication()->input->getBool('error', false);
	    if($error){
		$form_data = JFactory::getApplication()->getUserState('com_testimonials.edit.form.data', array());
		$item->photo = $form_data['photo'];
		$item->images = $form_data['exist_images'];
		if(!empty($form_data['tags'])) $tags->selected = $form_data['tags'];
		if(!empty($form_data['remove_image'])){
		    $remove_images = explode('|', $form_data['remove_image']);
		    foreach($remove_images as $remove_image){
			$item->images = str_replace($remove_image, '', $item->images);
		    }
		    $item->images = str_replace('||', '|', $item->images);
		}
	    }else $form_data['remove_image'] = '';
	    if (($settings->get('use_cb') == 1 || $settings->get('use_jsoc') == 1 ) && ($user->id || $item->id) && empty($item->avatar)){
		if($item->id) $item->avatar = $this->getUserAvatar($item->user_id_t);
		else $item->avatar = $this->getUserAvatar($user->id);
	    }else $item->avatar = '';
	    
	    ?>
<script type='text/javascript'>
	(function($) {
     $(document).ready(function () {
     if ($("[rel=tooltip]").length) {
	$("[rel=tooltip]").tooltip();
     }
     $("[rel=rating]").click(function (){
	var block_name = $(this).attr('name');
	$(this).parent().children().removeClass('hover');
	$('#customs_'+$(this).attr('field_id')).val($(this).attr('rating'));
	$(this).toggleClass('hover');
     });
     deleteImage = function(el){
	$($(el).parent()).remove();
	$('#remove_image').val($('#remove_image').val()+'|'+jQuery(el).attr('image'));
     };
     deleteAvatar = function(el){
	$('#avatarUploadButton').css('display','block');
	$('#avatarUploadImage').css('display','none');
	$('#jform_photo').val(''); 
     };
     <?php if($settings->get('show_addimage') == 1) : ?>
     $('#imageUpload').fileupload({
	sequentialUploads: false,
        dataType: 'json',
	formData: {task: 'new_image', id: '<?php echo $item->id?>'},
	xhrFields: {
        withCredentials: true
    },
	submit: function (e, data) {
		$('#imageProgressContainer').css('display','block');
	    },
        done: function (e, data) {
	    if(data.result.status == 'ok'){
		$('#uploadedImages').append('<a href="javascript:void(0)" class="testim-img"><img src="<?php echo JURI::root().'/images/testimonials/';?>'+data.result.image+'" alt="'+data.result.image+'"/><span class="testim-del-img" image="'+data.result.image+'" onclick="deleteImage(this);"></span></a>');
		$('#uploadedImages a:last-child > img').load(function(){
		    if($('#uploadedImages a:last-child > img').height() < $('#uploadedImages a:last-child > img').width()){
			$('#uploadedImages a:last-child > img').css('height','100%');
			$('#uploadedImages a:last-child > img').css('width','auto');
		    }
		});
		$('#jform_exist_images').val($('#jform_exist_images').val()+'|'+data.result.image);
		$('#imageProgressContainer').css('display','none');
	    }
	    if(data.result.status == 'bad' && data.result.message != ''){
		alert(data.result.message);
		$('#imageProgressContainer').css('display','none');
	    }
        } 
    });
    <?php endif; ?>
    <?php if($settings->get('allow_photo') == 1) : ?>
     $('#avatarUpload').fileupload({
	sequentialUploads: false,
        dataType: 'json',
	formData: {task: 'new_avatar', id: '<?php echo $item->id?>' },
	submit: function (e, data) {
		$('#avatarUploadButton').css('display','none');
		$('#avatarProgressContainer').css('display','block');
	},
        done: function (e, data) { 
	    if(data.result.status == 'ok'){
		$('#avatarUploadImage').html('<img src="<?php echo JURI::root().'/images/testimonials/';?>'+data.result.image+'" alt="'+data.result.image+'"/><span class="testim-del-img" image="'+data.result.image+'" onclick="deleteAvatar(this);"></span>');
		$('#avatarProgressContainer').css('display','none');
		$('#avatarUploadImage').css('display','block');
		$('#jform_photo').val('images/testimonials/'+data.result.image);
		$('#avatarUploadImage > img').load(function(){
		    if($('#avatarUploadImage > img').height() < $('#avatarUploadImage > img').width()){
			$('#avatarUploadImage > img').css('height','100%');
			$('#avatarUploadImage > img').css('width','auto');
		    }
		});
	    }
	    if(data.result.status == 'bad'){
		if(data.result.message != '') alert(data.result.message);
		$('#avatarProgressContainer').css('display','none');
		$('#avatarUploadButton').css('display','block');
	    }
        } 
    });
    <?php endif; ?>
   });
   })(jplace.jQuery);
  </script>
	<input type="hidden" name="jform[date_added]" value="<?php echo date('Y-m-d H:i:s', time());?>" />
	<?php if($item->id) : ?>
	    <input type="hidden" name="jform[user_id_t]" value="<?php echo($item->user_id_t);?>" />
	<?php endif; ?>
	    <fieldset class="testim-required">
                    <hr class="testim-line testim-top-line"/>
		    <?php if ($settings->get('show_caption')) : ?>
		    <div class="testim-field-group control-group">
                    <label class="testim-label testim-required control-label" for="jform_t_caption" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('t_caption', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('t_caption', 'label')); ?>:</label>
						<div class="controls">
							<?php echo $form->getInput('t_caption'); ?>
						</div>
                    </div>
		    <?php else : ?>
			<input type="hidden" name="jform[t_caption]" value="<?php echo((!empty($item->t_caption) ? $item->t_caption : '_')); ?>" />
		    <?php endif; ?>
                    <div class="testim-field-group control-group">
                    <label class="testim-label control-label" for="jform_testimonial" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('testimonial', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('testimonial', 'label')); ?>:</label>
                        <div class="testim-texeditor-container controls">
			    <div id="jform_testimonial_toolbar" style="display: none;" class="texteditor-toolbar">
			      <div class="btn-group">
				  <a class="btn" data-wysihtml5-command="bold" title="CTRL+B"><i class="icon-bold"></i></a>
				  <a class="btn" data-wysihtml5-command="italic" title="CTRL+I"><i class="icon-italic"></i></a>
				  <a class="btn" data-wysihtml5-command="underline" title="CTRL+U"><i class="icon-underline"></i></a>
				  <a class="btn" data-wysihtml5-command="createLink"><i class="icon-link"></i></a>
			      </div>
			      <div class="btn-group">
				  <a class="btn" data-wysihtml5-command="insertOrderedList"><i class="icon-list-ol"></i></a>
				  <a class="btn" data-wysihtml5-command="insertUnorderedList"><i class="icon-list-ul"></i></a>
			      </div>
			      <div data-wysihtml5-dialog="createLink" style="display: none; padding-top: 5px">
				  <div class="input-append">
				      <input type="text" class="input-large" data-wysihtml5-dialog-field="href" value="http://">
				      <a class="btn" data-wysihtml5-dialog-action="save"><i class="icon-ok"></i> <?php echo JText::_('COM_TESTIMONIALS_SAVE'); ?></a>
				      <a class="btn" data-wysihtml5-dialog-action="cancel"><i class="icon-remove"></i> <?php echo JText::_('COM_TESTIMONIALS_CANCEL'); ?></a>
				  </div>
			      </div>
			    </div>
			    <?php echo $form->getInput('testimonial'); ?>
			</div>
                    </div>               
		
		    <?php
			$unrequiredFields = 0;
			foreach ($custom_fields as $i => $custom_field) {
			    if($custom_field->required) {
				echo($this->showCustomField($custom_field, $i));
			    }else $unrequiredFields++;
			}
		    ?>
		    
		    <?php if($settings->get('allow_photo')) : ?>
			<!--Block .testim-field-group is for avatar.-->
			<div class="testim-field-group control-group testim-images-container">
			    <div class="testim-label control-label">&nbsp;</div>
                            <div class="testim-add-image clearfix controls" id="avatarUploadContainer">
				    <?php if((!empty($item->photo) && file_exists(JPATH_SITE . DIRECTORY_SEPARATOR  . $item->photo)) || !empty($item->avatar)) : ?>
				    <?php $image = (!empty($item->photo) ? $item->photo : $item->avatar); ?>
					<div class="imageProgressContainer" style="display: none;"><div id="imageProgress" class="imageProgress"></div></div>
					<a href="javascript:void(0)" class="testim-img" id="avatarUploadImage"><img src="<?php echo JURI::root().'/'.$image;?>" alt=" "/><span class="testim-del-img" image="<?php echo $image?>" onclick="deleteAvatar(this);"></span></a>
					<div class="testim-add-img2" id="avatarUploadButton" style="display:none"><span class="testim-add-img-label"><?php echo JText::_('COM_TESTIMONIALS_ADD_AVATAR'); ?></span><input type="file" name="avatar" id="avatarUpload" data-url="<?php echo JRoute::_('index.php?option=com_testimonials&task=new_avatar'); ?>" class="file-input-button" /></div>
				    <?php else : ?>
					<div id="avatarProgressContainer"  class="imageProgressContainer" style="display: none;"><div id="imageProgress" class="imageProgress"></div></div>
					<a href="javascript:void(0)" class="testim-img" id="avatarUploadImage" style="display:none"></a>
					<div class="testim-add-img2" id="avatarUploadButton" onclick="document.getElementById('avatarUpload').click(); "><span class="testim-add-img-label"><?php echo JText::_('COM_TESTIMONIALS_ADD_AVATAR'); ?></span><input type="file" onclick="event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);" name="avatar" id="avatarUpload" data-url="<?php echo JRoute::_('index.php?option=com_testimonials&task=new_avatar'); ?>" class="file-input-button" /></div>
				    <?php endif; ?>
			    </div>

			    <input type="hidden" name="jform[photo]" id="jform_photo" value="<?php echo (!empty($item->photo) ? $item->photo : '');?>" />

			</div> 
		    <?php endif; ?>
			
		    <?php if ($settings->get('show_authorname')) : ?>
		    <div class="testim-field-group control-group">
                    <label class="testim-label control-label testim-required" for="jform_t_author" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('t_author', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('t_author', 'label')); ?>:</label>
			    <div class="controls">
				<?php echo $form->getInput('t_author'); ?>
                    </div>
                    </div>
		    <?php endif; ?>
                    
                    </fieldset>	
				<?php if($settings->get('show_testimmore')){ ?>
                    <hr class="testim-line"/>
		    <?php if($unrequiredFields>0 || $settings->get('allow_photo') || $settings->get('show_authordesc')) : ?>
                    <div class="testim-field-group control-group testim-field-more">
                        <div class="testim-label control-label"></div>
                        <div class="testim-add-image controls">                
                            <i class="icon-caret-right" id="testim-more-label"></i><a href="" class="testim-more"><?php echo JText::_('COM_TESTIMONIALS_TELL_MORE');?></a>
                        </div>
                    </div>  
                    <fieldset>
                        <div class="testim-notrequired testim-hide">
			<?php if ($settings->get('show_authordesc')) : ?>
			<div class="testim-field-group control-group">
			<label class="testim-label control-label testim-required" for="jform_author_description" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('author_description', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('author_description', 'label')); ?>:</label>
			<div class="testim-texeditor-container controls">
			    <div id="jform_author_description_toolbar" style="display: none;" class="texteditor-toolbar">
			      <div class="btn-group">
				  <a class="btn" data-wysihtml5-command="bold" title="CTRL+B"><i class="icon-bold"></i></a>
				  <a class="btn" data-wysihtml5-command="italic" title="CTRL+I"><i class="icon-italic"></i></a>
				  <a class="btn" data-wysihtml5-command="underline" title="CTRL+U"><i class="icon-underline"></i></a>
				  <a class="btn" data-wysihtml5-command="createLink"><i class="icon-link"></i></a>
			      </div>
			      <div class="btn-group">
				  <a class="btn" data-wysihtml5-command="insertOrderedList"><i class="icon-list-ol"></i></a>
				  <a class="btn" data-wysihtml5-command="insertUnorderedList"><i class="icon-list-ul"></i></a>
			      </div>
			      <div data-wysihtml5-dialog="createLink" style="display: none; padding-top: 5px">
				  <div class="input-append">
				      <input type="text" class="input-large" data-wysihtml5-dialog-field="href" value="http://">
				      <a class="btn" data-wysihtml5-dialog-action="save"><i class="icon-ok"></i> <?php echo JText::_('COM_TESTIMONIALS_SAVE'); ?></a>
				      <a class="btn" data-wysihtml5-dialog-action="cancel"><i class="icon-remove"></i> <?php echo JText::_('COM_TESTIMONIALS_CANCEL'); ?></a>
				  </div>
			      </div>
			    </div>
			    <?php echo $form->getInput('author_description'); ?>
			</div>
			</div>
			<?php endif; ?>
			<?php if($settings->get('show_addimage')) : ?>
			<!--Block .testim-field-group is for images. When images are added into .for-images .testim-field-group is display:block-->
			<div class="testim-field-group control-group testim-images-container">
			    <div class="testim-label control-label">&nbsp;</div>
			    <div class="testim-add-image controls clearfix">
				<div id="imageProgressContainer" class="imageProgressContainer"><div id="imageProgress" class="imageProgress"></div></div>
				<span id="uploadedImages" ></span>
				<?php $this->showImages($item->images); ?>
                                <div class="testim-add-img2" onclick="document.getElementById('imageUpload').click();"><span class="testim-add-img-label"><?php echo JText::_('COM_TESTIMONIALS_ADD_IMAGE'); ?></span><input type="file" name="image" onclick="event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);" id="imageUpload" data-url="<?php echo JRoute::_('index.php?option=com_testimonials&task=new_image'); ?>" class="file-input-button"></div>
				<input type="hidden" name="jform[exist_images]" id="jform_exist_images" value="<?php echo $item->images;?>" />
				<input type="hidden" name="remove_image" id="remove_image" value="<?php echo $form_data['remove_image'];?>" />
			    </div>
			</div> 
			<?php endif; ?>
                            <?php
				foreach ($custom_fields as $i => $custom_field) {
				    if($custom_field->required == 0) {
					echo($this->showCustomField($custom_field, $i));
				    }
				}
			    ?>
                        </div>
		    </fieldset>
		    <?php endif; ?>
		<?php } ?>
		<?php if($settings->get('show_tags') && !empty($tags->tags)) : ?>
                <fieldset>                    
                    <hr class="testim-line testim-bottom-line"/>
                    <div class="testim-field-group control-group testim-tags-container">
						<div class="controls">
							<ul class="testim-tags">
					<?php foreach($tags->tags as $tag) : ?>
								<li>
									<span class="label<?php echo (in_array($tag->value, $tags->selected) ? ' label-success' : '')?>" tag="<?php echo $tag->value?>"><?php echo $tag->text?></span>
								</li>
					<?php endforeach; ?>
							</ul>
						</div>
                    </div>
		    <select name="jform[tags][]" id="jform_tags" multiple="multiple" style="display: none;">
			<?php foreach($tags->tags as $tag) : ?>
			<option value="<?php echo $tag->value?>"<?php echo (in_array($tag->value, $tags->selected) ? ' selected="selected"' : '')?>><?php echo $tag->text?></option>
			<?php endforeach; ?>
		    </select>
		   <hr class="testim-line testim-bottom-line"/>
                </fieldset>
		<?php endif; ?>
		    <?php
		    if(!JFactory::getUser()->authorise('core.edit', 'com_testimonials')){
			if ($this->config->get('show_recaptcha')) {
			require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'recaptchalib.php');
			?>
		    <fieldset>
			<hr class="testim-line testim-bottom-line"/>
		    <div class="testim-field-group  control-group testim-images-container">
			<div class="testim-label control-label">&nbsp;</div>
			<div class="testim-add-image controls clearfix">
			<?php echo recaptcha_get_html($this->config->get('recaptcha_publickey'));?>
			</div>
		    </div>
		    </fieldset>  
		    <?php
			}elseif ($this->config->get('show_captcha')){
		    ?>
		    <fieldset>  
			<hr class="testim-line testim-bottom-line"/>
		    <div class="testim-field-group control-group">
                    <div class="testim-label control-label testim-images-container"><img src="<?php echo JURI::base();?>index.php?option=com_testimonials&task=captcha.show" alt="<?php echo JText::_('COM_TESTIMONIALS_RELOAD_SECURITY_CODE');?>" title="<?php echo JText::_('COM_TESTIMONIALS_RELOAD_SECURITY_CODE');?>" onclick="javascript: refresh_captcha( captcha_params );" style="cursor:pointer;" id="captcha_code" /></div>
			    
			<div class="controls">
			<input type="text" name="captcha_value" id="captcha_value" value="" class="inputbox" style="margin-bottom: 10px;" autocomplete="off" />
			
                    </div>
                    </div>
		    </fieldset>  
		    <?php
			}
		    }
		    ?>
	    <?php
	} 
    }
    
    protected function showCustomField($custom_field, $id){
	switch ($custom_field->type){
	    case 'url':
		$url = array();
		$url = explode('|', $custom_field->value);
		?>
		    <div class="testim-field-group control-group">
                    <label class="testim-label control-label" for="customs_link_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name." ";?><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_LINK');?>:</label>
                        <div class="controls">
							<input type="text" id="customs_link_<?php echo $custom_field->id?>" name="customs_link[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($url[0])?$url[0]:'');?>" />
						</div>
                    </div>
		    <div class="testim-field-group control-group">
                    <label class="testim-label control-label" for="customs_name_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name." ";?><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_NAME');?>:</label>
                        <div class="controls">
							<input type="text" id="customs_name_<?php echo $custom_field->id?>" name="customs_name[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($url[1])?$url[1]:'');?>" />
						</div>
                    </div>
		<?php
		break;
	    case 'textarea':
		?>
		<div class="testim-field-group control-group">
		<label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
		    <div class="controls">
				<textarea cols="30" rows="10" id="customs_<?php echo $custom_field->id?>" name="customs[<?php echo $custom_field->id;?>]"><?php echo htmlspecialchars(isset($custom_field->value)?$custom_field->value:'');?></textarea>
			</div>
		</div>
		<?php
		break;
	    case 'rating':
		?>
		<div class="testim-field-group control-group">
		<label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
		<input type="hidden" name="customs[<?php echo $custom_field->id?>]" id="customs_<?php echo $custom_field->id?>" value="<?php echo (isset($custom_field->value) ? $custom_field->value : '')?>" />
		    <div class="rating controls">
		    <?php for($a=5;$a>0;$a--) : ?>
			<span rating="<?php echo $a;?>" rel="rating" field_id="<?php echo $custom_field->id?>" <?php echo (isset($custom_field->value) && $custom_field->value == $a ? 'class="hover"' : '')?>>&#9734;</span>
		    <?php endfor; ?>
		    </div>
		</div>
		<?php
		break;
	    default:
		?>
		<div class="testim-field-group control-group">
		<label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
		    <div class="controls">
				<input type="text" id="customs_<?php echo $custom_field->id?>" name="customs[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($custom_field->value)?$custom_field->value:'');?>" />
			</div>
		</div>
		<?php
		break;
	}
    }

    protected function showImages($images){
	$images = trim($images, '|');
	$images = explode('|', $images);
	foreach($images as $image){
	    if(!empty($image) && file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)){
		?>
		    <a href="javascript:void(0)" class="testim-img"><img src="<?php echo JURI::root().'/images/testimonials/'.$image;?>" alt="<?php echo $image?>"/><span class="testim-del-img" image="<?php echo $image?>" onclick="deleteImage(this);"></span></a>
		<?php
	    }
	}
    }
	
}