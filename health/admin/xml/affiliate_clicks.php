<?php
/*
  $Id: affiliate_clicks.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($HTTP_GET_VARS['acID'] > 0) {
    $affiliate_clickthroughs_raw = "select ac.*, pd.products_name, a.affiliate_firstname, a.affiliate_lastname from " . TABLE_AFFILIATE_CLICKTHROUGHS . " ac left join " . TABLE_PRODUCTS . " p on (p.products_id = ac.affiliate_products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "') left join " . TABLE_AFFILIATE . " a  on (a.affiliate_id = ac.affiliate_id) where a.affiliate_id = '" . $HTTP_GET_VARS['acID'] . "'  " . (tep_session_is_registered('login_affiliate')?" and ac.affiliate_id = '" . $login_id."'":''). "  ORDER BY ac.affiliate_clientdate desc";
//	"select * from " . TABLE_AFFILIATE_CLICKTHROUGHS . " where affiliate_id ='" . $HTTP_GET_VARS['acID'] . "' order by date desc";
    $affiliate_clickthroughs_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_clickthroughs_raw, $affiliate_clickthroughs_numrows);
  } else {
    $affiliate_clickthroughs_raw = "select ac.*, pd.products_name, a.affiliate_firstname, a.affiliate_lastname from " . TABLE_AFFILIATE_CLICKTHROUGHS . " ac left join " . TABLE_PRODUCTS . " p on (p.products_id = ac.affiliate_products_id) left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (pd.products_id = p.products_id and pd.language_id = '" . $languages_id . "') left join " . TABLE_AFFILIATE . " a  on (a.affiliate_id = ac.affiliate_id)  " . (tep_session_is_registered('login_affiliate')?" where ac.affiliate_id = '" . $login_id."'":''). "  ORDER BY ac.affiliate_clientdate desc";
    $affiliate_clickthroughs_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_clickthroughs_raw, $affiliate_clickthroughs_numrows);
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_AFFILIATE;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=affiliate');
  $header_title_submenu=HEADING_TITLE;
?>
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/left_separator.gif" width="<?php echo BOX_WIDTH; ?>" valign="top" height="100%" valign=top>
    <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" height="100%" valign=top>
       <tr>
        <td width=100% height=25 colspan=2>
          <table border="0" width="100%" cellspacing="0" cellpadding="0" background="images/infobox/header_bg.gif">
            <tr>
              <td width="28"><img src="images/l_left_orange.gif" width="28" height="25" alt="" border="0"></td>
              <td background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
            </tr>
          </table>
        </td>
      </tr>
      </tr>
      <tr>
        <td valign=top>
          <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="0" valign=top>
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
          </table>
        </td>
        <td width=1 background="images/line_nav.gif"><img src="images/line_nav.gif"></td>
      </tr>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td height="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFFILIATE_USERNAME .'/' . TABLE_HEADING_IPADDRESS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ENTRY_DATE .'/' . TABLE_HEADING_REFERRAL_URL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CLICKED_PRODUCT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_BROWSER; ?></td>
              </tr>
<?php
  if ($affiliate_clickthroughs_numrows > 0) {
    $affiliate_clickthroughs_values = tep_db_query($affiliate_clickthroughs_raw);
    $number_of_clickthroughs = '0';
    while ($affiliate_clickthroughs = tep_db_fetch_array($affiliate_clickthroughs_values)) {
      $number_of_clickthroughs++;

      if ( ($number_of_clickthroughs / 2) == floor($number_of_clickthroughs / 2) ) {
        echo '                  <tr class="productListing-even">';
      } else {
        echo '                  <tr class="productListing-odd">';
      }
?>
                <td class="dataTableContent"><?php echo $affiliate_clickthroughs['affiliate_firstname'] . " " . $affiliate_clickthroughs['affiliate_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo tep_date_short($affiliate_clickthroughs['affiliate_clientdate']); ?></td>
<?php
      if ($affiliate_clickthroughs['affiliate_products_id'] > 0) $link_to = '<a href="' . tep_catalog_href_link(FILENAME_CATALOG_PRODUCT_INFO, 'products_id=' . $affiliate_clickthroughs['affiliate_products_id']) . '" target="_blank">' . $affiliate_clickthroughs['products_name'] . '</a>';
      else $link_to = "Startpage";
?>
                <td class="dataTableContent"><?php echo $link_to; ?></td>
                <td class="dataTableContent" align="center"><?php echo $affiliate_clickthroughs['affiliate_clientbrowser']; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent"><?php echo $affiliate_clickthroughs['affiliate_clientip']; ?></td>
                <td class="dataTableContent" colspan="3"><?php  echo $affiliate_clickthroughs['affiliate_clientreferer']; ?></td>
              </tr>
              <tr>
                <td class="dataTableContent" colspan="4"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
              </tr>
<?php
    }
  } else {
?>
              <tr class="productListing-odd">
                <td colspan="7" class="smallText"><?php echo TEXT_NO_CLICKS; ?></td>
              </tr>
<?php
  }
?>
              <tr>
                <td class="smallText" colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_clickthroughs_split->display_count($affiliate_clickthroughs_numrows,  MAX_DISPLAY_SEARCH_RESULTS, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_CLICKS); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_clickthroughs_split->display_links($affiliate_clickthroughs_numrows,  MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <?php 
  if ($HTTP_GET_VARS['acID'] > 0) {
?>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_STATISTICS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  } else {
?>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<?php
  }
?>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');?>
