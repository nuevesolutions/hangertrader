<?php

/*
 * @version		$Id: default.php 2.6.0 2014-07-15 $
 * @package		Joomla
 * @copyright   Copyright (C) 2013-2014 Jom Classifieds
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');
$itemid = JRequest::getInt('Itemid')  ? '&Itemid=' . JRequest::getInt('Itemid') : '';
$link   = 'index.php?option=com_jomclassifieds&view=alert&task=save'.$itemid;
$cancel_link = 'index.php?option=com_jomclassifieds&view=alert'.$itemid;
$config = JomclUtils::getCfg();
$mainframe = JFactory::getApplication();	
JHTML::_('script', JURI::root().'components/com_jomclassifieds/js/bfvalidateplus.js', true, true);
require_once(JPATH_ROOT.DS.'components'.DS.'com_jomclassifieds'.DS.'etc'.DS.'recaptchalib.php');
$document = JFactory::getDocument();
$document->addScriptDeclaration("
	
	function valJomclAddForm() { 	
		f = document.jomclForm;		
		
		document.formvalidator.setHandler('list', function (value) {			
        	return (value != -1);
		});
		
        if (document.formvalidator.isValid(f)) { 			
           	return true;    
        } else {			
           	alert('" . JText::_("ERROR_VALIDATION") . "'); 
			return false;
        }    
    }    
");
$userid = JFactory::getUser()->get('id');
?>

<div class="jomclassifieds" id="jomclassifieds">

  <div class="jomclassifiedsalertbox" style="border: 1px solid #f5f5f5;padding: 10px;">
    <form action="<?php echo JRoute::_($link); ?>" method="post" name="jomclForm" id="jomclForm" class="form-horizontal" enctype="multipart/form-data">
      <div class="alert alert-info">
        <h6><?php echo JText::_('CUSTOMIZE_YOUR_ALERT_FREE_DESC'); ?></h6>
      </div>
       <?php if(!$userid) : ?>
          <div class="control-group">
            <label class="control-label"><?php echo JText::_('YOUR_USERNAME'); ?><span class="mandatory">*</span></label>
            <div class="controls">
              <input type="text" id="username" name="username" class="required" size="50" />
            </div>
          </div>
       <?php endif; ?>
      <?php if(!$userid) : ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('EMAIL'); ?><span class="mandatory">*</span></label>
        <div class="controls">
          <input type="text" id="alertemail" name="email" class="required validate-email" size="50" onblur="checkuser()"/>
        </div>
      </div>
      <div class="control-group" id="jomclpass"></div>
      <?php endif; ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('CELLPHONE'); ?><span class="mandatory">*</span></label>
        <div class="controls">
          <input type="text" id="cellphone" name="cellphone" class="required validate-phone" size="50" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('CATEGORY'); ?><span class="mandatory">*</span></label>
        <div class="controls"> <?php echo JomclHTML::ListParentCategories(); ?><span id="jcschildcategories_0"><?php echo JomclHTML::ListChildCategories(); ?></span> </div>
      </div>
      
      <?php if($config->alertaitportid) : ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('AIRPORT_IDENTIFIER'); ?><span class="mandatory">*</span></label>
        <div class="controls">
          <input type="text" id="airportid" name="airportid" class="required key" size="50"  onkeyup="jomcl_lookup(this.value);"/>
          <div id="suggestions"></div>
        </div>
      </div>
      <?php endif; ?>
     <?php /*if( $config->alertairportname && $config->alertaitportid) : ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('AIRPORT_NAME'); ?><span class="mandatory">*</span></label>
        <div class="controls">
          <input type="text" id="airportname" name="airportname" readonly class="key" size="50" value=""/>
        </div>
      </div>
      <?php endif; ?>
      <?php if( $config->showalertaddress && $config->alertaitportid) : ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('ADDRESS'); ?></label>
        <div class="controls">
          <input type="text" id="airaddress" name="address" readonly class="key" size="50" />
        </div>
      </div>
      <?php endif; ?>
      <?php if($config->airportlocation && $config->alertaitportid) : ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('LOCATION'); ?></label>
        <div class="controls">
          <input type="text" id="airlocation" name="location" readonly class="key" size="50" />
        </div>
      </div>
      <?php endif; */ ?>
      <?php /*?><div class="control-group">
  <label class="control-label"><?php echo JText::_('POSTALCODE'); ?></label>
  <div class="controls">
    <input type="text" id="postalcode" name="postalcode" readonly class="key" size="50" />
  </div>
</div><?php */?>
     <?php if( $config->showtags) : ?>
      <div class="control-group">
        <label class="control-label"><?php echo JText::_('TAGS'); ?><span class="mandatory">*</span></label>
        <div class="controls"> <?php echo JomclHTML::ListTagsAlert(); ?> </div>
      </div>
	  <?php endif; ?>
	  
      <div id="jcsextrafields_0" class="adForm"><?php echo JomclExtraFields::ListExtraFields(0, -1); ?></div>
      
       <!--<div class="control-group">
        <label class="control-label"><?php echo JText::_('PRICE'); ?><span class="mandatory">*</span></label>
        <div class="controls">
          <input type="text" id="pricemin" name="pricemin" class="required validate-price" placeholder="Min" size="50" />
          <input type="text" id="pricemax" name="pricemax" class="required validate-price" placeholder="Max" size="50" />
        </div>
      </div>-->
       <div class="control-group">
        <label class="control-label"><?php echo JText::_('ALERT_PERIOD'); ?><span class="mandatory">*</span></label>
        <div class="controls"><?php echo JText::_('FROM'); ?>
          <!-- <input type="date" id="alert_startdate" name="alert_startdate" class="required validate-date" size="50" />-->
          <?php echo JHTML::calendar("",'alert_startdate', 'alert_startdate', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>' required validate-date', 'placeholder'=>'Y-m-d')); ?> <?php echo JText::_('TO'); ?>
          <!--<input type="date" id="alert_enddate" name="alert_enddate" class="required validate-date" size="50" />-->
          <?php echo JHTML::calendar("",'alert_enddate', 'alert_enddate', '%Y-%m-%d',array('size'=>'8','maxlength'=>'10','class'=>' required validate-date', 'placeholder'=>'Y-m-d')); ?> </div>
      </div>
      <div class="control-group">
        <label class="control-label">&nbsp;</label>
        <!--div class="controls"> <?php //echo recaptcha_get_html(RE_CAPTCHA_PUBLIC_KEY); ?></div>
      </div>
      <span id="jomclreportcaptha"></span-->
     
       <div class="jomclsubmitblock">
        <!-- Hidden Fields -->
        <input type="hidden" name="mode" value="new" />    
         
        <?php if($userid) :?>
        <input type="hidden" name="username" value="<?php echo JFactory::getUser()->get('username'); ?>" />
        <input type="hidden" name="email" value="<?php echo JFactory::getUser()->get('email'); ?>" />
        <?php endif; ?>   
         <button type="submit" class="btn btn-success" onclick="return valJomclAddForm();"><i class="icon-new icon-white"></i> <?php echo JText::_('SUBMIT'); ?></button>
        <a class="btn btn-default" href="<?php echo JRoute::_($cancel_link); ?>" ><i class="icon-unpublish"></i> <?php echo JText::_('CANCEL'); ?> </a>
         <!--span id="jomclAlert"></span> <span id="jomclreportcaptha"></span--></div>
      <?php echo JHTML::_( 'form.token' ); ?>
    </form>
  </div>
</div>
<script type="text/javascript"> 
	var $autosearch = jQuery.noConflict();
    $autosearch(document).ready(function() {	
	
	  var cssObj = { 'box-shadow' : '#888 5px 10px 10px', 
		'-webkit-box-shadow' : '#888 5px 10px 10px', 
		'-moz-box-shadow' : '#888 5px 10px 10px'}; 
		$autosearch("#suggestions").css(cssObj);
		
		$autosearch("input").blur(function(){
	 		$autosearch('#suggestions').fadeOut();
			if(document.getElementById("airportid").value == '') {
				$autosearch('#airportname').val('');	
				$autosearch('#airaddress').val('');	
				$autosearch('#airlocation').val('');	
				$autosearch('#postalcode').val('');	
			
			}
			
			
		 });		
		
      });
	  
	   function jomcl_lookup(inputString) {
	   	  
		if(inputString.length == 0) {
			$autosearch('#suggestions').fadeOut(); 
			
			
		} else {
			$autosearch.post("<?php echo JURI::root(); ?>index.php?option=com_jomclassifieds&format=raw&task=autofil", {key: ""+inputString+""}, function(data) {
				$autosearch('#suggestions').fadeIn(); 
				$autosearch('#suggestions').html(data); 				
				
				$autosearch("a.clickable").click(function(event){
            			event.preventDefault();											
						$autosearch("#airportid").val($autosearch(this).html());	
						
					 var airportid = document.getElementById("airportid").value;
				     $autosearch.post("<?php echo JURI::root(); ?>index.php?option=com_jomclassifieds&format=raw&task=updatelocation&aId="+airportid, function(s) {
			 
			 		var Responce = s.replace('<input type="hidden" id="jomclbase" value="http://www.hangar33.devsoho.com/" />','');
					Responce = Responce.replace('<input type="hidden" id="joclpreloaderText" value="Loading Please wait...." />','');
					var jomclJsonObj = jQuery.parseJSON(Responce);
					
					$autosearch('#airportname').val(jomclJsonObj[0].name);	
					$autosearch('#airaddress').val(jomclJsonObj[0].address);	
					$autosearch('#airlocation').val(jomclJsonObj[0].city);	
					$autosearch('#postalcode').val(jomclJsonObj[0].zipcode);
								
					
			});										
						
           			// $autosearch("input#textbox").val($autosearch(this).html());
      			  });  
				  
				
			});
		}
		
	}
	  
    </script>
