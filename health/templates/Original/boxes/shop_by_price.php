<?php
/*
  $Id: shop_by_price.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $
  
  Contribution by Meltus
  http://www.highbarn-consulting.com
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- shop by price //-->
          <tr>
            <td class="infoBoxCell">
<?php
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOP_BY_PRICE);
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_SHOP_BY_PRICE);
    new infoBoxHeading($info_box_contents, false, false);
  }

  $range = split(';', SHOP_BY_PRICE_RANGES);
  $info_box_contents = array();

  for($i=0,$n=sizeof($range);$i<=$n;$i++){
    if ($i == 0){
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i , 'NONSSL') . '">' . TEXT_SHOP_BY_PRICE_UNDER . $currencies->format($range[$i], false) . '</a>'
                                  );
    }elseif ($i == $n){
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i , 'NONSSL') . '">' . TEXT_SHOP_BY_PRICE_OVER . $currencies->format($range[$i - 1], false) . '</a>'
                                  );
    }else{
    $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_SHOP_BY_PRICE, 'range=' . $i , 'NONSSL') . '">' . TEXT_SHOP_BY_PRICE_FROM . $currencies->format($range[$i - 1], false) . ' ' . TEXT_SHOP_BY_PRICE_TO . $currencies->format($range[$i], false) . '</a>'
                                );
    }
  }

  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- shop_by_price //-->
