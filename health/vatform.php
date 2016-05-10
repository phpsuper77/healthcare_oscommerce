<?php
/*
  $Id: contact_us.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  define('RELATIVE_PATH', DIR_WS_CLASSES.'/html2pdf/');
  define('FPDF_FONTPATH', DIR_WS_CLASSES.'/html2pdf/font/');
  if ( !defined('DIR_FS_DOCUMENT_ROOT') ) define('DIR_FS_DOCUMENT_ROOT', DIR_FS_CATALOG);
  include_once(DIR_WS_CLASSES . '/html2pdf/html2fpdf.php');
  class VEF_PDF extends HTML2FPDF{
    function VEF_PDF(){
      $this->HTML2FPDF();
      //$this->SetTopMargin(24); // header images
      $this->UseCSS(false);
      $this->SetFontSize(10);
    }
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_VATFORM);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'send')) {

	  if ($_POST['subaction'] == "skip") {
		  $cart->removeVatExempt();
		  tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'amazon_purchaseContractId='.$_GET['amazon_purchaseContractId'], 'SSL'));
		  //tep_redirect(tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product'));
		  die;
	  }


    $name = tep_db_prepare_input($HTTP_POST_VARS['name']);
    if (strlen($name)==0) {
      $error = true;
      $messageStack->add('vatform', ENTRY_NAME_ERROR);
    }

    $address = tep_db_prepare_input($HTTP_POST_VARS['address']);
    if (strlen($address)==0) {
      $error = true;
      $messageStack->add('vatform', ENTRY_ADDRESS_ERROR);
    }

    $email = tep_db_prepare_input($HTTP_POST_VARS['email']);
    if (strlen($email)==0) {
      $error = true;
      $messageStack->add('vatform', ENTRY_EMAIL_ERROR);
    }elseif(!tep_validate_email($email)){
      $error = true;
      $messageStack->add('vatform', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $phone = tep_db_prepare_input($HTTP_POST_VARS['phone']);
    if (strlen($phone)==0) {
      $error = true;
      $messageStack->add('vatform', ENTRY_PHONE_ERROR);
    }

    if ( !(isset( $HTTP_POST_VARS['tick1'] ) && isset( $HTTP_POST_VARS['tick2'] ) && isset( $HTTP_POST_VARS['tick3'] )) ) {
      $error = true;
      $messageStack->add('vatform', ENTRY_TICK_ERROR);
    }

    $describe_condition = tep_db_prepare_input($HTTP_POST_VARS['describe_condition']);
    if (strlen($describe_condition)==0) {
      $error = true;
      $messageStack->add('vatform', ENTRY_DESCRIBE_STATEMENTS_ERROR);
    }

    $signed = tep_db_prepare_input($HTTP_POST_VARS['signed']);
    if (strlen($signed)==0) {
      $error = true;
      $messageStack->add('vatform', ENTRY_SIGNED_ERROR);
    }

    $sec_name = tep_db_prepare_input($HTTP_POST_VARS['sec_name']);
    $sec_address = tep_db_prepare_input($HTTP_POST_VARS['sec_address']);
    $sec_relationship = tep_db_prepare_input($HTTP_POST_VARS['sec_relationship']);

    if ( !$error ) {
      // build & send form
      // get pdf header
      $info_id = 12;
      $sql = tep_db_query("select if(length(i1.info_title), i1.info_title, i.info_title) as info_title, if(length(i1.page_title), i1.page_title, i.page_title) as page_title, if(length(i1.description), i1.description, i.description) as description, i.information_id from " . TABLE_INFORMATION . " i LEFT JOIN " . TABLE_INFORMATION . " i1 on i.information_id = i1.information_id  and i1.languages_id = '" . (int)$languages_id . "' and i1.affiliate_id = '" . (int)$HTTP_SESSION_VARS['affiliate_ref'] . "'  where i.information_id = '" . (int)$info_id . "' and i.languages_id = '" . (int)$languages_id . "' and i.visible = 1 and i.affiliate_id = 0");
      $row=tep_db_fetch_array($sql);
      $pdf_str = '<table width="100%"><tr><td width="50%"><img style="WIDTH: 289px; HEIGHT: 111px" height="111" hspace="0" src="images/logo_white.jpg" width="289" align="top" border="0" /></td><td width="50%" align="right"><p style="MARGIN-BOTTOM: 0cm" align="right"><font face="Verdana, sans-serif">'.nl2br(STORE_NAME_ADDRESS).'<br /><br /></font><font face="Verdana, sans-serif">email: </font><font color="#0000ff"><u><a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'"><font face="Verdana, sans-serif">'.STORE_OWNER_EMAIL_ADDRESS.'</font></a><br /></u></font><font face="Verdana, sans-serif">fax: </font><font color="#000000"><font face="Verdana, sans-serif"><font size="2"><b>'.FAX_NUMBER.'</b></font></font></font></p></td></tr></table>'."\n<br>\n<br>\n<br>";
      $pdf_str .= $row['description'];
      $pdf_str = preg_replace('/ src="(.*)\/images/', ' src="images', $pdf_str);
      $pdf_str .= '<p><font face="Verdana, sans-serif">';
      $pdf_str .= '<table>';
      $pdf_str .= '<tr><td width="25%">'.ENTRY_NAME.'</td><td>'.$name.'</td></tr>';
      $pdf_str .= '<tr><td width="25%">'.ENTRY_ADDRESS.'</td><td>'.$address.'</td></tr>';
      $pdf_str .= '<tr><td width="25%">'.ENTRY_EMAIL.'</td><td>'.$email.'</td></tr>';
      $pdf_str .= '<tr><td width="25%">'.ENTRY_PHONE.'</td><td>'.$phone.'</td></tr>';
      $pdf_str .= '</table>';
      $pdf_str .= ENTRY_TICK_BOXES_HEAD."\n".' <br>'."\n <br>";
      $pdf_str .= '[*] '.ENTRY_TICK_1.'<br> <u>'.$describe_condition.'</u> <br>'."\n <br>";
      $pdf_str .= '[*] '.sprintf(ENTRY_TICK_2_S, preg_replace('/\s+/ims', ' ',STORE_NAME_ADDRESS) ).'<br>'."\n <br>";
      $pdf_str .= '[*] '.ENTRY_TICK_3.'<br>'."\n <br>";
      $pdf_str .= '</p>';
      $pdf_str .= '<p><table width="100%"><tr><td width="15%">&nbsp;</td><td width="50%">'.ENTRY_SIGNED.' '.$signed.'</td><td width="35%">'.PDF_TEXT_DATE.tep_date_short( date('Y-m-d H:i:s') ).'</td></tr></table></p>';

      $pdf_str .= '<p>'.ENTRY_SECONDARY.'</p>';
      $pdf_str .= '<p>'.ENTRY_SECONDARY_NAME.' '.$sec_name.'</p>';
      $pdf_str .= '<p>'.ENTRY_SECONDARY_ADDRESS.' '.$sec_address.'</p>';
      $pdf_str .= '<p>'.ENTRY_SECONDARY_RELATIONSHIP.' '.$sec_relationship.'</p>';
      $pdf_str .= '';
       
      $pdf = new VEF_PDF();
      $pdf->AddPage();
      $pdf->WriteHTML($pdf_str);
      $binPdf = $pdf->Output('vat_exempt_form.pdf','S');

      $enquiry  = ENTRY_NAME.' '.$name."\n";
      $enquiry .= ENTRY_ADDRESS."\n$address\n";
      $enquiry .= ENTRY_EMAIL." $email\n";
      $enquiry .= ENTRY_PHONE." $phone\n";
      $enquiry .= "\nCondition describe \n$describe_condition \n\n";
      $enquiry .= ENTRY_SIGNED." $signed\n";
      $enquiry .= "\n".ENTRY_SECONDARY_RELATIONSHIP." $sec_relationship\n";
      $enquiry .= ENTRY_SECONDARY_NAME.' '.$sec_name."\n";
      $enquiry .= ENTRY_SECONDARY_ADDRESS.' '.$sec_address."\n";
      
      $attaches = array(array('name'=>'vat_exempt_form.pdf','file'=>$binPdf));

      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email, $attaches);
	  tep_mail(STORE_OWNER, "muzz@alljammin.com", EMAIL_SUBJECT, $enquiry, $name, $email, $attaches);

      if ( tep_session_is_registered('customer_id') ) {
        tep_db_query("update " . TABLE_CUSTOMERS . " set vat_exemption_form_sent =1, vat_exemption_form_date=now() where customers_id='" . (int)$customer_id . "'");
      }else{
        $_SESSION['memo_vatsend_for'] = $email;
        $_SESSION['memo_vatsend_date'] = date('Y-m-d H:i:s');
      }
		//continue to next checkout step
	    tep_redirect(tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, 'amazon_purchaseContractId='.$_GET['amazon_purchaseContractId'], 'SSL'));


      //tep_redirect(tep_href_link(FILENAME_VATFORM, tep_get_all_get_params( array('action') ).'action=success'));
    }
  }else{
    // defaults
    if ( tep_session_is_registered('customer_id') ) {
      $data_r = tep_db_query("SELECT customers_firstname, customers_lastname, customers_email_address, customers_default_address_id, customers_telephone FROM ".TABLE_CUSTOMERS." WHERE customers_id='".(int)$customer_id."'");
      if ( tep_db_num_rows($data_r)>0 ) {
        $data = tep_db_fetch_array($data_r);
        $name = trim($data['customers_firstname']. ' ' . $data['customers_lastname']);
        $email = $data['customers_email_address'];
        $phone = $data['customers_telephone'];
        $address_query = tep_db_query("select entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$data['customers_default_address_id'] . "'");
        $address_array = tep_db_fetch_array($address_query);
        $address = tep_address_format(
          tep_get_address_format_id($address_array['country_id']), 
          $address_array, 
          false, 
          '', 
          "\n"
        );
        $address = trim($address);
      }
    }else{
      $name = '';
      $address = '';
      $email = '';
      $phone = '';
    }
    $describe_condition = '';
    $signed = '';
    $sec_name = '';
    $sec_address = '';
    $sec_relationship = '';
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_VATFORMS));

  $content = CONTENT_VATFORM;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
