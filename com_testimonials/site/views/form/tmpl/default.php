<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

$settings = $params = $this->params;
$helper = $this->helper;
$custom_fields = $this->custom_fields;
$testimonial_id= $this->item->id;
$script = array();
/*
 * wysihtml5 add
 */
if($params->get('use_editor') == 1){
    $script[] = "
    (function($) {
         $(document).ready(function () {
         var editor = new wysihtml5.Editor(\"jform_testimonial\", {
            toolbar:      \"jform_testimonial_toolbar\",
            parserRules:  wysihtml5ParserRules
         });
         ";
    if($params->get('show_authordesc') == 1 && $params->get('show_testimmore') == 1){
        $script[] = "
         var editor2 = new wysihtml5.Editor(\"jform_author_description\", {
            toolbar:      \"jform_author_description_toolbar\",
            parserRules:  wysihtml5ParserRules
         });
         ";
    }
$script[] = "
         });
     })(jQuery);
         ";
}
$script[] = "
        jQuery(document).ready(function($){
            $('.testim-more').on('click', function(){
                $(this).parent().find('[class^=icon-caret]').toggleClass('icon-caret-right').toggleClass('icon-caret-down');
                $(this).parent().parent().find('fieldset').toggleClass('slided-in');
            });
        });
        ";
/*
 * form valitation
 */
$script[] ="
(function($) {
		validateFields = function(msg) {
			var error_message = [];
			var error = false;
			var form = document.adminForm;
        ";
if($params->get('show_caption')){
    $script[]="
                $('#jform_t_caption').removeClass('invalid');
                $('[for=jform_t_caption]').removeClass('invalid');
                if (document.getElementById('jform_t_caption').value == '') {
                        $('#jform_t_caption').addClass('invalid');
                        $('[for=jform_t_caption]').addClass('invalid');
                        error_message.push('".JText::_('COM_TESTIMONIALS_EDIT_CAPTION_ERROR')."');
                        error = true;
                }
            ";
}
if($params->get('show_authorname')){
    $script[]="
                $('#jform_t_author').removeClass('invalid');
                $('[for=jform_t_author]').removeClass('invalid');
                if (document.getElementById('jform_t_author').value == '') {
                        $('#jform_t_author').addClass('invalid');
                        $('[for=jform_t_author]').addClass('invalid');
                        error_message.push('".JText::_('COM_TESTIMONIALS_EDIT_AUTHOR_NAME_ERROR')."');
                        error = true;
                }
            ";
}
$script[]="
            $('#jform_testimonial').removeClass('invalid');
            $('[for=jform_testimonial]').removeClass('invalid');
            if (document.getElementById('jform_testimonial').value == '') {
                    $('#jform_testimonial').addClass('invalid');
                    $('[for=jform_testimonial]').addClass('invalid');
                    error_message.push('".JText::_('COM_TESTIMONIALS_EDIT_TESTIMONIAL_ERROR')."');
                    error = true;
            }
        ";
foreach($custom_fields as $field){
    if($field->required){
        if ($field->type == 'url') {
            $script[] ="
                        $('#customs_link_".$field->id."').removeClass('invalid');
                        $('[for=customs_link_".$field->id."]').removeClass('invalid');
                        $('#customs_name_".$field->id."').removeClass('invalid');
                        $('[for=customs_name_".$field->id."]').removeClass('invalid');
                        if (document.getElementById('customs_link_".$field->id."').value == '') {
                            $('#customs_link_".$field->id."').addClass('invalid');
                            $('[for=customs_link_".$field->id."]').addClass('invalid');
                            error_message.push('".JText::sprintf('COM_TESTIMONIALS_EDIT_URL_LINK_ERROR_EMPTY', $field->name)."');
                            error = true;
                        }
                        if (document.getElementById('customs_name_".$field->id."').value == ''){
                            $('#customs_name_".$field->id."').addClass('invalid');
                            $('[for=customs_name_".$field->id."]').addClass('invalid');
                            error_message.push('".JText::sprintf('COM_TESTIMONIALS_EDIT_URL_NAME_ERROR_EMPTY', $field->name)."');
                            error = true;
                        }
                    ";
        }else{
            $script[] ="
                        $('#customs_".$field->id."').removeClass('invalid');
                        $('[for=customs_".$field->id."]').removeClass('invalid');
                        if (document.getElementById('customs_".$field->id."').value == '') {
                            $('#customs_".$field->id."').addClass('invalid');
                            $('[for=customs_".$field->id."]').addClass('invalid');
                            error_message.push('".JText::sprintf('COM_TESTIMONIALS_EDIT_CUSTOM_ERROR_EMPTY', $field->name)."');
                            error = true;
                        }
                    ";
        }
    }
    /*
     * not required fields block
     */
    if($settings->get('show_testimmore')){
        if ($field->type == 'textemail'){
            $script[]="
                if (document.getElementById('customs_".$field->id."').value !== ''){
                    $('#customs_".$field->id."').removeClass('invalid');
                    $('[for=customs_".$field->id."]').removeClass('invalid');
                    var email = document.getElementById('customs_".$field->id."').value;
                    if (!email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)){
                        $('#customs_".$field->id."').addClass('invalid');
                        $('[for=customs_".$field->id."]').addClass('invalid');
                        error_message.push('".JText::sprintf('COM_TESTIMONIALS_EDIT_CUSTOM_ERROR_INCORRECT', $field->name)."');
                        error = true;
                    }
                }
            ";
        }
        if ($field->type == 'url'){
            $script[]="
                if (document.getElementById('customs_link_".$field->id."').value !== ''){
                    $('#customs_link_".$field->id."').removeClass('invalid');
                    $('[for=customs_link_".$field->id."]').removeClass('invalid');
                    var url = document.getElementById('customs_link_".$field->id."').value;
                    if (!url.match(/(ftp:\/\/|http:\/\/|https:\/\/|www)(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/)){
                        $('#customs_link_".$field->id."').addClass('invalid');
                        $('[for=customs_link_".$field->id."]').addClass('invalid');
                        error_message.push('".JText::sprintf('COM_TESTIMONIALS_EDIT_CUSTOM_ERROR_INCORRECT', $field->name)."');
                        error = true;
                    }
                }
            ";
        }
    }
}

$script[]="
        if(!error) return true;
            else{
                if(msg) showErrorMsg(error_message);
                return false;
            }
        }
    ";
/*
 * Show error message
 */
$script[]="
        showErrorMsg = function(error_message){
		    if(typeof(error_message) != 'undefined'){
                error_message.reverse();
                error_message.push('".JText::_('COM_TESTIMONIALS_EDIT_FORM_ERROR')."');
                error_message.reverse();
			    alert(error_message.join(\"\\n\"));
		    }else{
			    alert('".JText::_('COM_TESTIMONIALS_EDIT_REQUIRED_ERROR')."');
		    }
		}
    ";
$script[]="
		hideErrorMsg = function(){
			document.getElementById('error_msg').style.visibility = 'hidden';
			document.getElementById('error_msg').style.color = '';
			document.getElementById('error_msg').innerHTML = '';
		}
    })(jQuery);
    ";

$script[]="
	(function($) {
		submit_form = function(task) {
		    if (validateFields(true)) {
				$('input[name=task]', $('#adminForm')).val(task);
				$('#adminForm').submit();
		    }
		}
            $(document).ready(function () {
                /*
                if ($(\"[rel=tooltip]\").length) {
                    $(\"[rel=tooltip]\").tooltip();
                }
                */
                $(\"[rel=rating]\").click(function (){
                    /*var block_name = $(this).attr('name');*/
                    $(this).parent().children().html('<i class=\"fa fa-star-o\"></i>');
                    var rate_val=$(this).attr('rating');
                    $('#customs_'+$(this).attr('field_id')).val(rate_val);
                    $(this).parent().children().each(function( index ) {
                      if($(this).attr('rating')<=rate_val){
                        $(this).html('<i class=\"fa fa-star\"></i>');
                      }
                    });
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
        ";
if($params->get('show_addimage') == 1){
    $script[]="
                $('#imageUpload').fileupload({
                    sequentialUploads: false,
                    dataType: 'json',
                    formData: {task: 'new_image', id: '".$testimonial_id."'},
                    xhrFields: {
                        withCredentials: true
                    },
                    submit: function (e, data) {
                        $('#imageProgressContainer').css('display','block');
                    },
                    done: function (e, data) {
                        if(data.result.status == 'ok'){
                            $('#uploadedImages').append('<a href=\"javascript:void(0)\" class=\"testim-img\"><img src=\"".JURI::root(true).'/images/testimonials/'."'+data.result.image+'\" alt=\"'+data.result.image+'\"/><span class=\"testim-del-img\" image=\"'+data.result.image+'\" onclick=\"deleteImage(this);\"></span></a>');
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
            ";
}
if($params->get('allow_photo') == 1){
   $script[] = "
            $('#avatarUpload').fileupload({
                sequentialUploads: false,
                dataType: 'json',
                formData: {task: 'new_avatar', id: '".$testimonial_id."' },
                submit: function (e, data) {
                    $('#avatarUploadButton').css('display','none');
                    $('#avatarProgressContainer').css('display','block');
                },
                done: function (e, data) {
                    if(data.result.status == 'ok'){
                        $('#avatarUploadImage').html('<img src=\"".JURI::root().'/images/testimonials/'."'+data.result.image+'\" alt=\"'+data.result.image+'\"/><span class=\"testim-del-img\" image=\"'+data.result.image+'\" onclick=\"deleteAvatar(this);\"></span>');
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
        ";
}
$script[] ="
            });
        })(jQuery);
    ";

$script = implode("",$script);
JFactory::getDocument()->addScriptDeclaration($script);JFactory::getDocument()->addStyleDeclaration('.controls textarea#jform_testimonial{	width:100%!important;}');

?>
<div class="item-page" style="padding-top: 20px;">
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&view=form&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal"  enctype="multipart/form-data">
		<?php
            $form = $this->form;
            $item = $this->item;
            $custom_fields = $this->custom_fields;
            $tags = $this->tags;

        $user		= JFactory::getUser();
        if ($helper->can("create")){
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
            } else $form_data['remove_image'] = '';

            if (($params->get('use_cb') == 1 || $params->get('use_jsoc') == 1 ) && ($user->id || $item->id) && empty($item->avatar)){
                if($item->id) $item->avatar = $helper->getUserAvatar($item->user_id_t);
                else $item->avatar = $helper-getUserAvatar($user->id);
            }else $item->avatar = '';

            ?>
            <input type="hidden" name="jform[date_added]" value="<?php echo date('Y-m-d H:i:s', time());?>" />
            <?php if($item->id) : ?>
                <input type="hidden" name="jform[user_id_t]" value="<?php echo($item->user_id_t);?>" />
            <?php endif; ?>
            <fieldset class="testim-required">
                <?php if ($params->get('show_caption')) : ?>
                    <div class="testim-field-group control-group form-group">
                        <label class="testim-label testim-required control-label" for="jform_t_caption" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('t_caption', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('t_caption', 'label')); ?>:</label>
                        <div class="controls">
                            <?php echo $form->getInput('t_caption'); ?>
                        </div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="jform[t_caption]" value="<?php echo((!empty($item->t_caption) ? $item->t_caption : '_')); ?>" />
                <?php endif; ?>
                <div class="testim-field-group control-group form-group">
                    <label class="testim-label control-label" for="jform_testimonial" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('testimonial', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('testimonial', 'label')); ?>:</label>
                    <div class="testim-texeditor-container controls">
                        <div id="jform_testimonial_toolbar" style="display: none;" class="texteditor-toolbar">
                            <div class="btn-group">
                                <a class="btn btn-default" data-wysihtml5-command="bold" title="CTRL+B"><i class="fa fa-bold"></i></a>
                                <a class="btn btn-default" data-wysihtml5-command="italic" title="CTRL+I"><i class="fa fa-italic"></i></a>
                                <a class="btn btn-default" data-wysihtml5-command="underline" title="CTRL+U"><i class="fa fa-underline"></i></a>
                                <a class="btn btn-default" data-wysihtml5-command="createLink"><i class="fa fa-link"></i></a>
                            </div>
							<!-- 
                            <div class="btn-group">
                                <a class="btn btn-default" data-wysihtml5-command="bold" title="CTRL+B"><i class="icon-bold" style="font-weight:bold;">B</i></a>
                                <a class="btn btn-default" data-wysihtml5-command="italic" title="CTRL+I"><i class="icon-italic" style="font-style:italic;">I</i></a>
                                <a class="btn btn-default" data-wysihtml5-command="underline" title="CTRL+U"><i class="icon-underline" style="text-decoration:underline;">U</i></a>
                                <a class="btn btn-default" data-wysihtml5-command="createLink"><i class="icon-link fa fa-link"></i></a>
                            </div>
							-->
                            <div data-wysihtml5-dialog="createLink" style="display: none; padding-top: 5px">
                                <div class="input-append">
                                    <input type="text" class="input-large" data-wysihtml5-dialog-field="href" value="http://">
                                    <a class="btn btn-default" data-wysihtml5-dialog-action="save"><?php echo JText::_('COM_TESTIMONIALS_SAVE'); ?></a>
                                    <a class="btn btn-default" data-wysihtml5-dialog-action="cancel"><?php echo JText::_('COM_TESTIMONIALS_CANCEL'); ?></a>
                                </div>
                            </div>
                        </div>
                        <?php echo $form->getInput('testimonial'); ?>
                    </div>
                </div>

                <?php
                $unrequiredFields = 0;
                foreach ($custom_fields as $id => $custom_field) {
                    if($custom_field->required) {
                        switch ($custom_field->type){
                            case 'url':
                                $url = array();
                                $url = explode('|', $custom_field->value);
                                ?>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_link_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name." ";?><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_LINK');?>:</label>
                                    <div class="controls">
                                        <input type="text" class="inputbox form-control" id="customs_link_<?php echo $custom_field->id?>" name="customs_link[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($url[0])?$url[0]:'');?>" />
                                    </div>
                                </div>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_name_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name." ";?><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_NAME');?>:</label>
                                    <div class="controls">
                                        <input type="text" class="inputbox form-control" id="customs_name_<?php echo $custom_field->id?>" name="customs_name[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($url[1])?$url[1]:'');?>" />
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'textarea':
                                ?>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
                                    <div class="controls">
                                        <textarea cols="30" rows="10" class="inputbox form-control" id="customs_<?php echo $custom_field->id?>" name="customs[<?php echo $custom_field->id;?>]"><?php echo htmlspecialchars(isset($custom_field->value)?$custom_field->value:'');?></textarea>
                                    </div>
                                </div>
                                <?php
                                break;
                            case 'rating':
                                ?>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
                                    <input type="hidden" name="customs[<?php echo $custom_field->id?>]" class="inputbox form-control" id="customs_<?php echo $custom_field->id?>" value="<?php echo (isset($custom_field->value) ? $custom_field->value : '')?>" />
                                    <div class="rating controls">
                                        <?php for($a=1;$a<6;$a++) : ?>
                                            <span rating="<?php echo $a;?>" rel="rating" field_id="<?php echo $custom_field->id?>"><i class="fa fa-star<?php echo ((isset($custom_field->value) && $custom_field->value >= $a) ? '' : '-o');?>"></i></span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            default:
                                ?>
                                    <div class="testim-field-group control-group form-group">
                                        <label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
                                        <div class="controls">
                                            <input type="text" class="inputbox form-control" id="customs_<?php echo $custom_field->id?>" name="customs[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($custom_field->value)?$custom_field->value:'');?>" />
                                        </div>
                                    </div>
                                <?php
                                break;
                        }
                    }else $unrequiredFields++;
                }
                ?>

                <?php if($settings->get('allow_photo')) : ?>
                    <!--Block .testim-field-group is for avatar.-->
                    <div class="testim-field-group control-group form-group testim-images-container">
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
                    <div class="testim-field-group control-group form-group">
                        <label class="testim-label control-label testim-required" for="jform_t_author" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('t_author', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('t_author', 'label')); ?>:</label>
                        <div class="controls">
                            <?php echo $form->getInput('t_author'); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </fieldset>
            <?php if($settings->get('show_tags') && !empty($tags->tags)) : ?>
                <fieldset>
                    <div class="testim-field-group control-group form-group testim-tags-container">
						<label class="testim-label control-label" for="tags" rel="tooltip" title="<?php echo JText::_('COM_TESTIMONIALS_TTAGS'); ?>" ><?php echo JText::_('COM_TESTIMONIALS_TTAGS'); ?>:</label>
                        <div class="controls">
                            <ul class="testim-tags">
                                <?php foreach($tags->tags as $tag) : ?>
                                    <li>
                                        <span class="label label-default<?php echo (in_array($tag->value, $tags->selected) ? ' label-success' : '')?>" tag="<?php echo $tag->value?>"><?php echo $tag->text?></span>
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
                </fieldset>
            <?php endif; ?>
			<fieldset>
				<div class="testim-field-group testim-field-group control-group form-group">
					<div class="controls">
						<?php echo $form->getInput('captcha'); ?>
					</div>
				</div>
			</fieldset>
            <?php if($settings->get('show_testimmore')){ ?>
                <hr class="testim-line"/>
                <?php if($unrequiredFields>0 || $settings->get('allow_photo') || $settings->get('show_authordesc')) : ?>
                    <div class="testim-field-group control-group form-group testim-field-more">
                        <div class="testim-label control-label"></div>
                        <div class="controls">
							<p>
								<i class="icon-caret-right" id="testim-more-label"></i><a href="javascript:void(0);" class="testim-more"><?php echo JText::_('COM_TESTIMONIALS_TELL_MORE');?></a>
							</p>
                        </div>
                        <fieldset>
                            <div class="testim-notrequired testim-hide" style="display: none;">
                                <?php if ($settings->get('show_authordesc')) : ?>
                                    <div class="testim-field-group control-group form-group">
                                        <label class="testim-label control-label testim-required" for="jform_author_description" rel="tooltip" title="<?php echo JText::_($form->getFieldAttribute ('author_description', 'description')); ?>" ><?php echo JText::_($form->getFieldAttribute ('author_description', 'label')); ?>:</label>
                                        <div class="testim-texeditor-container controls">
                                            <div id="jform_author_description_toolbar" style="display: none;" class="texteditor-toolbar">
												<div class="btn-group">
													<a class="btn btn-default" data-wysihtml5-command="bold" title="CTRL+B"><i class="fa fa-bold"></i></a>
													<a class="btn btn-default" data-wysihtml5-command="italic" title="CTRL+I"><i class="fa fa-italic"></i></a>
													<a class="btn btn-default" data-wysihtml5-command="underline" title="CTRL+U"><i class="fa fa-underline"></i></a>
													<a class="btn btn-default" data-wysihtml5-command="createLink"><i class="fa fa-link"></i></a>
												</div>
												<!-- 
												<div class="btn-group">
													<a class="btn btn-default" data-wysihtml5-command="bold" title="CTRL+B"><i class="icon-bold" style="font-weight:bold;">B</i></a>
													<a class="btn btn-default" data-wysihtml5-command="italic" title="CTRL+I"><i class="icon-italic" style="font-style:italic;">I</i></a>
													<a class="btn btn-default" data-wysihtml5-command="underline" title="CTRL+U"><i class="icon-underline" style="text-decoration:underline;">U</i></a>
													<a class="btn btn-default" data-wysihtml5-command="createLink"><i class="icon-link fa fa-link"></i></a>
												</div>
												-->
                                                <div data-wysihtml5-dialog="createLink" style="display: none; padding-top: 5px">
                                                    <div class="input-append">
                                                        <input type="text" class="input-large" data-wysihtml5-dialog-field="href" value="http://">
                                                        <a class="btn btn-default" data-wysihtml5-dialog-action="save"><?php echo JText::_('COM_TESTIMONIALS_SAVE'); ?></a>
                                                        <a class="btn btn-default" data-wysihtml5-dialog-action="cancel"><?php echo JText::_('COM_TESTIMONIALS_CANCEL'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo $form->getInput('author_description'); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if($settings->get('show_addimage')) : ?>
                                    <!--Block .testim-field-group is for images. When images are added into .for-images .testim-field-group is display:block-->
                                    <div class="testim-field-group control-group form-group testim-images-container">
                                        <div class="testim-label control-label">&nbsp;</div>
                                        <div class="testim-add-image controls clearfix">
                                            <div id="imageProgressContainer" class="imageProgressContainer"><div id="imageProgress" class="imageProgress"></div></div>
                                            <span id="uploadedImages" ></span>
                                            <?php
                                                $images = trim($images, '|');
                                                $images = explode('|', $images);
                                                foreach($images as $image){
                                                    if(!empty($image) && file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)){
                                                        ?>
                                                        <a href="javascript:void(0)" class="testim-img"><img src="<?php echo JURI::root().'/images/testimonials/'.$image;?>" alt="<?php echo $image?>"/><span class="testim-del-img" image="<?php echo $image?>" onclick="deleteImage(this);"></span></a>
                                                    <?php
                                                    }
                                                }
                                            ?>
                                            <div class="testim-add-img2" onclick="document.getElementById('imageUpload').click();"><span class="testim-add-img-label"><?php echo JText::_('COM_TESTIMONIALS_ADD_IMAGE'); ?></span><input type="file" name="image" onclick="event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);" id="imageUpload" data-url="<?php echo JRoute::_('index.php?option=com_testimonials&task=new_image'); ?>" class="file-input-button"></div>
                                            <input type="hidden" name="jform[exist_images]" id="jform_exist_images" value="<?php echo $item->images;?>" />
                                            <input type="hidden" name="remove_image" id="remove_image" value="<?php echo $form_data['remove_image'];?>" />
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                <?php
                $unrequiredFields = 0;
                foreach ($custom_fields as $id => $custom_field) {
                    if(!$custom_field->required) {
                        switch ($custom_field->type){
                            case 'url':
                                $url = array();
                                $url = explode('|', $custom_field->value);
                                ?>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_link_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name." ";?><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_LINK');?>:</label>
									<div class="controls">
                                        <input type="text" class="inputbox form-control" id="customs_link_<?php echo $custom_field->id?>" name="customs_link[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($url[0])?$url[0]:'');?>" />
									</div>
                                </div>
                                <?php
                                break;
                            case 'textarea':
                                ?>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
                                    <div class="controls">
                                        <textarea cols="30" rows="10" class="inputbox form-control" id="customs_<?php echo $custom_field->id?>" name="customs[<?php echo $custom_field->id;?>]"><?php echo htmlspecialchars(isset($custom_field->value)?$custom_field->value:'');?></textarea>
									</div>
                                </div>
                                <?php
                                break;
                            case 'rating':
                                ?>
                                <div class="testim-field-group control-group form-group">
                                    <label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
                                    <input type="hidden" name="customs[<?php echo $custom_field->id?>]" class="inputbox form-control" id="customs_<?php echo $custom_field->id?>" value="<?php echo (isset($custom_field->value) ? $custom_field->value : '')?>" />
                                    <div class="rating controls">
                                        <?php for($a=1;$a<6;$a++) : ?>
                                            <span style="cursor: pointer;" rating="<?php echo $a;?>" rel="rating" field_id="<?php echo $custom_field->id?>"><i class="fa fa-star<?php echo ((isset($custom_field->value) && $custom_field->value >= $a) ? '' : '-o');?>"></i></span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php
                                break;
                            default:
                                ?>
                                    <div class="testim-field-group control-group form-group">
                                        <label class="testim-label control-label" for="customs_<?php echo $custom_field->id?>" rel="tooltip" title="<?php echo $custom_field->descr; ?>" ><?php echo $custom_field->name;?>:</label>
                                       <div class="controls">
                                            <input type="text" class="inputbox form-control" id="customs_<?php echo $custom_field->id?>" name="customs[<?php echo $custom_field->id?>]" value="<?php echo htmlspecialchars(isset($custom_field->value)?$custom_field->value:'');?>" />
										</div>
                                    </div>
                                <?php
                                break;
                        }
					}
                }
                ?>
                            </div>
                        </fieldset>
                    </div>
                <?php endif; ?>
            <?php } ?>
        <?php
        }

        ?>
		<?php echo $form->getInput('catid'); ?>
		<input type="hidden" name="task" value="" />
		<?php
			if(JFactory::getApplication()->input->getVar('tmpl')=='component')
			{
				?>
				<input type="hidden" name="tmpl" value="component" />
				<?php
			}
		?>
		
    <div class="testim-buttoms testim-field-group testim-field-group control-group form-group">
		<div class="controls">
			<button class="btn" type="button" onclick="submit_form('topic.save');"> 
				<?php echo JText::_('COM_TESTIMONIALS_SAVE') ; ?>
			</button>
			<?php /*<a href="javascript:void(0)" class="btn" onclick='window.parent.document.getElementById("sbox-btn-close").click();'> */ ?>
			<a href="javascript:void(0)" class="btn" onclick='window.history.back();'>
				<?php echo JText::_('COM_TESTIMONIALS_CLOSE') ;?>
			</a>
		</div>
    </div>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</body>
</html>
