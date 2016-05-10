<link rel="stylesheet" href="/templates/Original/css/index.css" type="text/css">
<div class="flexslider">
	<ul class="slides">
		<li>
			<a href="/Pulse_Oximeters"><img src="/templates/images/homepage/slide1.jpg" /></a>
	    </li>
		<li>
			<a href="/Water_Jet_Irrigators"><img src="/templates/images/homepage/slide2.jpg" /></a>
	    </li>
		<li>
			<a href="/?cPath=57&sort=2a&filter_id=77"><img src="/templates/images/homepage/slide3.jpg" /></a>
	    </li>
		<li>
			<a href="/info/Postage+Costs+and+Info.html"><img src="/templates/images/homepage/slide4.jpg" /></a>
	    </li>
		<li>
			<a href="/info/Postage+Costs+and+Info.html"><img src="/templates/images/homepage/slide5.jpg" /></a>
	    </li>		
	</ul>
</div>


    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
if (false){
?>
      <tr>
        <td>
<?php
   if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (tep_not_null($category['categories_heading_title'])) ) {
     $str = $category['categories_heading_title'];
   } else {
     $str = HEADING_TITLE;
   }
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' => $str), array('params'=> 'align=right', 'text' => tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT)));
  new contentPageHeading($infobox_contents);
?>         
            </td>
      </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?>       
<?php
}
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
if (SHOW_CUSTOMER_GREETING == 'yes'){
?>
          <tr>
            <td class="main"><?php echo tep_customer_greeting(); ?></td>
          </tr>
<?php
  if (CELLPADDING_SUB < 5){
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  }
?> 
<?php
}
?>
<?php
if (file_exists(DIR_WS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/' . $template_name)) {
  $modules_folder = (DIR_WS_TEMPLATES .TEMPLATE_NAME . '/mainpage_modules/' . $template_name);
}else{
  $modules_folder = DIR_WS_MODULES. '/mainpage_modules/';
}

if (tep_not_null(INCLUDE_MODULE_ONE) && is_file($modules_folder . INCLUDE_MODULE_ONE)) {
  echo '<tr><td class="main">';
  include($modules_folder . INCLUDE_MODULE_ONE);
  echo '</td></tr>';
  echo '<tr><td>'. tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';
}
?>
          
<?php
if (tep_not_null(INCLUDE_MODULE_TWO) && is_file($modules_folder . INCLUDE_MODULE_TWO)) {
  echo '<tr><td class="main">';
  include($modules_folder . INCLUDE_MODULE_TWO);
  echo '</td></tr>';
  echo '<tr><td>'. tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';
}
?>
<?php
if (tep_not_null(INCLUDE_MODULE_THREE)  && is_file($modules_folder . INCLUDE_MODULE_THREE)) {
  echo '<tr><td class="main">';
  include($modules_folder . INCLUDE_MODULE_THREE);
  echo '</td></tr>';
  echo '<tr><td>'. tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';
}
?>
<?php
if (tep_not_null(INCLUDE_MODULE_FOUR)  && is_file($modules_folder . INCLUDE_MODULE_FOUR)) {
  echo '<tr><td class="main">';
  include($modules_folder . INCLUDE_MODULE_FOUR);
  echo '</td></tr>';
  echo '<tr><td>'. tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';

}
?>
<?php
if (tep_not_null(INCLUDE_MODULE_FIVE  && is_file($modules_folder . INCLUDE_MODULE_FIVE))) {
  echo '<tr><td class="main">';
  include($modules_folder . INCLUDE_MODULE_FIVE);
  echo '</td></tr>';
  echo '<tr><td>'. tep_draw_separator('pixel_trans.gif', '100%', '10') .'</td></tr>';
}
?>
<?php
if (tep_not_null(INCLUDE_MODULE_SIX)  && is_file($modules_folder . INCLUDE_MODULE_SIX)) {
  echo '<tr><td class="main">';
  include($modules_folder . INCLUDE_MODULE_SIX);
  echo '</td></tr>';
}
?>
        </table></td>
      </tr>
    </table>
	
<? include ("templates/java/static/index.phtml"); ?>	