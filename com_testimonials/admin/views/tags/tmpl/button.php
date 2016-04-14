<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
$extension  = 'com_testimonials';
$function	= JFactory::getApplication()->input->getCmd('function', 'jSelectTestimonial');

    JFactory::getDocument()->addStyleDeclaration('
        .btn-danger.active, .btn-success.active, .btn-primary.active {
            background-color: #378137 !important;
            border-color: #378137 #378137 #378137;
        }      
    ');
    JFactory::getDocument()->addScriptDeclaration('                  
        
           jQuery(document).ready(function () {
           
            var count = 5;
            var flag = "cats";
    
            insertTst = function()
            {
                var layout = getInputOption("jform_layouts", false);                
                var tag = "tag:"+getInputOption("jform_tst_modal_tags", true);               
                var cat = getInputOption("jform_tst_modal_categories", true);                              
                      
                if (layout == "") layout = "default";
                else layout = layout;
                
                if (layout.charAt(0)+layout.charAt(1) == "_:") layout = layout.slice(2);
                
                if (layout || tag || cat || count) {
                    if (flag == "tags") {
                        parent.window.jInsertEditorText("{testimonials "+layout+"|"+tag+"|"+count+"}", "jform_articletext");                    
                    }
                    if (flag == "cats") {
                        parent.window.jInsertEditorText("{testimonials "+layout+"|"+cat+"|"+count+"}", "jform_articletext");   
                    }
                }
                else {
                    parent.window.jInsertEditorText("{testimonials}", "jform_articletext");
                }
                parent.window.jModalClose();
            };
        
         
            function getInputOption(id, getText) {
                select = document.getElementById(id);
                if (!getText) value = jQuery("#"+id).find(":selected").val();
                else value = jQuery("#"+id).find(":selected").text();
                if (value != null) return value+"";
                else return "";
            };   
           
           
                jQuery(".tst_toggle").click(function(){
                    jQuery(".tst_tabs").hide();
                                     
                    if (jQuery(this).hasClass("tst_cats")) {
                        jQuery(".tst_tab_cats").show();
                        flag = "cats"
                    }
                    else {
                        jQuery(".tst_tab_tags").show();
                        flag = "tags"
                    }
                });
                
                jQuery("#jform_numbers").keyup(function(){
                    count = jQuery(this).val();                 
                });
            }); 
    ');
?>



<form class="form">
    <br>
    <?php echo $this->form->getLabel('layouts'); ?>
    <?php echo $this->form->getInput('layouts'); ?>
    <br>
    <?php echo $this->form->getInput('admin_style'); ?>
    <br>
    <div class="btn-group" role="group" aria-label="Default button group">
        <?php echo $this->form->getInput('TagOrCat'); ?>
    </div>
    <br>
    <br>
    <div class="tst_tab_cats tst_tabs">
    <?php echo $this->form->getInput('cats'); ?>
    </div>
    <div class="tst_tab_tags tst_tabs" style="display: none">
        <?php echo $this->form->getInput('tags'); ?>
    </div>
    <br>
    <?php echo $this->form->getLabel('numbers'); ?>
    <?php echo $this->form->getInput('numbers'); ?>
    <br>
    <button onclick="insertTst();" class="btn btn-primary"><?php echo JText::_('Insert'); ?></button>
</form>

