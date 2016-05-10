<?php
/*
  $Id: index.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

	$template_id_select_query = tep_db_query("select template_id from " . TABLE_TEMPLATE . "  where template_name = '" . DEFAULT_TEMPLATE . "'");
$template_id_select =  tep_db_fetch_array($template_id_select_query);

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<center>
<!-- Header -->    
<table cellspacing=0 cellpadding=0 width=772 height="26" border=0 class="top">
  <tr>
    <td align="right" class="smalltext"><?php echo TEXT_CURRENT_TIME;?>: <?php echo strftime(DATE_TIME_FORMAT_LONG);?>
    <!--Thu June 1 19:04:10 2004-->&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>
<table cellspacing=0 cellpadding=0 width=772 border=0>
  <tr>
    <td><?=tep_image(DIR_WS_IMAGES."page_top_left.jpg","","309","108")?></td>
    <td><?=tep_image(DIR_WS_IMAGES."page_top_right.jpg","","463","108")?></td>
  </tr>
</table>
<table cellspacing=0 cellpadding=0 width=772 height="29" border=0 class="content" background="<?=DIR_WS_IMAGES?>top_nav_bg.jpg">
  <tr>
    <td width="51"><img src="images/spacer.gif" width="51" height="1" alt="" border="0"></td>
    <td class=headerbarcontent>
      <a class=headerlink href="http://www.holbi.co.uk"><?=HEADER_TITLE_SUPPORT_SITE?></a>&nbsp;|&nbsp;
      <a class=headerlink href="<?=tep_catalog_href_link()?>"><?=HEADER_TITLE_ONLINE_CATALOG?></a>&nbsp;|&nbsp;
      <a class=headerlink href="<?=tep_href_link(FILENAME_LOGOFF, '', 'NONSSL')?>"><?=HEADER_TITLE_LOGOFF?></a>&nbsp;&nbsp;</td>
    <td width=100 align="center">
    <?php echo tep_draw_form('languages', 'index.php', '', 'get'); ?>
    <?php echo tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); ?></td>
    </form>
    </td>
  </tr>
  <tr>
    <td colspan="3" bgcolor="#D9D9D9"><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
  </tr>
</table>
<!-- Header_oef -->
<!-- Content --> 
<table cellspacing=0 cellpadding=0 width=772 border=0 class="content" background="<?=DIR_WS_IMAGES?>page_bg.gif">
  <tr valign="top">
    <td width="249">
      <table cellspacing=0 cellpadding=0 width=249 border=0>
         <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => 'Holbi'/*,'link'=>'http://www.oscommerce.com'*/);
  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.holbi.co.uk/services/oscommerce-support-service/" target="_blank">' . BOX_ENTRY_SUPPORT_SITE . '</a>');
//  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.oscommerce.com/community.php/forum" target="_blank">' . BOX_ENTRY_SUPPORT_FORUMS . '</a>');
  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.oscommerce.com/community.php/mlists" target="_blank">' . BOX_ENTRY_MAILING_LISTS . '</a>');
  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.oscommerce.com/community.php/bugs" target="_blank">' . BOX_ENTRY_BUG_REPORTS . '</a>');
  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.holbi.co.uk/services/oscommerce-tutorials/" target="_blank">' . BOX_ENTRY_FAQ . '</a>');
//  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.oscommerce.com/community.php/irc" target="_blank">' . BOX_ENTRY_LIVE_DISCUSSIONS . '</a>');
//  $contents[] = array('text'  => '<a class="BoxContent" href="http://www.oscommerce.com/community.php/cvs" target="_blank">' . BOX_ENTRY_CVS_REPOSITORY . '</a>');
  $contents[] = array('text'  => '<a  class="BoxContent" href="http://www.holbi.co.uk/" target="_blank">' . BOX_ENTRY_INFORMATION_PORTAL . '</a>');
  $box = new box;
  echo $box->menuBoxIndex($heading, $contents);

?>
            </td>
          </tr>
          <tr>
            <td>
<?

  $heading = array();
  $contents = array();

  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    if ($login_affiliate == 1){
      $sql = "select count(*) as count from " . TABLE_ORDERS . " o, " . TABLE_AFFILIATE_SALES . " ase  where o.orders_id = ase.affiliate_orders_id and affiliate_id = '" . $login_id . "' and orders_status = '" . $orders_status['orders_status_id'] . "'";
    }else{
      $sql = "select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'";
    }
    $orders_pending_query = tep_db_query($sql);
    $orders_pending = tep_db_fetch_array($orders_pending_query);
//Admin begin
//    $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
    if (tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes') == true) { 
      $contents[] = array('text'  =>  '<a class="BoxContent" href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count']);
    } else {
       $contents[] = array('text'  =>  $orders_status['orders_status_name'] . ': ' . $orders_pending['count']);
    }
//Admin end
  }
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_ORDERS);

  $box = new box;
  echo $box->menuBoxIndex($heading, $contents);
?>
            </td>
          </tr>
          <tr>
            <td>
<?
  $customers_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . ($login_affiliate==1?" where affiliate_id = '" . $login_id. "'":''));
  $customers = tep_db_fetch_array($customers_query);
  $products_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");
  $products = tep_db_fetch_array($products_query);
  $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . ($login_affiliate == 1?" r left join " . TABLE_CUSTOMERS . " c on r.customers_id = c.customers_id where c.affiliate_id = '" . $login_id . "'":''));
  $reviews = tep_db_fetch_array($reviews_query);

  $heading = array();
  $contents = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_TITLE_STATISTICS);
  $contents[] = array('text'  => BOX_ENTRY_CUSTOMERS . ' ' . $customers['count']);
  $contents[] = array('text'  => BOX_ENTRY_PRODUCTS . ' ' . $products['count']);
  $contents[] = array('text'  => BOX_ENTRY_REVIEWS . ' ' . $reviews['count']);
  $box = new box;
  echo $box->menuBoxIndex($heading, $contents);


?>
            </td>
          </tr>
          <tr>
            <td>
<?
  $contents = array();

  if (getenv('HTTPS') == 'on') {
    $size = ((getenv('SSL_CIPHER_ALGKEYSIZE')) ? getenv('SSL_CIPHER_ALGKEYSIZE') . '-bit' : '<i>' . BOX_CONNECTION_UNKNOWN . '</i>');
    $contents[] = array('params' => 'class="menuBoxHeading"',
                        'text' => tep_image(DIR_WS_ICONS . 'locked.gif', ICON_LOCKED, '', '', 'align="right"') . sprintf(BOX_CONNECTION_PROTECTED, $size));
  } else {
    $contents[] = array('params' => 'class="menuBoxHeading"',
                        'text' => tep_image(DIR_WS_ICONS . 'unlocked.gif', ICON_UNLOCKED, '', '', 'align="right"') . BOX_CONNECTION_UNPROTECTED);
  }
  $heading='';
  $box = new box;
  echo $box->menuBoxIndex($contents,"");
?>
            </td>
          </tr>
          <tr>
            <td><img src="images/spacer.gif" width="1" height="100" alt="" border="0"></td>
          </tr>
        </table>
      </td>
      <td bgcolor="#D9D9D9"><?=tep_draw_separator("spacer.gif","1","1")?></td>
      <td width="100%">
        <table cellspacing=0 cellpadding=0 width=100% border=0 background="<?=DIR_WS_IMAGES?>contentbox/content_bg.gif">
          <tr>
            <td><?=tep_image(DIR_WS_IMAGES."contentbox/content_left.gif","","8","30");?></td>
          </tr>
        </table>
        <table cellspacing=0 cellpadding=0 width=100% border=0> 
          <tr>
            <td width="20"><?=tep_draw_separator("spacer.gif","20","1")?></td>
            <td>

<!-- Content box  Everyday activities-->
<?php
  if(tep_admin_check_boxes('customers.php') || tep_admin_check_boxes('reports.php') || tep_admin_check_boxes('affiliate.php') || tep_admin_check_boxes('catalog.php')){
?>
            <table cellspacing=0 cellpadding=0 width=100% border=0>
              <tr>
                <td><img src="images/contentbox/contentbox_left.gif" width="45" height="27" alt="" border="0"></td>
                <td class="contentboxheading" width=100%><?=TEXT_EVERYDAY_ACTIVITIES?></td>
                <td></td>
              </tr>
            </table>
            <table cellspacing=0 cellpadding=0 width=100% border=0>
              <tr>
                <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
              </tr>
              <tr>
                <?
                $count=0;
                if(tep_admin_check_boxes('customers.php'))
                {
                  $count++;
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_customer.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                           <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                           <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file(FILENAME_CUSTOMERS), 'selected_box=customers')?>"><?=BOX_HEADING_CUSTOMERS?></a></td>
                          </tr>
                          <tr>
                           <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS, 'selected_box=customers');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS, 'selected_box=customers');?></td>
                          </tr>
                       </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                if(tep_admin_check_boxes('reports.php'))
                {
                  $count++;
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_reports.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('reports.php', FILENAME_STATS_PRODUCTS_PURCHASED), 'selected_box=reports')?>"><?=BOX_HEADING_REPORTS?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_STATS_PRODUCTS_PURCHASED, REPORTS_PRODUCTS, 'selected_box=reports');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_STATS_CUSTOMERS, REPORTS_ORDERS, 'selected_box=reports');?><br></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                if(!($count&1))
                {
                ?>
              </tr>
              <tr>
              <?
                }
                if(tep_admin_check_boxes('affiliate.php'))
                {
                  $count++;
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_affiliates.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('affiliate.php', FILENAME_AFFILIATE_SUMMARY), 'selected_box=affiliate')?>"><?=BOX_HEADING_AFFILIATE?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_AFFILIATE, BOX_AFFILIATE, 'selected_box=affiliate');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_AFFILIATE_BANNERS, BOX_AFFILIATE_BANNERS, 'selected_box=affiliate');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                if(!($count&1))
                {
                ?>
              </tr>
              <tr>
              <?
                }
                if( tep_admin_check_boxes('catalog.php'))
                {
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_affiliates.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('catalog.php', FILENAME_CATEGORIES), 'selected_box=catalog')?>"><?=BOX_HEADING_CATALOG?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_CATEGORIES, CATALOG_CONTENTS, 'selected_box=catalog');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS, 'selected_box=catalog');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                ?>

              </tr>
              <tr>
                <td colspan="2"><img src="images/spacer.gif" width="1" height="20" alt="" border="0"></td>
              </tr>
            </table>
            <!-- Content box  Everyday activities_oef-->                    
<?php
  }
if (tep_admin_check_boxes('admin_account.php') || tep_admin_check_boxes('tools.php') || tep_admin_check_boxes('administrator.php')){
?>            
            <!-- Content box  Site management-->    
            <table cellspacing=0 cellpadding=0 width=100% border=0>
              <tr>
                <td><img src="images/contentbox/contentbox_left.gif" width="45" height="27" alt="" border="0"></td>
                <td class="contentboxheading" width=100%><?=TEXT_SITE_MANAGEMENT?></td>
                <td></td>
              </tr>
            </table>
            <table cellspacing=0 cellpadding=0 width=100% border=0>
              <tr>
                <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
              </tr>
              <tr>
                <?
                $count=0;
                if(tep_admin_check_boxes('admin_account.php'))
                {
                  $count++;
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_account.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file(FILENAME_ADMIN_ACCOUNT))?>"><?=BOX_HEADING_MY_ACCOUNT?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_ADMIN_ACCOUNT, HEADER_TITLE_ACCOUNT);?>&nbsp; &nbsp; <?php echo tep_admin_index_link(FILENAME_LOGOFF, HEADER_TITLE_LOGOFF);?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                if(tep_admin_check_boxes('tools.php'))
                {
                  $count++;
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_tools.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('tools.php', FILENAME_BACKUP), 'selected_box=tools')?>"><?=BOX_HEADING_TOOLS?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_BACKUP, TOOLS_BACKUP, 'selected_box=tools');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_BANNER_MANAGER, TOOLS_BANNERS, 'selected_box=tools');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_FILE_MANAGER, TOOLS_FILES, 'selected_box=tools');?></td>    
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                if(!($count&1))
                {
                ?>
              </tr>
              <tr>
              <?
                }
                if(tep_admin_check_boxes('administrator.php'))
                {
                ?>
                <td>
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_admin.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('administrator.php', FILENAME_ADMIN_ACCOUNT),'selected_box=administrator')?>"><?=BOX_HEADING_ADMINISTRATOR?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?if( tep_admin_check_boxes(FILENAME_ADMIN_MEMBERS, 'sub_boxes')){?><?php echo tep_admin_index_link(FILENAME_ADMIN_MEMBERS, BOX_ADMINISTRATOR_MEMBER, 'selected_box=administrator');?>&nbsp; &nbsp;<?}?> <?if(tep_admin_check_boxes(FILENAME_ADMIN_FILES, 'sub_boxes')){?><?php echo tep_admin_index_link(FILENAME_ADMIN_FILES, BOX_ADMINISTRATOR_BOXES, 'selected_box=administrator');?><?}?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                ?>
                <td>
                  <img src="images/spacer.gif" width="1" height="1" alt="" border="0">
                </td>
              </tr>
              <tr>
                <td colspan="2"><img src="images/spacer.gif" width="1" height="20" alt="" border="0"></td>
              </tr>
            </table>
            <!-- Content box  Site management_oef-->  
<?php
}
if (tep_admin_check_boxes('configuration.php') || tep_admin_check_boxes('faqdesk.php') || tep_admin_check_boxes('modules.php') || tep_admin_check_boxes('taxes.php') || tep_admin_check_boxes('localization.php') || tep_admin_check_boxes('design_controls.php') || tep_admin_check_boxes('newsdesk.php')){
?>            
            <!-- Content box  Configuration-->    
            <table cellspacing=0 cellpadding=0 width=100% border=0>
              <tr>
                <td><img src="images/contentbox/contentbox_left.gif" width="45" height="27" alt="" border="0"></td>
                <td class="contentboxheading" width=100%><?=TEXT_CONFIGURATION?></td>
                <td></td>
              </tr>
            </table>
            <table cellspacing=0 cellpadding=0 width=100% border=0>
              <tr>
                <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
              </tr>
              <tr>
              <?
                $count=0;
                if(tep_admin_check_boxes('configuration.php'))
                {
                  $count++;
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_config.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file(FILENAME_CONFIGURATION), 'selected_box=configuration&gID=1')?>"><?=BOX_HEADING_CONFIGURATION?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_CONFIGURATION, BOX_CONFIGURATION_MYSTORE, 'selected_box=configuration&gID=1');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_CONFIGURATION, BOX_CONFIGURATION_LOGGING, 'selected_box=configuration&gID=10');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_CONFIGURATION, BOX_CONFIGURATION_CACHE, 'selected_box=configuration&gID=11');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                if(tep_admin_check_boxes('modules.php'))
                {
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr valign="top">
                      <td><img src="images/icon/icon_modules.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?= tep_href_link(tep_selected_file(FILENAME_MODULES), 'selected_box=modules&set=payment')?>"><?=BOX_HEADING_MODULES?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_MODULES, BOX_MODULES_PAYMENT, 'selected_box=modules&set=payment');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_MODULES, BOX_MODULES_SHIPPING, 'selected_box=modules&set=shipping');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                ?>
              </tr>
              <tr>
                <?
                if(tep_admin_check_boxes('taxes.php'))
                {
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr>
                      <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
                    </tr>
                    <tr valign="top">
                      <td><img src="images/icon/icon_location.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('taxes.php', FILENAME_COUNTRIES), 'selected_box=taxes')?>"><?=BOX_HEADING_LOCATION_AND_TAXES?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_COUNTRIES, BOX_TAXES_COUNTRIES, 'selected_box=taxes');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_GEO_ZONES, BOX_TAXES_GEO_ZONES, 'selected_box=taxes');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                ?>
                <?
                if(tep_admin_check_boxes('localization.php'))
                {
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr>
                      <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
                    </tr>
                    <tr valign="top">
                      <td><img src="images/icon/icon_localization.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('localization.php', FILENAME_CURRENCIES), 'selected_box=localization')?>"><?=BOX_HEADING_LOCALIZATION?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_CURRENCIES, BOX_LOCALIZATION_CURRENCIES, 'selected_box=localization');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_LANGUAGES, BOX_LOCALIZATION_LANGUAGES, 'selected_box=localization');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              <?
                }
              ?>
              </tr>
              <tr>
              <?
              if(tep_admin_check_boxes('design_controls.php'))
              {
              ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr>
                      <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
                    </tr>
                    <tr valign="top">
                      <td><img src="images/icon/icon_design.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('design_controls.php', FILENAME_TEMPLATE_CONFIGURATION), 'cID=' . $template_id_select[template_id] . '&gID=' . $template_id_select[template_id] . '&selected_box=design_controls')?>"><?=BOX_HEADING_DESIGN_CONTROLS?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_TEMPLATE_CONFIGURATION, BOX_HEADING_TEMPLATE_CONFIGURATION, 'selected_box=design_controls');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_INFOBOX_CONFIGURATION, BOX_HEADING_BOXES, 'gID=' . $template_id_select[template_id] . '&selected_box=design_controls');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                ?>
                <?
                if(tep_admin_check_boxes('newsdesk.php'))
                {
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr>
                      <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
                    </tr>
                    <tr valign="top">
                      <td><img src="images/icon/icon_newdesk.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('newsdesk.php', FILENAME_NEWSDESK), 'selected_box=newsdesk')?>"><?=BOX_HEADING_NEWSDESK?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_NEWSDESK, NEWSDESK_ARTICLES, 'selected_box=newsdesk');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_NEWSDESK_REVIEWS, NEWSDESK_REVIEWS, 'selected_box=newsdesk');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              <?
                }
              ?>
              </tr>
              <tr>
                <?
                if(tep_admin_check_boxes('faqdesk.php'))
                {
                ?>
                <td width="50%">
                  <table class="infobox" cellspacing=0 cellpadding=1 width=100% border=0>
                    <tr>
                      <td colspan="2"><img src="images/spacer.gif" width="1" height="10" alt="" border="0"></td>
                    </tr>
                    <tr valign="top">
                      <td><img src="images/icon/icon_faq.jpg" width="65" height="62" alt="" border="0"></td>
                      <td width="100%">
                        <table cellspacing=0 cellpadding=3 width=100% border=0>
                          <tr>
                            <td><img src="images/spacer.gif" width="1" height="1" alt="" border="0"></td>
                          </tr>
                          <tr>
                            <td class="title"><a class="title" href="<?=tep_href_link(tep_selected_file('faqdesk.php', FILENAME_FAQDESK), 'selected_box=faqdesk')?>"><?=BOX_HEADING_FAQDESK?></a></td>
                          </tr>
                          <tr>
                            <td class="subtitle"><?php echo tep_admin_index_link(FILENAME_FAQDESK, FAQDESK_ARTICLES, 'selected_box=faqdesk');?>&nbsp; &nbsp;<?php echo tep_admin_index_link(FILENAME_FAQDESK_REVIEWS, FAQDESK_REVIEWS, 'selected_box=faqdesk');?></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
                <?
                }
                ?>
                <td>
                  <img src="images/spacer.gif" width="1" height="1" alt="" border="0">
                </td>
              </tr>
              <tr>
                <td colspan="2"><img src="images/spacer.gif" width="1" height="20" alt="" border="0"></td>
              </tr>              
            </table>
<?php
}
?>
            <!-- Content box  Configuration_oef-->                          
                      </td>
          
          
          
          
          <td width="20"><img src="images/spacer.gif" width="20" height="1" alt="" border="0"></td>
        </tr>
      </table>
    
    </td>
  </tr>
</table>  

<!-- Content_oef -->                   
<!-- Footer --> 
            <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
          </td>
        </tr>
        <!-- Footer_oef --> 
</center>    
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>