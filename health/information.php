<?php
  /*
  Module: Information Pages Unlimited
  File date: 2003/03/03
  Based on the FAQ script of adgrafics
  Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  
  Released under the GNU General Public License
  */
  
    require('includes/application_top.php');
	include_once('controllers/front/InformationController.php');
	$controller = new InformationController();
	$canonical_tag = $controller->get_canonical_tag();#

if ($HTTP_GET_VARS['info_id'] == "16") {
	  header('HTTP/1.0 404 Not Found');
	}
	
	
	/* Amazon Checkout - If displaying the Amazon Order Success Page then clear amazon PurchaseContrractID */
	if ($HTTP_GET_VARS['info_id'] == "25") {
		$_SESSION['amazon_purchaseContractId'] = "";
	}
	
  
  // Added for information pages
  if(!$HTTP_GET_VARS['info_id'])
  die("No page found.");
  $info_id = $HTTP_GET_VARS['info_id'];  
  
  $sql = tep_db_query("select if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, if(length(i1.description), i1.description, i.description) as description, i.information_id from " . TABLE_INFORMATION . " i LEFT JOIN " . TABLE_INFORMATION . " i1 on i.information_id = i1.information_id  and i1.languages_id = '" . (int)$languages_id . "' and i1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  where i.information_id = '" . (int)$info_id . "' and i.languages_id = '" . (int)$languages_id . "' and i.visible = 1 and i.affiliate_id = 0");
//  $sql=tep_db_query("SELECT * FROM ".TABLE_INFORMATION." WHERE visible='1' AND information_id='".$info_id."' AND languages_id = '" . $languages_id . "'");
  $row=tep_db_fetch_array($sql);
  
  $INFO_DESCRIPTION = $row['description'];
  $INFO_TITLE = tep_not_null($row['page_title'])?$row['page_title']:$row['info_title'];
  
  // Only replace cariage return by <BR> if NO HTML found in text
  // Added as noticed by infopages module
  if (!preg_match("/([\<])([^\>]{1,})*([\>])/i", $INFO_DESCRIPTION)) {
    $INFO_DESCRIPTION = str_replace("\r\n", "<br>\r\n", $INFO_DESCRIPTION );
  }
  
  $breadcrumb->add($INFO_TITLE, tep_href_link(FILENAME_INFORMATION, 'info_id=' . $row['information_id'], 'NONSSL'));
  
  $content = CONTENT_INFORMATION;
  
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
