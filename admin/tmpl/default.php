<?php
/**
 * @version $Id: default.php
 * @package simpleicalblock
 * @subpackage simpleicalblock Module
 * @copyright Copyright (C) 2022 -2022 wsacarousel, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: https://www.waasdorpsoekhan.nl
 * @author email contact@waasdorpsoekhan.nl
 * @developer A.H.C. Waasdorp
 *
 *
 * simpleicalblock is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * simpleicalblock is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WsaCarousel. If not, see <http://www.gnu.org/licenses/>.
 * 0.2.0
 * ook voor eigen javascript 3 wsacarousel
 * 1.0.6 20-2-2022 adjustments for J4
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/* change duration of transformation
 needs a change in  .emulateTransitionEnd(600) in Carousel.prototype.slide = function (type, next)
 otherwise the slide disappears afte 0.6 sec.
 solved for BS4 no change needed anymore.
 */

switch ($params->get('twbs_version',5)) {
    case "3": {
    $carousel_item_left = 'item.left';
    $carousel_item_right = 'item.right';
    $carousel_item_next = 'item.next';
    $carousel_item_prev = 'item.prev';
    $bs_data = 'data-';
	}
	break;
    case "4": {  /* twbs version = 4.3 */
        $carousel_item_left =  $carousel_class .'-item-left';
        $carousel_item_right =  $carousel_class .'-item-right';
        $carousel_item_next =  $carousel_class .'-item-next';
        $carousel_item_prev =  $carousel_class .'-item-prev';
        $bs_data = 'data-';
    }
    break;
    case "5":
    default:  {  
    $carousel_item_left =  $carousel_class .'-item-start';
    $carousel_item_right =  $carousel_class .'-item-end';
    $carousel_item_next =  $carousel_class .'-item-next';
    $carousel_item_prev =  $carousel_class .'-item-prev';
    $bs_data = 'data-bs-';
    }
}
    
$decl = "
#wsacarousel-loader" . $mid . "
{
width:" . $style['sldwidth'] . ";
max-width: 100%;
height: auto;
overflow: hidden;
border: 0;
}
#wsacarousel" . $mid . "
{
position: relative;
width: " . $style['vicnt'] * 100 . "%;
width: calc(" . $style['vicnt'] . " * (100% + " . $style['marginr'] . "));
}
#wsacarousel-container" . $mid . " .".  $carousel_class ."-item-inner { 
position: relative;
width: " . 100/$style['vicnt'] . "%;
float: left;
background-color: " . $ii_bgc . "; 
  height: 0;
  overflow: hidden;
  padding: 0 0 " . (100 /$style['vicnt'])/ $style['aspectratio'] . "% 0 ;
padding-bottom: calc(" . (100 /$style['vicnt'])/ $style['aspectratio'] . "% - " . 1/ $style['aspectratio']  . "*" . $style['marginr'] . ");
}
#wsacarousel-container" . $mid . " .".  $carousel_class ."-item-inner .aspect-ratio-box-inside{ 
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

#wsacarousel" . $mid . " .". $carousel_class ."-control{
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    align-items: center;
    -ms-flex-pack: center;
    justify-content: center;
	width: " . 15/$style['vicnt'] . "%;
	}
#wsacarousel" . $mid . " .".  $carousel_class ."-indicators {
	margin: 0 " . 15/$style['vicnt'] . "% 1rem;
right: 0;
left: 0;
width:auto;
-webkit-box-pack: center;
-ms-flex-pack: center;
justify-content: center;
padding-left: 0;
}	
#wsacarousel" . $mid . " #wsacarouselbottom" . $mid  ." {
position: relative;
bottom:0;
margin:0 calc(100% - " . 100/$style['vicnt'] . "%) 0 0;
margin:0 calc(100% - " . 100/$style['vicnt'] . "% + " . $style['marginr'] . ") 0 0;
z-index: 2;
}
.indicator-numbers .wsanr". $mid . " {
background-color: transparent;
width: 30px;
height: auto;
margin: 0 3px;
text-indent: 0px;
text-align: center;
}
#wsacarouselbottom" . $mid . ".indicator-numbers .wsanr". $mid . ".active {
opacity: 1;
}
#wsacarouselbottom" . $mid . ".indicator-numbers  li[data-target] {
border: 0;
border-top: 10px solid transparent;
border-bottom: 10px solid transparent;
opacity: 0.5;
transition: opacity 0.6s ease;
}
#wsacarousel" . $mid . " .".  $carousel_class ."-control-next,
#wsacarousel" . $mid . " .".  $carousel_class ."-indicators {
right:  calc(100% - " . 100/$style['vicnt'] . "% + " . $style['marginr'] . ");
}
#wsacarousel-loader" . $mid . " .showBothOnHover,
#wsacarousel-loader" . $mid . " .showOnHover {
	opacity: 0;
	-webkit-transition: opacity 200ms ease 50ms;
	transition: opacity 200ms ease 50ms;
}
#wsacarousel-loader" . $mid . " .wsashow,
#wsacarousel-loader" . $mid . ":hover .showBothOnHover,
#wsacarousel-loader" . $mid . " .showOnHover:hover,
#wsacarousel-loader" . $mid . " .showOnHover.focused  {
	outline: 0;
	opacity: 0.9;
}
.".  $carousel_class ."-control-prev {
  background-image:         linear-gradient(to right, rgba(0, 0, 0, .1) 0%, rgba(0, 0, 0, .0001) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#80000000', endColorstr='#00000000', GradientType=1);
  background-repeat: repeat-x;
}
.".  $carousel_class ."-control-next {
  background-image:         linear-gradient(to right, rgba(0, 0, 0, .0001) 0%, rgba(0, 0, 0, .1) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#80000000', GradientType=1);
  background-repeat: repeat-x;
}
.play-pause {
position: absolute;
left: " . 50/$style['vicnt'] . "%;
left: calc(" . 50/$style['vicnt'] . "% - 0.5*" . $style['marginr'] . ");  
top: 50%;
margin-top: -20px;
margin-left: -20px;
height: 40px;
width: 40px;
} 
.play-pause img{
width: 100%;
height: 100%; 
}
#play"  . $mid . " {
display:none;
}	
#wsacarousel-container" . $mid . "  .".  $carousel_class ."-inner .".  $carousel_class ."-caption{
position: " .  $caption_overlay . ";
bottom: 0;
padding:0;
left: 0;
right: 0;
right:  calc(" . $style['marginr'] . ");
font-size: 12px;
line-height: 15.6px;
background-color: RGBA(" . $caption_rgba .");
color: " . $caption_fgc .";
}
.".  $carousel_class ."-caption {
color: #fff;
text-align: center;
}
#wsacarousel-container" . $mid . " .".  $carousel_class ."-item-content{
float: left;
margin-bottom: " . $style['marginb'] . ";
width: 100%;
width:  calc(100% - " . $style['marginr'] . ");
height: " . $style['sldheight'] . "; 
aspect-ratio: " . $style['aspectratio']  . ";
overflow: hidden;
}
#wsacarousel-container" . $mid . " .".  $carousel_class ."-item-img{
" . $style['image'] . "
}
    
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner > .item {
    -webkit-transition-duration: " . $duration/1000 . "s;
    -moz-transition-duration: " . $duration/1000 . "s;
    -o-transition-duration: " . $duration/1000 . "s;
    transition-duration: " . $duration/1000 . "s;
}
@media (min-width: 768px) {
#wsacarousel-loader" . $mid . "
{
width:" . $style['slrwidth'] . ";
}
#wsacarousel" . $mid . "
{
width: 100%;
width: calc(100% + " . $style['marginr'] . ");
}
#wsacarousel" . $mid . " .". $carousel_class ."-control{
	width: 15%;
}
#wsacarousel" . $mid . " .".  $carousel_class ."-indicators {
margin: 0 15% 1rem;
}
#wsacarousel" . $mid . " .".  $carousel_class ."-control-next,
#wsacarousel" . $mid . " .".  $carousel_class ."-indicators {
right:  calc(" . $style['marginr'] . ");
}
#wsacarousel" . $mid . " #wsacarouselbottom" . $mid  ." {
margin-right: calc(" . $style['marginr'] . ");
}
.play-pause {
position: absolute;
left: 50%;
left: calc(50% - 0.5*" . $style['marginr'] . ");  
top: 50%;
}
}
";
if ($style['vicnt'] > 1) {
    
    $decl = $decl .
    "
/* override position and transform in 3.3.x and 4.3.x and 5.1.x
*/
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner ." . $carousel_item_prev . ",
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner ." . $carousel_item_left . ".active {
  transform: translateX(-" . 100/$style['vicnt'] . "%);
}
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner ." . $carousel_item_next . ",
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner ." . $carousel_item_right . ".active {
  transform: translateX(" . 100/$style['vicnt'] . "%);
}
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner ." . $carousel_item_left . ",
#wsacarousel-container" . $mid . " .".  $carousel_class ."-inner ." . $carousel_item_right . " {
  transform: translateX(0);
}";
}
if ($joomlaverge4) { // J4 code stylesheets and javascript addStyleSheet etc for J4
    $wa->addInlineStyle($decl, [], [],[]);
}
else {
$document->addStyleDeclaration($decl);
}
if($show_buttons) { // javascript for play_pause
/* work without jQuery in BS5 */
if ($params->get('twbs_version',4) == 5 ) {
	$decl = "
document.addEventListener('DOMContentLoaded', function() {
    var playbtn" . $mid ." = document.getElementById('play" . $mid ."');
    var pausebtn" . $mid ." = document.getElementById('pause" . $mid ."');
    var element" . $mid ." = document.getElementById('wsacarousel-container". $mid ."');
    pausebtn" . $mid .".addEventListener('click', function() {
        var myCarousel" . $mid ." = ". $js_mainobject . ".Carousel.getInstance(element" . $mid .");
        myCarousel" . $mid .".pause();
        playbtn" . $mid .".style.display = 'inline';
        pausebtn" . $mid .".style.display = 'none';
    });
    playbtn" . $mid .".addEventListener('click', function() {
        var myCarousel" . $mid ." = ". $js_mainobject . ".Carousel.getInstance(element" . $mid .");
        myCarousel" . $mid .".cycle();
        playbtn" . $mid .".style.display = 'none';
        pausebtn" . $mid .".style.display = 'inline';
    });
});	
";
} else 
	
    $decl = 
    "
jQuery(document).ready(function() {
jQuery('#pause"  . $mid . "').click(function() {
jQuery('#pause"  . $mid . ",#play"  . $mid . "').toggle();	
jQuery('#wsacarousel-container"  . $mid . "').".  $carousel_class ."('pause');
});
jQuery('#play"  . $mid . "').click(function() {
jQuery('#play"  . $mid . ",#pause"  . $mid . "').toggle();	
jQuery('#wsacarousel-container"  . $mid . "').".  $carousel_class ."('cycle');
});
})
";
if ($joomlaverge4) { // J4 code stylesheets and javascript addStyleSheet etc for J4
    $wa->addInlineScript($decl, [], [],['jquery']);
}
else {
    $document->addScriptDeclaration($decl);
}
} // end javascript for play_pause.
?>

<div id="wsacarousel-loader<?php echo $mid; ?>" class="wsacarousel<?php echo $params->get('moduleclass_sfx') ?> wsacarousel-loader"  tabindex="0">
	<div id="wsacarousel<?php echo $mid; ?>" class="wsacarousel-box">
		<!-- Container with data-options (animation and wsa-carousel only for info) -->
        <div id="wsacarousel-container<?php echo $mid; ?>" class="<?php echo $carousel_class; ?> slide " 
        <?php echo $bs_data; ?>ride="<?php echo $carousel_class; ?>"
        <?php echo $bs_data; ?>interval="<?php echo $interval ; ?>" 
		<?php echo $bs_data; ?>pause="hover"
		<?php echo $bs_data; ?>wrap="<?php echo $wrap; ?>"
		<?php echo $bs_data; ?>keyboard="true"
		<?php echo $bs_data; ?>duration="<?php echo $duration; ?>"
		>
		<!-- Indicators -->
		<?php if($show_idx && !$idx_style) { ?>
         <ol class="<?php echo $carousel_class; ?>-indicators <?php echo (1==$show_idx)?' showOnHover':' wsashow';?>" >
		<?php $itemnr = 0; 
			 foreach ($slides as $slide) { /* per slide */
					$itemnr++;  ?>
            <li <?php echo $bs_data; ?>target="#wsacarousel-container<?php echo $mid; ?>" <?php echo $bs_data; ?>slide-to="<?php echo $itemnr - 1;?>" class="<?php if ($itemnr==1) echo 'active'; ?>" ></li>
        <?php } /* end per slide */ ?> 
         </ol>
        <?php } /* end Indicators */ ?> 
			<!-- Wrapper for slides -->
        	<div id="wsacarousel-inner<?php echo $mid; ?>" class="<?php echo $carousel_class; ?>-inner"   role="listbox">
			<?php $itemnr = 0; 
			 foreach ($slides as $slide) { /* frame per slide  */
					$itemnr++; ?>
          			<div class="<?php echo $carousel_class; ?>-item item item<?php echo $itemnr; if ($itemnr==1) echo " active"; ?>" <?php if($slide->delay > 0) echo $bs_data .'interval="' . $slide->delay  . '" '; ?>>
          		<?php for ($seq = 0; ($seq < $style['vicnt']); $seq++) { /* slides in frame */
          		    $slide = $slides[($itemnr + $seq -1) % $slidecnt];
          		    $rel = (!empty($slide->rel) ? 'rel="'.$slide->rel.'"':''); ?>
          		    <div class="<?php echo $carousel_class; ?>-item-inner seq<?php echo $seq; ?>">
          		        <div class="aspect-ratio-box-inside" > 
              			    <div class="<?php echo $carousel_class; ?>-item-content">
              				<?php if($slide->image) { 
              					$action = $link_image;
              					if($action > 1) {
    								$desc = ($show_desc) ? 'title="'.(!empty($slide->title) ? htmlspecialchars($slide->title.' ') : '').(!empty($slide->description) ? htmlspecialchars('<small>'.strip_tags($slide->description,"<p><a><b><strong><em><i><u>").'</small>') : '').'"':'';
    	          						$attr = 'class="image-link" data-wsmodal="true" data-'.$desc;
    							} else {
    								$attr = $rel;
    							}
              					?>
    	            			<?php if (($slide->link && $action==1) || $action>1) { ?>
    								<a <?php echo $attr; ?> href="<?php echo ($action>1 ? $slide->image : $slide->link); ?>" target="<?php echo $slide->target; ?>">
    							<?php } ?>
    								<img class="<?php echo $carousel_class; ?>-item-img" src="<?php echo $slide->image; ?>" alt="<?php echo $slide->alt; ?>" <?php echo (!empty($slide->img_title) ? ' title="'.$slide->img_title.'"':''); ?>"/>
    							<?php if (($slide->link && $action==1) || $action>1) { ?>
    								</a>
    							<?php } ?>
    						<?php } ?>
    						<?php if ($params->get('slider_source') && ($params->get('show_title') || $show_desc && !empty($slide->description) || $show_readmore && $slide->link)) { ?>
        						<!-- Slide description area: START -->
        						<div class="<?php echo $carousel_class; ?>-caption" >
        							<?php if($params->get('show_title')) { ?>
        							<div class="slide-title">
        							<?php if ($link_title && $slide->link) { ?><a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>" <?php echo $rel; ?>><?php } ?>
        										<?php echo $slide->title; ?>
        									<?php if($link_title && $slide->link) { ?></a><?php } ?>
        							</div>
        							<?php } ?>
        							
        							<?php if ($show_desc) { ?>
        							<div class="slide-text">
        									<?php if ($link_desc && $slide->link) { ?>
        									<a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>" <?php echo $rel; ?>>
        										<?php echo strip_tags($slide->description,"<br><span><em><i><b><strong><small><big>"); ?>
        									</a>
        									<?php } else { ?>
        										<?php echo $slide->description; ?>
        									<?php } ?>
        							</div>
        							<?php } ?>
        							
        							<?php if($show_readmore && $slide->link) { ?>
        								<a href="<?php echo $slide->link; ?>" target="<?php echo $slide->target; ?>" <?php echo $rel; ?> class="readmore"><?php echo $readmore_text ; ?></a>
        							<?php } ?>
        						</div>
        						<!-- Slide description area: END -->
    						<?php } ?>						
    						</div>
						</div>
					</div>
					<?php } ?>
				</div><!-- end slide-frame -->
                <?php } ?>
        	</div>
           <?php if($show_idx && (1 == $idx_style)) { ?>
        	<ol id="wsacarouselbottom<?php echo $mid; ?>" class="<?php echo $carousel_class; ?>-indicators indicator-numbers <?php echo (1==$show_idx) ? 'showOnHover':'wsashow' ?>">
        		<?php $itemnr = 0; foreach ($slides as $slide) { $itemnr++; ?>
        		<li <?php echo $bs_data; ?>target="#wsacarousel-container<?php echo $mid; ?>" <?php echo $bs_data; ?>slide-to="<?php echo $itemnr - 1;?>" class="wsanr<?php echo $mid; if ($itemnr == 1) echo ' active'; ?>" role="button"  tabindex="0"><?php  echo $itemnr; ?></li>
        		<?php } ?>
            </ol>
            <?php } ?>
       </div>
        <?php if($show_arrows ) { ?>
        <div id="navigation<?php echo $mid; ?>" class="navigation-container">
			<a class="left <?php echo $carousel_class; ?>-control <?php echo $carousel_class; ?>-control-prev <?php echo (1==$show_arrows) ? 'showOnHover':((3==$show_arrows) ? 'showBothOnHover' : 'wsashow' ) ?>" href="#wsacarousel-container<?php echo $mid; ?>" <?php echo $bs_data; ?>target="#wsacarousel-container<?php echo $mid; ?>"  role="button" <?php echo $bs_data; ?>slide="prev" >
        	<?php if($navigation->nav_buttons_style) { ?>
        	<img id="prev<?php echo $mid; ?>" class="prev-button " src="<?php echo $navigation->prev; ?>" alt="<?php echo $direction == 'rtl' ? Text::_('MOD_WSACAROUSEL_NEXT') : Text::_('MOD_WSACAROUSEL_PREVIOUS'); ?>" tabindex="0" />
        	<?php } else { ?>
        	<span class="<?php echo $carousel_class; ?>-control-prev-icon" aria-hidden="true"></span>
			<?php } ?>
			</a>
			<a class="right <?php echo $carousel_class; ?>-control <?php echo $carousel_class; ?>-control-next <?php echo (1==$show_arrows) ? 'showOnHover':((3==$show_arrows) ? 'showBothOnHover' : 'wsashow' ) ?>" href="#wsacarousel-container<?php echo $mid; ?>" <?php echo $bs_data; ?>target="#wsacarousel-container<?php echo $mid; ?>"  role="button" <?php echo $bs_data; ?>slide="next" >			
        	<?php if($navigation->nav_buttons_style) { ?>
			<img id="next<?php echo $mid; ?>" class="next-button " src="<?php echo $navigation->next; ?>" alt="<?php echo $direction == 'rtl' ? Text::_('MOD_WSACAROUSEL_PREVIOUS') : Text::_('MOD_WSACAROUSEL_NEXT'); ?>" tabindex="0" />
        	<?php } else { ?>
        	<span class="<?php echo $carousel_class; ?>-control-next-icon" aria-hidden="true"></span>
			<?php } ?>
			</a>
        </div>
        <?php } ?>
    	<?php  if($show_buttons) { ?>
    	<div class="play-pause <?php echo (1==$show_buttons) ? 'showOnHover':((3==$show_buttons) ? 'showBothOnHover' : 'wsashow' ); ?>" >
        	<img id="play<?php echo $mid; ?>" class="play-button "  role="button"
        	  src="<?php echo $navigation->play; ?>" alt="<?php echo Text::_('MOD_WSACAROUSEL_PLAY'); ?>" tabindex="0" >
        	<img id="pause<?php echo $mid; ?>" class="pause-button "  role="button"
        	 src="<?php echo $navigation->pause; ?>" alt="<?php echo Text::_('MOD_WSACAROUSEL_PAUSE'); ?>" tabindex="0" >
        </div>	 
    	<?php }  ?>
    </div>
</div>

<div class="wsa<?php echo $carousel_class; ?>-end" style="clear: both" tabindex="0"></div>
