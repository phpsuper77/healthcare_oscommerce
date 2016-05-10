<?php
/*
  $Id: left_banners.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- information //-->
<?php
$bannersPool = array();
foreach( array('left01','left02','left03','left04') as $bGroupName ) {
  $banner = tep_banner_exists('dynamic', $bGroupName);
  if ( $banner ) {
    $tmpText = tep_display_banner('static', $banner);
    if ( $request_type=='SSL' && preg_match('/src="?http:/',$tmpText) ) continue;
    $bannersPool[] = $tmpText; 
  }
}

if ( count($bannersPool)>0 ){
?>
<tr>
   <td align="center" class="infoBoxCell">
<?php
	$info_box_contents = array();
  foreach( $bannersPool as $bannerText ) {
    $info_box_contents[] = array('params' => 'align=center', 'text' =>  $bannerText);
  }
  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }  
?>
</td>
</tr>
<?php
}
?>				
<!-- information_eof //-->
