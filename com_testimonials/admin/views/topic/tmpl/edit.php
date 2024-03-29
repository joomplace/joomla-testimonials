<?php
/**
 * Testimonials Component for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.formvalidation');
jimport('joomla.filesystem.file');
$images = (!empty($this->item->images)) ? explode("|", $this->item->images) : '';
//TestimonialHelper::addFileUploadFull('index.php?option=com_testimonials&task=images.addImage&id=' . (int) $this->item->id, 'topic-form', $this->item->preloadImages);
$testimonial_id= $this->item->id;
?>
<?php echo $this->loadTemplate('menu'); ?>
<script type="text/javascript">
    var count = <?php echo (is_array($images) && count($images) > 0) ? (count($images) + 1) : 1; ?>;
    function insertRow() {
        if (count < 4) {
            $('#filelist').append('<input type="file" name="jform[image][]" value="" /><br style="clear: both;"/>');
            count = count + 1;
        } else {
            $('[name="add_another"]').attr('disabled', 'disabled');
        }
    }
    Joomla.submitbutton = function(task)
    {
        if (task == 'topic.cancel' || document.formvalidator.isValid(document.id('topic-form'))) {
<?php echo $this->form->getField('testimonial')->save(); ?>
            Joomla.submitform(task, document.getElementById('topic-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }
    jQuery(document).ready(function() {
        jQuery('#topic-form').bind('fileuploaddone', function(e, data) {
            for (var key in data.result) {
                if (data.result[key].status == 'ok') {
                    jQuery('#jform_exist_images').val(jQuery('#jform_exist_images').val() + '|' + data.result[key].image);
                }
            }
        });
        jQuery('#topic-form').bind('fileuploaddestroy', function(e, data) {
            var filename = data.url.substring(data.url.indexOf("image=") + 6);
            jQuery('#remove_image').val(jQuery('#remove_image').val() + '|' + filename);
        });
        jQuery('#configTabs a:first').tab('show');
    });
</script>
<form action="<?php echo JRoute::_('index.php?option=com_testimonials&layout=edit&id=' . (int) $this->item->id); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="topic-form" class="form-validate">
    <input type="hidden" name="jform[date_added]" value="<?php echo date('Y-m-d H:i:s', time()); ?>" />
    <div class="row-fluid" id="adminForm">
        <div id="j-main-container" class="span7 form-horizontal">
            <ul class="nav nav-tabs" id="configTabs">
                <li><a href="#topic-details" data-toggle="tab"><?php echo JText::_('COM_TESTIMONIALS_DETAILS'); ?></a></li>
                <li><a href="#customs-details" data-toggle="tab"><?php echo JText::_('COM_TESTIMONIALS_SUBMENU_CUSTOMS'); ?></a></li>
                <li><a href="#images-details" data-toggle="tab"><?php echo JText::_('ADD_IMAGES'); ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="topic-details">
                    <fieldset class="adminform">
                        <legend><?php echo empty($this->item->id) ? JText::_('COM_TESTIMONIALS_NEW_TOPIC') : JText::sprintf('COM_TESTIMONIALS_EDIT_TOPIC', $this->item->id); ?></legend>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('t_caption'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('t_caption'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('t_author'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('t_author'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('catid'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('catid'); ?>
                            </div>
                        </div>
                        <br style="clear:both;"/>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('author_description'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('author_description'); ?>
                            </div>
                        </div>
                        <br style="clear:both;"/>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('testimonial'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('testimonial'); ?>
                            </div>
                        </div>
                </div>
                <div class="tab-pane" id="customs-details">
                    <div class="row-fluid">
                        <?php
                        $custom_fields = $this->custom_fields;
                        for ($i = 0; $i < count($custom_fields); $i++) {
                            $custom_field = $custom_fields[$i];
                            if ($custom_field->type == 'url') {
                                $url = array();
                                $url = explode('|', $custom_field->value);
                                ?>
                                <div class="control-group">
                                    <label for="customs_link_<?php echo $custom_field->id ?>" <?php if (!empty($custom_field->descr)) {
                            echo('class="control-label hasTip" title="' . $custom_field->name . '::' . $custom_field->descr . '"');
                        } else {
                            echo('class="control-label"');
                        } ?>><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_LINK'); ?> - <?php echo $custom_field->name; ?></label>
                                    <div class="controls">
                                        <input type="text" id="customs_link_<?php echo $custom_field->id ?>" name="customs_link[<?php echo $custom_field->id ?>]" value="<?php echo htmlspecialchars(isset($url[0]) ? $url[0] : ''); ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="customs_name_<?php echo $custom_field->id ?>" <?php if (!empty($custom_field->descr)) {
                            echo('class="control-label hasTip" title="' . $custom_field->name . '::' . $custom_field->descr . '"');
                        } else {
                            echo('class="control-label"');
                        } ?>><?php echo JText::_('COM_TESTIMONIALS_CUSTOMS_URL_NAME'); ?> - <?php echo $custom_field->name; ?></label>
                                    <div class="controls">
                                        <input type="text" id="customs_name_<?php echo $custom_field->id ?>" name="customs_name[<?php echo $custom_field->id ?>]" value="<?php echo htmlspecialchars(isset($url[1]) ? $url[1] : ''); ?>"/>
                                    </div>
                                </div>
    <?php
    }
    if ($custom_field->type != 'url') {
        if ($custom_field->type == 'rating') {
            ?>
                                    <div class="control-group">
                                        <label for="customs_<?php echo $custom_field->id ?>" <?php if (!empty($custom_field->descr)) {
                                        echo('class="control-label hasTip" title="' . $custom_field->name . '::' . $custom_field->descr . '"');
                                    } else {
                                        echo('class="control-label"');
                                    } ?>><?php echo $custom_field->name; ?></label>
                                        <div class="controls">
                                            <select name="customs[<?php echo $custom_field->id ?>]">
                                                <option value="0">0</option>
                                            <?php for ($a = 1; $a < 6; $a++) : ?>
                                                    <option value="<?php echo $a; ?>"<?php echo ((isset($custom_field->value) && $custom_field->value == $a) ? ' selected="selected"' : ''); ?>><?php echo $a; ?></option>
                                            <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                            <?php
                                        }else {
                                            ?>
                                    <div class="control-group">
                                        <label for="customs_<?php echo $custom_field->id ?>" <?php if (!empty($custom_field->descr)) {
                                    echo('class="control-label hasTip" title="' . $custom_field->name . '::' . $custom_field->descr . '"');
                                } else {
                                    echo('class="control-label"');
                                } ?>><?php echo $custom_field->name; ?></label>
                                        <div class="controls">
                                    <?php
                                    if ($custom_field->type == 'textarea') {
                                        ?>
                                                <textarea id="customs_<?php echo $custom_field->id; ?>" name="customs[<?php echo $custom_field->id; ?>]"><?php echo htmlspecialchars(isset($custom_field->value) ? $custom_field->value : ''); ?></textarea>
                <?php
            } else {
                ?>									
                                                <input type="text" id="customs_<?php echo $custom_field->id ?>" name="customs[<?php echo $custom_field->id ?>]" value="<?php echo htmlspecialchars(isset($custom_field->value) ? $custom_field->value : ''); ?>"/>
                <?php
            }
            ?>
                                        </div>
                                    </div>
            <?php
        }
    }
}
?>
                    </div>
                </div>
                <div class="tab-pane" id="images-details">

                    <div class="span12" style="padding-right: 15px;">
                        <!-- The loading indicator is shown during file processing -->
                        <div class="fileupload-loading"></div>

                        <div class="testim-field-group control-group form-group testim-images-container">
                            <div class="testim-add-image controls clearfix">
<!--                                <div id="imageProgressContainer" class="imageProgressContainer"><div id="imageProgress" class="imageProgress"></div></div>-->
                                <span id="uploadedImages" >
                                    <?php
                                    if(is_array($images) && !empty($images)){
                                        foreach ($images as $image) {
                                            if (!empty($image) && file_exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'testimonials' . DIRECTORY_SEPARATOR . $image)) {
                                                ?>
                                                <a href="javascript:void(0)" class="testim-img"><img src="<?php echo JURI::root() . '/images/testimonials/' . $image; ?>" alt="<?php echo $image ?>"/><span class="testim-del-img" image="<?php echo $image ?>" onclick="deleteImage(this);"></span></a>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </span>
                                <div class="testim-add-img2" onclick="document.getElementById('imageUpload').click();"><span class="testim-add-img-label"><?php echo JText::_('COM_TESTIMONIALS_ADD_IMAGE'); ?></span><input type="file" name="image" onclick="event.stopPropagation ? event.stopPropagation() : (event.cancelBubble=true);" id="imageUpload" data-url="<?php echo JRoute::_('index.php?option=com_testimonials&task=new_image'); ?>" class="file-input-button"></div>
                                <input type="hidden" name="jform[exist_images]" id="jform_exist_images" value="<?php echo $this->item->images;?>" />
                                <input type="hidden" name="remove_image" id="remove_image" value="<?php echo (empty($form_data['remove_image']))?'':$form_data['remove_image'];?>" />
                            </div>
                        </div>
                    </div>
                    <!-- The template to display files available for upload -->
                    <script id="template-upload" type="text/x-tmpl">
                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-upload fade">
                        <td class="preview"><span class="fade"></span></td>
                        <td class="name"><span>{%=file.name%}</span></td>
                        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                        {% if (file.error) { %}
                        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                        {% } else if (o.files.valid && !i) { %}
                        <td>
                        <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
                        </td>
                        <td class="start">{% if (!o.options.autoUpload) { %}
                        <button class="btn btn-primary">
                        <i class="icon-upload icon-white"></i>
                        <span>Start</span>
                        </button>
                        {% } %}</td>
                        {% } else { %}
                        <td colspan="2"></td>
                        {% } %}
                        <td class="cancel">{% if (!i) { %}
                        <button class="btn btn-warning">
                        <i class="icon-ban-circle icon-white"></i>
                        <span>Cancel</span>
                        </button>
                        {% } %}</td>
                        </tr>
                        {% } %}
                    </script>
                    <!-- The template to display files available for download -->
                    <script id="template-download" type="text/x-tmpl">
                        {% for (var i=0, file; file=o.files[i]; i++) { %}
                        <tr class="template-download fade">
                        {% if (file.error) { %}
                        <td></td>
                        <td class="name"><span>{%=file.name%}</span></td>
                        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                        <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                        {% } else { %}
                        <td class="preview">{% if (file.thumbnail_url) { %}
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" class="modal" ><img src="{%=file.thumbnail_url%}"></a>
                        {% } %}</td>
                        <td class="name">
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" class="modal">{%=file.name%}</a>
                        </td>
                        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                        <td colspan="2"></td>
                        {% } %}
                        <td class="delete">
                        <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                        <i class="icon-trash icon-white"></i>
                        <span>Delete</span>
                        </button>
                        <input type="checkbox" name="delete" value="1">

                        </td>
                        </tr>
                        {% } %}
                    </script>

                </div>
            </div>
        </div>
        <div id="j-right-sidebar-container" class="span3">
            <div class="accordion" id="accordion2">
                <div class="accordion-group">
                   
                    <?php echo JHtml::_('sliders.start','newsfeed-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
		<?php echo JHtml::_('sliders.panel',JText::_('COM_TESTIMONIALS_USER_DETAILS'), 'user-details'); ?>
                            <fieldset class="panelform">
<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('user_id_t'); ?>
					<?php echo $this->form->getInput('user_id_t'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('ip_addr'); ?>
					<?php echo $this->form->getInput('ip_addr'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('photo'); ?>
					<?php echo $this->form->getInput('photo'); ?>
					<?php
						$photo = $this->item->photo;
						$path= JPATH_SITE.DIRECTORY_SEPARATOR.$photo;
						if (file_exists($path) && is_file($path))
						{
							$imageTypes = 'xcf|odg|gif|jpg|png|bmp';
							$isimage = preg_match("/\.(?:$imageTypes)$/i",$photo);
							if ($isimage)
							{
								?>
								<div id="clr" class="clr"></div>
								<div style="text-align:center;">
                                    <img style=" width: 114px" class="ti_photo" src="<?php echo JURI::root().$photo;?>" alt="<?php echo $this->item->t_author;?>" />
								</div>
								<?php
							}
						}
						// CB photo
						if ($this->settings->get('use_cb') && isset($this->avatar))
						{
							$path= JPATH_SITE.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'comprofiler'.DIRECTORY_SEPARATOR.$this->avatar;
							if (file_exists($path) && is_file($path))
							{
								?>
								<div id="clr" class="clr"></div>
								<div><?php echo JText::_('COM_TESTIMONIALS_CB_PHOTO'); ?></div>
								<div style="text-align:center;">
									<img class="cb_photo ti_photo" src="<?php echo JURI::base().'index.php?option=com_testimonials&task=showpicture&image=images/comprofiler/'.$this->avatar.'&width=114';?>" alt="<?php echo $this->avatar;?>" />
								</div>
								<?php
							}
						}
						// JomSocial photo
						if ($this->settings->get('use_jsoc') && isset($this->avatar))
						{
							$path= JPATH_SITE.DIRECTORY_SEPARATOR.$this->avatar;
							if (file_exists($path) && is_file($path))
							{
								?>
								<div id="clr" class="clr"></div>
								<div><?php echo JText::_('COM_TESTIMONIALS_JOMSOC_PHOTO'); ?></div>
								<div style="text-align:center;">
									<img class="js_photo ti_photo" src="<?php echo JURI::base().'index.php?option=com_testimonials&task=showpicture&image='.$this->avatar.'&width=114';?>" alt="<?php echo $this->avatar;?>" />
								</div>
								<?php
							}
						}
						
					?>
				</li>
			</ul>
                            </fieldset>
                      
                </div>
                <div class="accordion-group">
                    
                   <?php echo JHtml::_('sliders.panel',JText::_('COM_TESTIMONIALS_PUBLIC_DETAILS'), 'publishing-details'); ?>
                            <fieldset class="panelform">
<ul class="adminformlist">
				<li>
					<?php echo $this->form->getLabel('id'); ?>
					<?php echo $this->form->getInput('id'); ?>
				</li>
				<li>
					<?php echo $this->form->getLabel('published'); ?>
					<?php echo $this->form->getInput('published'); ?>
				</li>
                <li>
                    <?php echo $this->form->getLabel('is_approved'); ?>
                    <?php echo $this->form->getInput('is_approved'); ?>
                </li>
				<li>
					<?php echo $this->form->getLabel('date_added'); ?>
					<?php echo $this->form->getInput('date_added'); ?>
				</li>
			</ul>
                            </fieldset>
                        
                </div>
                <div class="accordion-group">
                    
                    <?php echo JHtml::_('sliders.panel',JText::_('COM_TESTIMONIALS_ADMINISTRATION_TAGS'), 'tags-details'); ?>
		<fieldset class="panelform">
			<ul class="adminformlist">		
				<li>
					<?php echo $this->form->getLabel('tags'); ?>
					<?php echo $this->form->getInput('tags'); ?>
				</li>
			</ul>
		</fieldset>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>	
    </div>
</form>
<?php
$script="
(function($) {
	submit_form = function(task) {
		if (validateFields(true)) {
			$('input[name=task]', $('#adminForm')).val(task);
			$('#adminForm').submit();
		}
	}
	$(document).ready(function () {
		$(\"[rel=rating]\").click(function (){
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
		}
		
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
		
	});
})(jQuery);
";
$document = JFactory::getDocument();
$document->addScriptDeclaration($script);
$document->addStyleSheet($this->baseurl.'/components/com_testimonials/assets/submit-form/css/template_testimonials.css');
$document->addScript($this->baseurl.'/components/com_testimonials/assets/submit-form/js/main.js');
$document->addScript($this->baseurl.'/components/com_testimonials/assets/file-upload/js/vendor/jquery.ui.widget.js');
$document->addScript($this->baseurl.'/components/com_testimonials/assets/file-upload/js/jquery.iframe-transport.js');
$document->addScript($this->baseurl.'/components/com_testimonials/assets/file-upload/js/jquery.fileupload.js');
?>