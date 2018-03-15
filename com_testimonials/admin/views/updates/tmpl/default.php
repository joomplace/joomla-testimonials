<?php
/**
 * Testimonials component for Joomla 3
 * @package Testimonials
 * @author JoomPlace Team
 * @copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');

$updatefolder = JFactory::getApplication()->getUserState('com_testimonials.update.updatefolder');
?>
<?php echo $this->loadTemplate('menu');?>
<style type="text/css">
.icon-48-update	 		 { background-image: url("components/com_testimonials/assets/images/joomplace_logo-48.png"); 		 	 }
</style>
<script type="text/javascript">
	var isIE8 = window.XDomainRequest ? true : false;
	var invocation = createCrossDomainRequest();
	
	function createCrossDomainRequest(url, handler)	{
		var request;
		if (isIE8) {
			request = new window.XDomainRequest();
		} else {
			request = new XMLHttpRequest();
		}
		return request;
	}
		 
	function callOtherDomain() {
		var order_id = document.checkJPUser.order.value;
		if (order_id) {
			var url = "http://www.joomplace.com/index.php?option=com_jparea&task=checkjpuser&tmpl=component&order="+order_id;
			if (invocation) {
				if(isIE8) {
					invocation.onload = outputResult;
					invocation.open("GET", url, true);
					invocation.send();
				} else {
					invocation.open('GET', url, true);
					invocation.onreadystatechange = handler;
					invocation.send();
				}
			} else {
				alert("<?php echo JText::_('COM_TESTIMONIALS_UPDATE_NO_INVOCATION');?>");
			}
		} else {
			alert("<?php echo JText::_('COM_TESTIMONIALS_UPDATE_EMPTY_ORDERID');?>");
		}
	}
		 
	function handler(evtXHR) {
		if (invocation.readyState == 4) {
			if (invocation.status == 200) {
				outputResult();
			} else {
				alert("<?php echo JText::_('COM_TESTIMONIALS_UPDATE_INVOCATION_ERROR');?>");
			}
		}
	}
		 
	function outputResult() {
		console.log(invocation.response);
		var response = JSON.decode(invocation.responseText);
		if (response.status == 'OK') {
			document.getElementById('check').value = "1";
			document.getElementById('subs_date').innerText = response.subsdate;
			document.getElementById('subs_date').textContent = response.subsdate;
			document.getElementById('info_box').style.display = 'block';
			setTimeout(document.checkJPUser.submit(), 300);
		} else if (response.status == 'NO') {
			if (response.error_code == 1) {
				alert(response.error_msg);
			} else {
				alert(response.error_msg);
				window.location.href = "index.php?option=com_testimonials&view=updates&layout=error";	
			}
		}
	}
</script>
<div id="info_box" style="display: none; margin-bottom: 20px;">
	<div id="system-message-container">
		<dl id="system-message">
			<dt class="message">Message</dt>
			<dd class="message message">
				<ul>
					<li>Thank you. Your subscription is valid until: <span id="subs_date"></span>. Update process will continue in few seconds.</li>
				</ul>
			</dd>
		</dl>
	</div>
</div>
<form action="<?php echo JRoute::_('index.php?option=com_installer&view=install');?>" method="post" name="checkJPUser">
	<div>
		<label for="juser" class="hasTip" title="::<?php echo JText::_('COM_TESTIMONIALS_UPDATE_ORDERID_DESC'); ?>">
			<?php echo JText::_('COM_TESTIMONIALS_UPDATE_ORDERID_LABEL');?> <input type="text" onKeydown="javascript:if (13 == event.keyCode) { return false; }" size="30" id="order" name="order" />
		</label>&nbsp;
		<input type="button" onClick="callOtherDomain();" value="<?php echo JText::_('COM_TESTIMONIALS_UPDATE_BUTTON_CHECK');?>" />
	</div>
	<div>
		<input type="hidden" name="task" value="install.install" />
		<input type="hidden" name="type" name="type" value />
		<input type="hidden" name="check" id="check" value />
		<input type="hidden" name="installtype" value="folder" />
		<input type="hidden" name="install_directory" value="<?php echo addslashes($updatefolder);?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
