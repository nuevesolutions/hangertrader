<?php
/*
 * @version		$Id: default.php 3.0 2015-02-16 $
 * @package		Joomla
 * @copyright   Copyright (C) 2013-2014 Jom Classifieds
 * @license     GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die('Restricted access');
?>
<script src="/templates/rt_myriad/html/com_jomclassifieds/advert/jquery.flexslider-min.js"></script>
<script src="/templates/rt_myriad/html/com_jomclassifieds/advert/modernizr.js"></script>
<link rel="stylesheet" href="/templates/rt_myriad/html/com_jomclassifieds/advert/flexslider.css" type="text/css" />
<?php 
$item = $this->item;
$extrafields = $this->extrafields;
$markup = JomclHTML::labelMarkUps($this->config, $item);
$tagMarkup = JomclHTML::LabelTags($this->config, $item);
if(!$item->category) {
	$item->category = 'n/a';
}
$currency = $item->currency == -1 ? '': $item->currency;
if($item->price == '0.00') {
	$price = 'n/a';
} else {

	if($this->config->showprice == 1){
		$price = number_format($item->price, 2) . ' ' . $currency; 
	} elseif ($this->config->showprice == 2){
		$price = $currency . ' ' . number_format($item->price, 2); 
	} else{
		$price = number_format($item->price, 2) . ' ' . $currency;
	}
}
$itemId = JRequest::getInt('Itemid');
$user = JFactory::getUser($item->userid);
$username = $user->username;
$Favourites = JFactory::getUser();

/* function for building images */
function buildCarousel($images,$item) {
	if($images == ''){
		$html = '<section class="slider">
				  <div class="flexslider">
					<ul class="slides"> 
						<li>	
							<img src="https://www.hangartrader.com/components/com_jomclassifieds/assets/noimage.gif" alt="no-image" />
						</li>
					</ul>
				  </div>
				  </section>';
		return $html;
	}
	$images = explode(",", $images);
	$n = count($images);
	$html = '';	
	if($n) {
		$html .= '<section class="slider">
				  <div class="flexslider">';
		$html .= '<ul class="slides"> ';	
		foreach ($images as $row ):		 
			$html .= '<li data-thumb="'.JomclUtils::getImage($row, '_popup').'">';
			$html .= '<img  src="'.JomclUtils::getImage($row, '_popup').'" alt="'.basename(JomclUtils::getImage($row, '_list')).'" >';		
			$html .= '</li>';
		endforeach;	
		$html .= '</ul>';
		$html .= '</div>
				  </section>';
	} else {
		
	}
	return 	$html;			 
}
?>

<div class="rt-flex-container">
	<div class="rt-grid-3">
		<div class="detial-left-block">
			<!-- email block -->
			<?php //echo $item->email; ?>
			<div class="email-block">
			<?php if(!empty($item->email) ) : ?>
				<div class="emailblock">
					<span class="icon-user"></span><p><?php echo $item->email; ?></p>
				</div>
			<?php endif; ?> 
			</div>
			<!-- price block -->
			<div class="price-block">
			<?php if($this->config->showprice) : ?>
				<?php if($price != 'n/a') { ?>
					<div class="jomcl-right"><span class="icon-dollar"><Strong>$</Strong></span><?php echo $item->price; ?></div>
				<?php } ?>
			<?php endif; ?>
			</div>
			<!--  phone number -->
			<div class="phone-block">
			<?php if(!empty($item->phonenumber)) : ?>
				<strong>Seller Phone #</strong>
				<div class="phoneblock">
					<span class="icon-mobile"></span>
					<?php 
						echo "(".substr($item->phonenumber, 0, 3).") ".substr($item->phonenumber, 3, 3)."-".substr($item->phonenumber,6);
					?>
				</div>
			<?php endif; ?>
				<div class="company-address">
				<?php if(count($extrafields) > 0 ) :
						 foreach($extrafields as $extrafield) :	
							if($extrafield->label =="Advertiser's Name" || $extrafield->label =="Company"){
								echo $extrafield->value;
								echo '<br>';
							}
						 endforeach;
					  endif;
				?>
				</div>
			</div>
			<!-- address block --> 			
			<?php if ( ($this->config->showlocname)  && ($this->address['address'] !='')): ?>
				<div class="locblock"><span class="icon-location"></span><?php echo $this->address['address']; ?></div>
			<?php endif; ?>
			<!-- Ad views -->
			<div class="advert-views">
			 <?php if($this->config->showviewscount) : ?>
				<div class="viewsblock"><span class="icon-eye"></span><?php echo $item->views; ?>&nbsp;<?php echo JText::_('VIEWS'); ?></div>
			<?php endif; ?>
			<!-- posted date -->
			<?php if($this->config->showdateadded) : ?>
				<div class="dateblock"><span class="icon-calendar"></span><?php echo JomclUtils::getRelativeTime($item->createddate); ?></div>
			<?php endif; ?>
			</div>
		</div>
		<?php
       		 $print_advert = JRoute::_( 'index.php?option=com_jomclassifieds&view=advert&task=downloadAdverts&'.'id='.$item->id .':'.$item->alias.'&Itemid='.$itemId);
        ?>
         <a class="jomcl-btn btn btn-large btn-block btn-primary" href="<?php echo $print_advert; ?>" target="_blank">
        	<span class="icon-printer"></span> <?php echo JText::_('PRINT_ADVERT') ?>
        </a>
	</div>
	<div class="rt-grid-9">
		<div class="detial-right-block">
			<div class="main-head-lines">
            	<div class="main-head-lines_lft">
					<!--<div class="ad_idblock">
					 <span class="icon-lamp"></span><?php echo JText::_('AD_ID'); ?>&nbsp;:&nbsp;<?php echo $item->id; ?>
					</div>-->
					<!-- ad title -->        
					<span class="title"><?php echo $item->title; ?><?php if($this->config->showcatname)	echo ','; ?></span> 
					<!-- ad type -->
						<?php if($this->config->showcatname): ?>
						<span class="catblock">
							<strong>
								<span class="icon-folder-open"></span>
								<a href="<?php echo JRoute::_('index.php?option=com_jomclassifieds&view=category&id='.$item->catid.':'.$item->catalias.'&Itemid='.$itemId); ?>"><?php echo $item->category; ?></a>
							</strong>
						</span>
				 <?php endif; ?>  
				<!-- airport name -->
				<?php if($item->airportname) {  echo ','; ?>
						<span> <?php echo $item->airportname; ?> </span>
				<?php } ?>
				</div>
				<!-- print icons -->
				<div class="social_med_rht">
					<?php echo $this->config->socialwidget; ?>
                </div>
			</div>
			<!--Gallery  Start-->
			<?php echo buildCarousel($item->images,$item); ?>
		</div>
	</div>
	<div class="rt-grid-12">
		<!-- Extrafields -->
		<div class="property-details dtls_info">
			 <div class="dtls_yxt">
				<?php if($tagMarkup): ?>
					<strong>Hangar Type</strong>: <?php echo $tagMarkup.','; ?>
				<?php endif; ?>
				<?php 
					if(count($extrafields) > 0 ) :
					 foreach($extrafields as $extrafield) :	
					 if($extrafield->label =="Advertiser's Name" || $extrafield->label =="Company"){ continue; }
						echo '<strong>'.$extrafield->label.'</strong>'; 
						echo ': ';
						echo $extrafield->value;
						echo ', '; 
					 endforeach;
					endif;
				?>
			</div>
			 <!--ContactAdvertiser-->
			  <?php  if($item->video !='' && $this->config->showvideo > 0 ) {  ?>
				<div class="jomcl-box jomcl-yt">
					<a class="jomcl-popup-trigger" data-jomcl-modal="jomclVideo" href="javascript: void(0)">
					<img class="jomcl-yt-overlay" alt="youtube" src="<?php echo JURI::root(); ?>components/com_jomclassifieds/assets/youtube.png" > 
					<img class="jomcl-yt-thumb"  alt="youtubePlay" src="http://img.youtube.com/vi/<?php echo JomclUtils::getYouTubeVideoId($item->video);?>/hqdefault.jpg" >
				   </a>
				</div>
			  <?php } ?>   
		</div>
		<!-- Description -->
		<div class="advert-descrption">
			 <!--Description Start-->
			  
			  <div class="jomcl-box">
				<h3 class="item-details"><span class="icon-pencil"></span><?php echo JText::_('DESCRIPTION') ?></h3>
				<?php if(strlen($item->description) > 0) {  ?>
					<div class="jomcl-desc muted"> <?php echo $item->description;?> </div>
				<?php } else { ?>
					<div class="jomcl-desc muted"> <strong>No Description</strong> </div>
				<?php } ?>
			  </div>
		</div>
	</div>
</div>



<?php /*
<div class="jomclassifieds <?php echo $this->escape($this->params->get('pageclass_sfx')); ?>" id="jomclassifieds">
<div class="pretext"><?php echo trim($this->params->get('pretext')); ?></div>
  <div class="jomcl-detailview <?php if($item->featured > 0 ) { echo JomclUtils::getSuffixclass(1); } ?>">
   <div class="column-top">
     <div class="jomcl-row1">
        <div class="jomcl-left">
        	<h2 class="title"><?php echo $item->title; ?></h2>
		  	<?php echo $markup; ?>
          	<?php if ( $item->featured > 0 && count($this->buildCarousel($item->images,$item)) == 0 ) { ?>
            	 <span class="label label-default"><i class="icon-star-empty"></i><?php echo $item->prename; ?></span>
          	<?php } ?>
          	<?php echo $tagMarkup; ?>
        </div>
        <?php if($this->config->showprice) : ?>
        	<?php if($price != 'n/a') { ?>
       	 		<div class="jomcl-right"><h3 class="title"><?php echo $price; ?></h1></div>
        	<?php } ?>
        <?php endif; ?>
     </div>
     <!--Loaction,category block Start-->
     <?php if($this->config->showlocname || $this->config->showcatname )  : ?>
     <div class="jomcl-row2">
     	<?php if($this->config->showcatname) : ?>
      		<span class="catblock"><span class="icon-folder-open"></span>
            <a href="<?php echo JRoute::_('index.php?option=com_jomclassifieds&view=category&id='.$item->catid.':'.$item->catalias.'&Itemid='.$itemId); ?>">
			<?php echo $item->category; ?></a>
            </span>
        <?php endif; ?>  
     	<?php if ( ($this->config->showlocname)  && ($this->address['country'] !='')): ?>
     		<span class="locblock"><span class="icon-location"></span><?php echo $this->address['country']; ?></span>
        <?php endif; ?>
        
      </div>
     <?php endif; ?>  
    </div>
   <div class="column-left jomcl-left">
   		<!--Gallery  Start-->
      		<?php echo $this->buildCarousel($item->images,$item); ?>
        <!--Social Share Start--> 
      <div class="jomcl-box jomcl-social">
       	<div class="social-script jomcl-left"><?php echo $this->config->socialwidget; ?></div>
        <div class="social-fav-report jomcl-right">
        <?php if($this->config->allowfav) : ?>
        	<div class="text-warning jomcl-left"><i class="icon-star"></i>
            <a <?php if(!$Favourites->id) { ?> class="jomcl-popup-trigger" data-jomcl-modal="jomclFavourites" <?php } ?> id="favourites" href="javascript: void(0)"><?php echo $this->favourites; ?></a></div>
        <?php endif; ?>
        <?php if($this->config->reportabuse) : ?>
       	 <div class="text-error jomcl-right">
         	<i class="icon-flag"></i><a class="jomcl-popup-trigger" data-jomcl-modal="jomclReport" href="javascript: void(0)"><?php echo JText::_('REPORT_THIS_AD') ?></a>
         </div>
        <?php endif; ?>
        </div> 
      </div>
       <!--Description Start-->
      <?php if(strlen($item->description) > 0) {  ?>
      <div class="jomcl-box">
        <h3 class="item-details"><span class="icon-pencil"></span><?php echo JText::_('DESCRIPTION') ?></h3>
        <div class="jomcl-desc muted"> <?php echo $item->description;?> </div>
      </div>
      <?php } ?>
     <!--Extrafields Start-->
      <?php if(count($extrafields) > 0 ) {  ?>
      <div class="jomcl-box">
        <h3 class="item-details"><span class="icon-pie"></span><?php echo JText::_('MORE_DETAILS') ?></h3>
        <div class="jomcl-extra-fields">
        <table class="table table-striped">
        <thead>        
         <tr>
           <th class="span4 col-md-4"><?php echo JText::_('NAME') ?></th>
           <th class="span8 col-md-8"><?php echo JText::_('DESCRIPTION') ?></th>
         </tr>
         </thead>
         <tbody>              
        <?php 	foreach($extrafields as $extrafield) :	?>
      	<tr>
           <td class="span4 col-md-4"><?php echo $extrafield->label; ?></td>
           <td class="span8 col-md-8"><?php echo $extrafield->value; ?></td>
        </tr>
        <?php endforeach; ?>
       </tbody>
      </table>
        </div>
      </div>
      <?php } ?>
    </div>
    <!--Left column End-->
    <div class="column-right jomcl-right">
      <!--JOmclBox3-->
      <div class="jomcl-box">      
        <h3 class="item-details"><span class="icon-pin"></span><?php echo JText::_('AD_DETAILS') ?></h3>
        <div class="ad_idblock"><span class="icon-lamp"></span><?php echo JText::_('AD_ID'); ?>&nbsp;:&nbsp;<?php echo $item->id; ?></div>        
        <?php if($this->config->showcatname) : ?>
        	<div class="catblock"><span class="icon-folder-open"></span>
            <a href="<?php echo JRoute::_('index.php?option=com_jomclassifieds&view=category&id='.$item->catid.':'.$item->catalias.'&Itemid='.$itemId); ?>">
			<?php echo $item->category; ?></a>
            </div>
        <?php endif; ?>
         <?php if($this->config->showmap && $this->address['address'] != '') : ?>
       		<div class="locblock"><span class="icon-location"></span><?php echo $this->address['address']; ?></div>
        <?php endif; ?>
        <?php if($this->config->showdateadded) : ?>
        	<div class="dateblock"><span class="icon-calendar"></span><?php echo JomclUtils::getRelativeTime($item->createddate); ?></div>
        <?php endif; ?>
        <?php if($this->config->showviewscount) : ?>
       		<div class="viewsblock"><span class="icon-eye"></span><?php echo $item->views; ?>&nbsp;<?php echo JText::_('VIEWS'); ?></div>
        <?php endif; ?>
      </div>
      <!--JOmclBox2-->
      <div class="jomcl-box">
        <h3 class="item-details"><span class="icon-pencil-2"></span><?php echo JText::_('ADVERTISER_DETAILS') ?></h3>
        <?php if($this->config->showusername) : ?>
        	<div class="userblock"><span class="icon-user"></span><?php echo $user->name.'&nbsp;('.$this->getuseradsCount($item->userid).')'; ?></div>
        <?php endif; ?>
        <?php if(!empty($item->phonenumber)) : ?>
        	<div class="phoneblock"><span class="icon-mobile"></span><?php echo $item->phonenumber; ?></div>
        <?php endif; ?>
        <?php if($this->config->showemail && !empty($item->email) ) : ?>
        	<div class="emailblock"><span class="icon-mail-2"></span><?php echo $item->email; ?></div>
        <?php endif; ?> 
        <?php if($this->config->showusername) : ?>       
       		<div class="userlinkblock"><span class="icon-arrow-right-2"></span>
            <a href="<?php echo JRoute::_('index.php?option=com_jomclassifieds&view=userads&id='.$item->userid.':'.$user->username.'&Itemid='.$itemId);?>">
			<?php echo JText::_('SHOW_USER_ADS'); ?></a>
            </div>
        <?php endif; ?>
      </div>
      <!--JOmclBox2-->   
      	<a class="jomcl-popup-trigger jomcl-btn btn btn-large btn-block btn-primary" data-jomcl-modal="jomclContact" href="javascript: void(0)">
        	<span class="icon-plus"></span> <?php echo JText::_('CONTACT_ADVERTISER') ?>
        </a>
      <!--ContactAdvertiser-->
      <?php  if($item->video !='' && $this->config->showvideo > 0 ) {  ?>
      	<div class="jomcl-box jomcl-yt">
        	<a class="jomcl-popup-trigger" data-jomcl-modal="jomclVideo" href="javascript: void(0)">
            <img class="jomcl-yt-overlay" alt="youtube" src="<?php echo JURI::root(); ?>components/com_jomclassifieds/assets/youtube.png" > 
            <img class="jomcl-yt-thumb"  alt="youtubePlay" src="http://img.youtube.com/vi/<?php echo JomclUtils::getYouTubeVideoId($item->video);?>/hqdefault.jpg" >
           </a>
      	</div>
      <?php } ?>
      <?php if($this->config->showmap && $this->address['address'] != '') : ?>
       	<div class="jomcl-box jomcl_map">
        	<?php $this->addScript(); ?>
       		<div id="map_canvas"></div>
       		<input type="hidden" id="latitude" name="latitude" value="<?php echo $item->latitude; ?>" />
        	<input type="hidden" id="langtitude" name="langtitude" value="<?php echo $item->langtitude; ?>" />
        	<input type="hidden" id="defLocation" name="defLocation" value="none"/>       
        	<h3 class="item-details"><span class="icon-compass"></span>
            <a href="http://www.google.com/maps/place/<?php echo trim($this->address['map']);?>" target="_blank"><?php echo JText::_('VIEW_MAP_ON_LARGE'); ?></a>
            </h3>
      </div>
      <?php endif; ?>
    </div>
    <div style="clear:both;"> <?php echo $this->loadTemplate('comments'); ?></div>
  </div>
 <input type="hidden" id="userid" name="userid" value="<?php echo JFactory::getUser()->get('id'); ?>" />
 <input type="hidden" id="advertid" name="advertid" value="<?php echo $item->id; ?>" />
 <div class="clear"></div>
 </div>
  <div class="clearfix"></div> 
  */ ?>
  
<?php if($this->config->showrelatedads) : 
		echo $this->loadTemplate('related');
endif; ?>
<div class="jomcl-popup-container">
	<?php echo $this->loadTemplate('gallery'); ?>
	<?php echo $this->loadTemplate('contact'); ?>
    <?php if($this->config->reportabuse) : ?>
 		<?php echo $this->loadTemplate('report'); ?> 
    <?php endif; ?>
    <?php if($this->config->showvideo) : ?>
 		<?php echo $this->loadTemplate('video'); ?>
    <?php endif; ?> 
    <?php if(!$Favourites->id) { ?>
	<?php echo $this->loadTemplate('favourites'); ?>
    <?php } ?> 	
  	<div class="jomcl-popup-overlay"></div>
</div>
<script>
	jQuery(document).ready(function(){ 
		jQuery('.rt-grid-9').removeClass('rt-push-3');
		jQuery('.rt-grid-3').removeClass('rt-pull-9');
	});
	
	jQuery(window).load(function() {
	  jQuery('.flexslider').flexslider({
		animation: "slide",
		controlNav: "thumbnails"
	  });
	});

</script>