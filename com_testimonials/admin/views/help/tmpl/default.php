<?php
/**
* Testimonials Component for Joomla 3
* @package Testimonials
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
 
JHtml::_('behavior.tooltip');
?>
<?php echo $this->loadTemplate('menu');?>
<table class="admin">
	<tr>
	<td valign="top" class="lefmenutd">
			<div>
				<?php echo $this->leftmenu;?>				
			</div>
		</td>
		<td>
			<div class="helptable">

                <div style="padding-left:10px ">
                    <h4><b>Help</b></h4>
                    <div style="padding-left:10px ">
                        <p>	This manual outlines key features. For detailed documentation, see the <a target="_blank" href="http://www.joomplace.com/video-tutorials-and-documentation.html">Video Tutorials and Documentation section</a>.
                        </p>
                    </div>
                </div>
							<div style="padding-left:10px ">
							<h4><b>1 Installation</b></h4>
							<div style="padding-left:10px ">
							<p>	Select <b>Extensions&gt;Extension Manager: Install</b> from the drop-down menu of the Joomla! Administrator Panel.<br />
								Browse the component's installation package file in the Upload Package File section and press <b>Upload File&amp;Install</b>.<br />
								Upload the extension’s plugins and modules following the same steps.
							</p>
							</div>
							</div>

							<div style="padding-left:10px ">
							<h4><b>2 Configuration</b></h4>
							<div style="padding-left:10px ">
<p>To configure the settings, select <b>Components>Testimonials>Settings</b> in Joomla! Administration panel or enter the component page and select <b>Testimonials Settings>Settings</b> in the menu on the left.
</p>
There are four tabs in the section: Templates, User information, Module settings, 
Permissions.
<p><b>Templates settings</b>:</p>
<ul>
    <li>Enable/Disable and Set testimonial title</li>
    <li>Show/Hide caption</li>
    <li>Show/Hide tags</li>
    <li>Show/Hide captcha</li>
    <li>Ordering testimonials</li>
</ul>
<p><b>User information</b>:</p>
<ul>
    <li>Show/Hide avatar</li>
    <li>Use Community Builder</li>
    <li>Set thumbnail width</li>
    <li>Show/Hide author name</li>
    <li>Show/Hide author description</li>
</ul>
&nbsp;
<p><b>Module settings</b>:</p>
<ul>
    <li>Timeout between testimonials changes</li>
    <li>Number of letters to show in testimonial module</li>
    <li>Set thumbnail width</li>
    <li>Show the 'Read more' and the 'Add new' link</li>
    <li>Set link positions</li>
    <li>Filter testimonials by tag</li>
</ul>
<p><b>Permissions</b>:</p>
<ul style="margin-left: 40px;">
	<li>Allow  users and groups to post</li>
	<li>Allow publishing/unpublishing</li>
	<li>Allow uploading photos</li>
	<li>Allow selecting tags</li>
    <li>Auto-approve testimonials added by users</li>
</ul>
<p>
								Once you've configured all the settings press the <i>Save</i> button in the top right corner of the form to save the settings.
								</p>
							</div>
							</div>

							<div style="padding-left:10px ">
							<h4><b>3 Management</b></h4>
							<div style="padding-left:10px ">
							<p>
							Once you configured the settings you may start managing tags, testimonials, custom fields and templates. To pass to the tags page you can either go to <b>Components>Testimonials>Testimonials Management</b> or navigate to this section using the left page menu. In addition, there're quick transition links on the top right side of the page.
							</p>
							</div>
							</div>

							<div style="padding-left:10px ">
							<h4><b>4 Tags</b></h4>
							<div style="padding-left:10px ">
							<p>
							This page represents the list of created tags. 
							To manage tags, use standard Joomla buttons in the top right corner of the page. You may also specify the number of testimonials to display on yhe page. To do this, set the number of items in the Display# field.
							
							TO CREATE A NEW TAG:<ul>
							<li>Go to <b>Components>Testimonials>Tags</b>.
							<li>Press the <b>New</b> button in the top right corner of the page. 
							<li>Enter the name of the newly created tag in the Tag name field and mark if it should be published or not.
							<li>Press <b>Save</b> to save the tag and return to the page with the list of tags or <b>Apply</b> to apply the changes and stay on the same page. Press <b>Cancel</b> to discard changes.
							</ul>
							Option <strong>"Assignning this Tag"</strong> allows showing testimonials in the module depending on what page a user is located on.  <br/>
							To do this, you can assign a tag to those testimonials that are related to some part of the site (e.g. a menu item or an articles category), and then assign this tag to a corresponding menu or a category. In the module settings of the Settings section, you can also select testimonials to display in the modules<br/>
							<ul>
							<li><b>Show all testimonials</b> - all testimonials will be shown</li>
							<li><b>Show only testimonials assigned to a spacific tag(tags) and testimonials without tag"</b> - testimonials are assigned to some tags, and those not assigned to any tag will be shown</li>
							<li><b>Testimonials assigned to a specific tag (tags)</b> - testimonials assigned to the specified tag (tags) will be shown</li>
							</ul>
							<strong>Note:</strong> Assigning tags to menu and articles for the module won't work in case a tag has already been selected for the module in the Modules Manager.
							
							</p>
							</div>
							</div>

							<div style="padding-left:10px ">
							<h4><b>5 Testimonials</b></h4>
							<div style="padding-left:10px ">
							<p>
							This page represents a list of existing testimonials and information about them. To manage testimonials, use standard Joomla! buttons in the top right corner of the page. To change the order of items, set the sequence number in the Order column of the form and click the button; alternatively you may use <b>Move up</b> and <b>Move down</b> buttons.
							
							TO CREATE A NEW TESTIMONIAL:<ul>
							<li>Go to <b>Components>Testimonials>Manage Testimonials</b>.
							<li>Press the New button in the top right corner of the page </li> 
							<li>Insert a caption, enter author's name and text of the testimonial, then select a tag from the list; browse a .jpg photo on your machine and indicate whether a testimonial should be published or not</li>
							<li>In tab <b>Custom Fields</b>, you can enter the values of custom fields for every testimonial</li>
							<li>Press <b>Save</b> to save the tag and return to the page with the list of testimonials or <b>Apply</b> to apply the changes and stay on the same page. Press <b>Cancel</b> to discard the changes
							</ul>
							</p>
							</div>
							</div>

													
							<div style="padding-left:10px ">
							<h4><b>6 Custom Fields Manager</b></h4>
							<div style="padding-left:10px ">
							<p>In this section, you can create custom fields  to be added to the testimonials templates (please see the instructions in section Templates Manager). To create a custom Field,click button New and set the following options:
							<br/>
							<ul>
								<li><b>Name</b> is the name of custom field</li>
								<li><b>Type of custom field</b> is the field type (Single input field, URL and Email field)</li>
								<li><b>Published</b> should be set if you'd like to publish the field</li>
								<li><b>Required field</b> should be set if you'd like to make the particular custom field obligatory for data input</li>
								<li><b>Description</b> is the field specification that will display in the pop-uptip if navigating cursor  to the field name when creating testimonials</li>								
							</ul>
							<br/>
							Once the fields are filled in, click the Save button. In the custom fields list, you can change the sequence of fields, enable and disable the Published option for the fields.
							</p>
							</div>
							</div>
							
							<div style="padding-left:10px ">
							<h4><b>7 Templates Manager</b></h4>
							<div style="padding-left:10px ">
							<p>In this section, you can set the templates and add custom Fields to them.<br/>
							<b>Note:</b> The knowledge of HTML is required for editing the templates. In case you accidentally damage the template, click <b>Sample Data > Reset Templates</b>. The original state of the templates will be restored. 
To edit the templates and to add custom fields, go to <b>Testimonials Management > Template Management</b>. The list of pre-installed testimonial templates will appear. Select the required template and edit HTML in the text area. To add a custom field, put the cursor to the place where you'd like to see the meaning of the custom field, and click on the required custom field (in the Add custom fields to template list). A special tag will be added to the template. This tag will be replaced with the values of the corresponding custom field. When you finish editing the template, click'Save. To preview the template, click Preview, you'll see the template in the pop-up window.
Template styles can also be modified. To edit styles, click the Edit CSS button.
							</p>
							</div>
							</div>
							
							<div style="padding-left:10px ">

							<h4><b>8 Creating Menu Item</b></h4>
							<div style="padding-left:10px ">
							<p>To get the component to display from front end, create a menu through the Joomla 'Menu Item Manager' section. Click New and choose <b>Testimonials > Testimonials Layout</b>. Enter a name for the menu item and proceed to the Testimonials Settings section to select either All tags (if you want all testimonials from all categories/tags to be displayed) or Select tags from the list. Set up the template using the Select Template drop-down list and press <b>Save</b>.<br />
If you have custom fields with the field type 'Rating', you might want to configure testimonials summary rating. Just place the tag <b>[custom field name_summary]</b> anywhere into the <b>Intro Text</b> field. See an example: imagine you created a field 'Product quality', the tag will look like [Product quality_summary]. To simplify the process, install the plg_editors_xsd_tmrating plugin. Once done, the Testimonials summary rating button will show up below the editor. Press the button, select a field in the pop-up window and save the changes.

							</p>
							</div>
							</div>
							
							<div style="padding-left:10px ">

							<h4><b>9 Sample Data</b></h4>
							<div style="padding-left:10px ">
							<p>If you want to have a visual example of testimonials before creating your own ones, install sample data. To do this, select <b>Sample Data>Install sample data</b> in the menu on the left. Once the process is completed, you will see 3 sample testimonials, 1 sample tag, and 3 custom fields.</p>
							</div>
							</div>
							
							</div>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
