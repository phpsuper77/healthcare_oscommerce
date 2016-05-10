<?php
/*
$Id: manufacturer_info.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

if (isset($HTTP_GET_VARS['products_id'])  && tep_check_product((int)$HTTP_GET_VARS['products_id'])) {
  if (USE_MARKET_PRICES == 'True' || CUSTOMERS_GROUPS_ENABLE == 'True'){
    $manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  left join " . TABLE_PRODUCTS_PRICES . " pp on p.products_id = pp.products_id and pp.groups_id = '" . (int)$customer_groups_id . "' and pp.currencies_id = '" . (USE_MARKET_PRICES == 'True'?$currency_id:'0'). "' where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and if(pp.products_group_price is null, 1, pp.products_group_price != -1 ) and p.manufacturers_id = m.manufacturers_id");
  }else{
    $manufacturer_query = tep_db_query("select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and p.manufacturers_id = m.manufacturers_id");
  }
  if (tep_db_num_rows($manufacturer_query)) {
    $manufacturer = tep_db_fetch_array($manufacturer_query);
?>
<!-- manufacturer_info //-->
          <tr>
            <td class="infoBoxCell">
<?php
if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
  new infoBoxImageHeading($info_box_contents);
}else{
  $info_box_contents = array();
  $info_box_contents[] = array('text'  => BOX_HEADING_MANUFACTURER_INFO );
  $infoboox_class_heading = $infobox_class . 'Heading';
  if (class_exists($infoboox_class_heading)){
    new $infoboox_class_heading($info_box_contents, false, false);
  }else{
    new infoBoxHeading($info_box_contents, false, false);
  }
}

$manufacturer_info_string = '<table border="0" width="100%" cellspacing="0" cellpadding="0">';
if (tep_not_null($manufacturer['manufacturers_image'])) $manufacturer_info_string .= '<tr><td align="center" colspan="2">' . tep_image(DIR_WS_IMAGES . $manufacturer['manufacturers_image'], $manufacturer['manufacturers_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</td></tr>';
if (tep_not_null($manufacturer['manufacturers_url'])) $manufacturer_info_string .= '<tr><td valign="top" >-&nbsp;</td><td valign="top" ><a class="infoBoxLink" href="' . tep_href_link(FILENAME_REDIRECT, 'action=manufacturer&manufacturers_id=' . $manufacturer['manufacturers_id']) . '" target="_blank">' . sprintf(BOX_MANUFACTURER_INFO_HOMEPAGE, $manufacturer['manufacturers_name']) . '</a></td></tr>';
$manufacturer_info_string .= '<tr><td valign="top">-&nbsp;</td><td valign="top" ><a class="infoBoxLink" href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']) . '">' . BOX_MANUFACTURER_INFO_OTHER_PRODUCTS . '</a></td></tr>' .
'</table>';

$info_box_contents = array();
$info_box_contents[] = array('text' => $manufacturer_info_string);

if (class_exists($infobox_class)){
  new $infobox_class($info_box_contents);
}else{
  new infoBox($info_box_contents);
}
?>
            </td>
          </tr>
<!-- manufacturer_info_eof //-->
<?php
  }
}
?>
