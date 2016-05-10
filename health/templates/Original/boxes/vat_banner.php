<?php
/*
  $Id: right_banners.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
<?php
if (tep_banner_exists('dynamic', 'vat_right_')){// || tep_banner_exists('dynamic', 'right02') || tep_banner_exists('dynamic', 'right03') || tep_banner_exists('dynamic', 'right04')){
?>
<tr>
   <td align="center">
<?php
	$info_box_contents = array();

  if ($banner = tep_banner_exists('dynamic', 'vat_right_')) {
	  $info_box_contents[] = array('params' => 'align=center', 'text' =>  tep_display_banner('static', $banner));
  }

  /*if ($banner = tep_banner_exists('dynamic', 'right02')) {
	  $info_box_contents[] = array('params' => 'align=center', 'text' =>  tep_display_banner('static', $banner));
	}
  if ($banner = tep_banner_exists('dynamic', 'right03')) {
		$info_box_contents[] = array('params' => 'align=center', 'text' =>  tep_display_banner('static', $banner));
	}
  if ($banner = tep_banner_exists('dynamic', 'right04')) {
	  $info_box_contents[] = array('params' => 'align=center', 'text' =>  tep_display_banner('static', $banner));
  }*/
  /*if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{*/
    new infoBoxBanner($info_box_contents);
  //}
?>
</td>
</tr>
<?php
}
?>				
<!-- information_eof //-->
