<?php
/*
  $Id: paypalipn_tests.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Paypal IPN v0.981 for Milestone 2
  Copyright (c) 2003 Pablo Pasqualino
  pablo_osc@osmosisdc.com
  http://www.osmosisdc.com

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $last_paypalipn_order_query = tep_db_query("select * from ".TABLE_ORDERS." WHERE orders_status='99999' order by orders_id desc limit 0,1");
  $last_paypalipn_order = tep_db_fetch_array($last_paypalipn_order_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?
  $header_title_menu=BOX_HEADING_PAYPALIPN_ADMIN;
  $header_title_menu_link= tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=paypalipn');
  $header_title_submenu='PayPal IPN Tests';
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
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
        </tr>
        <tr>
          <td><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
        </tr>
        <tr><form name="paypal_ipn_tests" action="<?php echo MODULE_PAYMENT_PAYPALIPN_NOTIFY_URL; ?>" method="post">        
       <td><?php if (MODULE_PAYMENT_PAYPALIPN_TEST_MODE=='False') { ?><font color="#FF0000"><b>Be careful, PayPal IPN Payment Module is NOT 
        in "test mode".<br>
        IPN Test will not work until you enable "test mode".</b><br><br></font><?php }; ?>
        <table border="0" cellspacing="0" cellpadding="2">
         <tr> 
          <td class="main">IPN Result:</td>
          <td class="main"> 
           <input type="radio" name="ipnstatus" value="VERIFIED" class="form_objects" checked>
           <font class="form_grn"> VERIFIED</font> 
           <input type="radio" name="ipnstatus" value="INVALID" class="form_objects">
           <font class="form_red"> INVALID</font> </td>
         </tr>
         <tr> 
          <td class="main">receiver_email:</td>
          <td class="main"> 
           <input type="text" name="receiver_email" value="<?php echo MODULE_PAYMENT_PAYPALIPN_ID; ?>">
          </td>
         </tr>
         <tr> 
          <td class="main">business:</td>
          <td class="main"> 
           <input type="text" name="business" value="<?php echo MODULE_PAYMENT_PAYPALIPN_ID; ?>">
          </td>
         </tr>
         <tr> 
          <td class="main">item_name:</td>
          <td class="main"> 
           <input type="text" name="item_name" value="item name">
          </td>
         </tr>
         <tr> 
          <td class="main">item_number:</td>
          <td class="main"> 
           <input type="text" name="item_number" value="<?php echo $last_paypalipn_order[orders_id]; ?>"><?php if  ($last_paypalipn_order[orders_id]) { ?> (<?php echo $last_paypalipn_order[orders_id]; ?>) is the id of the last order with "paypal_processing" status.<?php }; ?>
          </td>
         </tr>
         <tr> 
          <td class="main">quantity:</td>
          <td class="main"> 
           <input type="text" name="quantity" value="1">
          </td>
         </tr>
         <tr> 
          <td class="main">invoice:</td>
          <td class="main"> 
           <input type="text" name="invoice">
          </td>
         </tr>
         <tr> 
          <td class="main">custom:</td>
          <td class="main"> 
           <input type="text" name="custom">
          </td>
         </tr>
         <tr> 
          <td class="main">option_name1:</td>
          <td class="main"> 
           <input type="text" name="option_name1">
          </td>
         </tr>
         <tr> 
          <td class="main">option_selection1:</td>
          <td class="main"> 
           <input type="text" name="option_selection1">
          </td>
         </tr>
         <tr> 
          <td class="main">option_name2:</td>
          <td class="main"> 
           <input type="text" name="option_name2">
          </td>
         </tr>
         <tr> 
          <td class="main">option_selection2:</td>
          <td class="main"> 
           <input type="text" name="option_selection2">
          </td>
         </tr>
         <tr> 
          <td class="main">num_cart_items:</td>
          <td class="main"> 
           <input type="text" name="num_cart_items" value="0">
          </td>
         </tr>
         <tr> 
          <td class="main">payment_status:</td>
          <td class="main"> 
           <input type="radio" name="payment_status" value="Completed" class="form_objects" checked>
           <font class="form_std"> Completed</font> 
           <input type="radio" name="payment_status" value="Pending" class="form_objects">
           <font class="form_std"> Pending</font> 
           <input type="radio" name="payment_status" value="Failed" class="form_objects">
           <font class="form_std"> Failed</font> 
           <input type="radio" name="payment_status" value="Denied" class="form_objects">
           <font class="form_std"> Denied</font> </td>
         </tr>
         <tr> 
          <td class="main">pending_reason:</td>
          <td class="main"> 
           <input type="radio" name="pending_reason" value="echeck" class="form_objects">
           <font class="form_std"> echeck</font> 
           <input type="radio" name="pending_reason" value="intl" class="form_objects">
           <font class="form_std"> intl</font> 
           <input type="radio" name="pending_reason" value="verify" class="form_objects">
           <font class="form_std"> verify</font> 
           <input type="radio" name="pending_reason" value="address" class="form_objects">
           <font class="form_std"> address</font> 
           <input type="radio" name="pending_reason" value="upgrade" class="form_objects">
           <font class="form_std"> upgrade</font> 
           <input type="radio" name="pending_reason" value="unilateral" class="form_objects">
           <font class="form_std"> unilateral</font> 
           <input type="radio" name="pending_reason" value="other" class="form_objects">
           <font class="form_std"> other</font> </td>
         </tr>
         <tr> 
          <td class="main">payment_date:</td>
          <td class="main"> 
           <input type="text" name="payment_date" value="<?php echo date("H:i:s M j, Y"); ?> PST">
          </td>
         </tr>
         <tr> 
          <td class="main">payment_gross:</td>
          <td class="main"> 
           <input type="text" name="payment_gross" value="25">
          </td>
         </tr>
         <tr> 
          <td class="main">payment_fee:</td>
          <td class="main"> 
           <input type="text" name="payment_fee" value="1.02">
          </td>
         </tr>
         <tr> 
          <td class="main">mc_gross:</td>
          <td class="main"> 
           <input type="text" name="mc_gross" value="25">
          </td>
         </tr>
         <tr> 
          <td class="main">mc_fee:</td>
          <td class="main"> 
           <input type="text" name="mc_fee" value="1.02">
          </td>
         </tr>
         <tr> 
          <td class="main">mc_currency:</td>
          <td class="main"> 
           <input type="radio" name="USD" value="mc_currency" class="form_objects" checked>
           <font class="form_std"> USD</font> 
           <input type="radio" name="EUR" value="mc_currency" class="form_objects">
           <font class="form_std"> EUR</font> 
           <input type="radio" name="GBP" value="mc_currency" class="form_objects">
           <font class="form_std"> GBP</font>
           <input type="radio" name="CAD" value="mc_currency" class="form_objects">
           <font class="form_std"> CAD</font>
           <input type="radio" name="JPY" value="mc_currency" class="form_objects">
           <font class="form_std"> JPY</font>
           </td>
         </tr>
         <tr> 
          <td class="main">txn_id:</td>
          <td class="main"> 
           <input type="text" name="txn_id" value="<?php echo time(); ?>">
          </td>
         </tr>
         <tr> 
          <td class="main">txn_type:</td>
          <td class="main"> 
           <input type="radio" name="txn_type" value="web_accept" class="form_objects" checked>
           <font class="form_std"> web_accept</font> 
           <input type="radio" name="txn_type" value="cart" class="form_objects">
           <font class="form_std"> cart</font> 
           <input type="radio" name="txn_type" value="send_money" class="form_objects">
           <font class="form_std"> send_money</font> </td>
         </tr>
         <tr> 
          <td class="main">first_name:</td>
          <td class="main"> 
           <input type="text" name="first_name" value="first_name">
          </td>
         </tr>
         <tr> 
          <td class="main">last_name:</td>
          <td class="main"> 
           <input type="text" name="last_name" value="last_name">
          </td>
         </tr>
         <tr> 
          <td class="main">address_street:</td>
          <td class="main"> 
           <input type="text" name="address_street" value="street">
          </td>
         </tr>
         <tr> 
          <td class="main">address_city:</td>
          <td class="main"> 
           <input type="text" name="address_city" value="city">
          </td>
         </tr>
         <tr> 
          <td class="main">address_state:</td>
          <td class="main"> 
           <input type="text" name="address_state" value="state">
          </td>
         </tr>
         <tr> 
          <td class="main">address_zip:</td>
          <td class="main"> 
           <input type="text" name="address_zip" value="zip">
          </td>
         </tr>
         <tr> 
          <td class="main">address_country:</td>
          <td class="main"> 
           <select name="address_country" value="" class="form_objects">
            <option value=Afghanistan>Afghanistan 
            <option value=Albania>Albania 
            <option value=Algeria>Algeria 
            <option value=American-Samoa>American Samoa 
            <option value=Andorra>Andorra 
            <option value=Angola>Angola 
            <option value=Anguilla>Anguilla 
            <option value=Antarctica>Antarctica 
            <option value=Antigua-&-Barbuda>Antigua & Barbuda 
            <option value=Argentina>Argentina 
            <option value=Armenia>Armenia 
            <option value=Aruba>Aruba 
            <option value=Australia>Australia 
            <option value=Austria>Austria 
            <option value=Azerbaijan>Azerbaijan 
            <option value=Bahamas>Bahamas 
            <option value=Bahrain>Bahrain 
            <option value=Bangladesh>Bangladesh 
            <option value=Barbados>Barbados 
            <option value=Belarus>Belarus 
            <option value=Belgium>Belgium 
            <option value=Belize>Belize 
            <option value=Benin>Benin 
            <option value=Bermuda>Bermuda 
            <option value=Bhutan>Bhutan 
            <option value=Bolivia>Bolivia 
            <option value=Bosnia-Herzegovina>Bosnia Herzegovina 
            <option value=Botswana>Botswana 
            <option value=Bouvet-Island>Bouvet Island 
            <option value=Brazil>Brazil 
            <option value=British-Indian-Ocean-Territory>British Indian Ocean Territory 
            <option value=British-Virgin-Islands>British Virgin Islands 
            <option value=Brunei-Darussalam>Brunei Darussalam 
            <option value=Bulgaria>Bulgaria 
            <option value=Burkina-Faso>Burkina Faso 
            <option value=Burma>Burma 
            <option value=Burundi>Burundi 
            <option value=Cambodia>Cambodia 
            <option value=Cameroon>Cameroon 
            <option value=Canada>Canada 
            <option value=Canary-Islands>Canary Islands 
            <option value=Cape-Verde>Cape Verde 
            <option value=Cayman-Islands>Cayman Islands 
            <option value=Central-African-Republic>Central African Republic 
            <option value=Chad>Chad 
            <option value=Chile>Chile 
            <option value=China>China 
            <option value=Christmas-Island>Christmas Island 
            <option value=Cocos-Keeling-Islands>Cocos Keeling Islands 
            <option value=Colombia>Colombia 
            <option value=Comoros>Comoros 
            <option value=Congo-Democratic-Republic>Congo Democratic Republic 
            <option value=Congo-Republic>Congo Republic 
            <option value=Cook-Islands>Cook Islands 
            <option value=Costa-Rica>Costa Rica 
            <option value=Cote-dIvoire-Ivory-Coast>Cote dIvoire Ivory Coast 
            <option value=Croatia>Croatia 
            <option value=Cyprus>Cyprus 
            <option value=Czech-Republic>Czech Republic 
            <option value=Denmark>Denmark 
            <option value=Djibouti>Djibouti 
            <option value=Dominica>Dominica 
            <option value=Dominican-Republic>Dominican Republic 
            <option value=East-Timor>East Timor 
            <option value=Ecuador>Ecuador 
            <option value=Egypt>Egypt 
            <option value=El-Salvador>El Salvador 
            <option value=England>England 
            <option value=Equatorial-Guinea>Equatorial Guinea 
            <option value=Eritrea>Eritrea 
            <option value=Espana>Espana 
            <option value=Estonia>Estonia 
            <option value=Ethiopia>Ethiopia 
            <option value=Falkland-Islands>Falkland-Islands 
            <option value=Faroe-Islands>Faroe Islands 
            <option value=Fiji>Fiji 
            <option value=Finland>Finland 
            <option value=France>France 
            <option value=French-Guiana>French Guiana 
            <option value=French-Polynesia>French Polynesia 
            <option value=French-Southern-Territories>French Southern Territories 
            <option value=Gabon>Gabon 
            <option value=Gambia>Gambia 
            <option value=Georgia-Republic>Georgia Republic 
            <option value=Germany>Germany 
            <option value=Ghana>Ghana 
            <option value=Gibraltar>Gibraltar 
            <option value=Great-Britain>Great Britain 
            <option value=Greece>Greece 
            <option value=Greenland>Greenland 
            <option value=Grenada>Grenada 
            <option value=Guadeloupe>Guadeloupe 
            <option value=Guam>Guam 
            <option value=Guatemala>Guatemala 
            <option value=Guinea>Guinea 
            <option value=Guinea-Bissau>Guinea Bissau 
            <option value=Guyana>Guyana 
            <option value=Haiti>Haiti 
            <option value=Heard-&-Mc-Donald-Islands>Heard & Mc Donald Islands 
            <option value=Honduras>Honduras 
            <option value=Hong-Kong>Hong Kong 
            <option value=Hungary>Hungary 
            <option value=Iceland>Iceland 
            <option value=India>India 
            <option value=Indonesia>Indonesia 
            <option value=Iran>Iran 
            <option value=Ireland-Eire>Ireland Eire 
            <option value=Israel>Israel 
            <option value=Italy>Italy 
            <option value=Jamaica>Jamaica 
            <option value=Japan>Japan 
            <option value=Jordan>Jordan 
            <option value=Kazakhstan>Kazakhstan 
            <option value=Kenya>Kenya 
            <option value=Kiribati>Kiribati 
            <option value=Korea-South>Korea South 
            <option value=Korea-Republic>Korea Republic 
            <option value=Kuwait>Kuwait 
            <option value=Kyrgyzstan>Kyrgyzstan 
            <option value=Lao-Democratic-Republic>Lao Democratic Republic 
            <option value=Latvia>Latvia 
            <option value=Lebanon>Lebanon 
            <option value=Lesotho>Lesotho 
            <option value=Liberia>Liberia 
            <option value=Libya>Libya 
            <option value=Liechtenstein>Liechtenstein 
            <option value=Lithuania>Lithuania 
            <option value=Luxembourg>Luxembourg 
            <option value=Macao>Macao 
            <option value=Macedonia-Republic>Macedonia Republic 
            <option value=Madagascar>Madagascar 
            <option value=Malawi>Malawi 
            <option value=Malaysia>Malaysia 
            <option value=Maldives>Maldives 
            <option value=Mali>Mali 
            <option value=Malta>Malta 
            <option value=Marshall-Islands>Marshall Islands 
            <option value=Martinique>Martinique 
            <option value=Mauritania>Mauritania 
            <option value=Mauritius>Mauritius 
            <option value=Mayotte>Mayotte 
            <option value=Mexico>Mexico 
            <option value=Micronesia-Federated-States>Micronesia Federated States 
            <option value=Moldova-Republic>Moldova Republic 
            <option value=Monaco>Monaco 
            <option value=Mongolia>Mongolia 
            <option value=Montserrat>Montserrat 
            <option value=Morocco>Morocco 
            <option value=Mozambique>Mozambique 
            <option value=Myanmar>Myanmar 
            <option value=Namibia>Namibia 
            <option value=Nauru>Nauru 
            <option value=Nepal>Nepal 
            <option value=Netherlands>Netherlands 
            <option value=Netherlands-Antilles>Netherlands Antilles 
            <option value=New-Caledonia>New Caledonia 
            <option value=New-Zealand>New Zealand 
            <option value=Nicaragua>Nicaragua 
            <option value=Niger>Niger 
            <option value=Nigeria>Nigeria 
            <option value=Niue>Niue 
            <option value=Norfolk Island>Norfolk Island 
            <option value=Northern-Ireland>Northern Ireland 
            <option value=Northern-Mariana-Islands>Northern Mariana Islands 
            <option value=Norway>Norway 
            <option value=Oman>Oman 
            <option value=Pakistan>Pakistan 
            <option value=Palua>Palua 
            <option value=Panama>Panama 
            <option value=Papua-New-Guinea>Papua New Guinea 
            <option value=Paraguay>Paraguay 
            <option value=Peru>Peru 
            <option value=Philippines>Philippines 
            <option value=Pitcairn-Island>Pitcairn Island 
            <option value=Poland>Poland 
            <option value=Portugal>Portugal 
            <option value=Puerto-Rico>Puerto Rico 
            <option value=Qatar>Qatar 
            <option value=Reunion>Reunion 
            <option value=Romania>Romania 
            <option value=Russia>Russia 
            <option value=Russian-Federation>Russian Federation 
            <option value=Rwanda>Rwanda 
            <option value=Saint-Helena>Saint Helena 
            <option value=Saint-Kitts-&-Nevis>Saint Kitts & Nevis 
            <option value=Saint-Lucia>Saint Lucia 
            <option value=Saint-Pierre-&-Miquelon>Saint Pierre & Miquelon 
            <option value=Saint-Vincent-&-Grenadines>Saint Vincent & Grenadines 
            <option value=Samoa-Independent>Samoa Independent 
            <option value=San-Marino>San Marino 
            <option value=Sao-Tome-&-Principe>Sao Tome & Principe 
            <option value=Saudi-Arabia>Saudi Arabia 
            <option value=Scotland>Scotland 
            <option value=Senegal>Senegal 
            <option value=Serbia-Montenegro-Yugoslavia>Serbia Montenegro Yugoslavia 
            <option value=Seychelles>Seychelles 
            <option value=Sierra-Leone>Sierra Leone 
            <option value=Singapore>Singapore 
            <option value=Slovak-Republic-Slovakia>Slovak Republic Slovakia 
            <option value=Slovenia>Slovenia 
            <option value=Solomon-Islands>Solomon Islands 
            <option value=Somalia>Somalia 
            <option value=South-Africa>South Africa 
            <option value=South-Georgia-Sandwich-Islands>South Georgia Sandwich 
            Islands 
            <option value=South-Korea>South Korea 
            <option value=Spain>Spain 
            <option value=Sri-Lanka>Sri Lanka 
            <option value=Sudan>Sudan 
            <option value=Suriname>Suriname 
            <option value=Svalbard-&-Jan-Mayen-Islands>Svalbard & Jan Mayen Islands 
            <option value=Swaziland>Swaziland 
            <option value=Sweden>Sweden 
            <option value=Switzerland>Switzerland 
            <option value=Syrian-Arab-Republic-Syria>Syrian Arab Republic Syria 
            <option value=Taiwan>Taiwan 
            <option value=Tajikistan>Tajikistan 
            <option value=Tanzania>Tanzania 
            <option value=Thailand>Thailand 
            <option value=Togo>Togo 
            <option value=Tokelau>Tokelau 
            <option value=Tonga>Tonga 
            <option value=Trinidad>Trinidad 
            <option value=Trinidad-&-Tobago>Trinidad & Tobago 
            <option value=Tristan-da-Cunha>Tristan da Cunha 
            <option value=Tunisia>Tunisia 
            <option value=Turkey>Turkey 
            <option value=Turkmenistan>Turkmenistan 
            <option value=Turks-&-Caicos-Islands>Turks & Caicos Islands 
            <option value=Tuvalu>Tuvalu 
            <option value=Uganda>Uganda 
            <option value=Ukraine>Ukraine 
            <option value=United-Arab-Emirates>United Arab Emirates 
            <option value=United-Kingdom>United Kingdom 
            <option selected value=United-States>United States 
            <option value=Uruguay>Uruguay 
            <option value=US-Virgin-Islands>US Virgin Islands 
            <option value=Uzbekistan>Uzbekistan 
            <option value=Vanuatu>Vanuatu 
            <option value=Vatican-City>Vatican City 
            <option value=Venezuela>Venezuela 
            <option value=Vietnam>Vietnam 
            <option value=Wales>Wales 
            <option value=Wallis-&-Futuna-Islands>Wallis & Futuna Islands 
            <option value=Western-Samoa>Western Samoa 
            <option value=Yemen>Yemen 
            <option value=Zambia>Zambia 
            <option value=Zimbabwe>Zimbabwe 
           </select>
          </td>
         </tr>
         <tr> 
          <td class="main">address_status:</td>
          <td class="main"> 
           <input type="radio" name="address_status" value="confirmed" class="form_objects" checked>
           <font class="form_std"> confirmed</font> 
           <input type="radio" name="address_status" value="unconfirmed" class="form_objects">
           <font class="form_std"> unconfirmed</font> </td>
         </tr>
         <tr> 
          <td class="main">payer_email:</td>
          <td class="main"> 
           <input type="text" name="payer_email" value="email@email.com">
          </td>
         </tr>
         <tr> 
          <td class="main">payer_id:</td>
          <td class="main"> 
           <input type="text" name="payer_id" value="XPN8XCWPYAMW5">
          </td>
         </tr>
         <tr> 
          <td class="main">payer_status:</td>
          <td class="main"> 
           <input type="radio" name="payer_status" value="verified" class="form_objects" checked>
           <font class="form_std"> verified</font> 
           <input type="radio" name="payer_status" value="unverified" class="form_objects">
           <font class="form_std"> unverified</font> 
           <input type="radio" name="payer_status" value="intl_verified" class="form_objects">
           <font class="form_std"> intl_verified</font> 
           <input type="radio" name="payer_status" value="intl_unverified" class="form_objects">
           <font class="form_std"> intl_unverified</font> </td>
         </tr>
         <tr> 
          <td class="main">payment_type:</td>
          <td class="main"> 
           <input type="radio" name="payment_type" value="echeck" class="form_objects">
           <font class="form_std"> echeck</font> 
           <input type="radio" name="payment_type" value="instant" class="form_objects" checked>
           <font class="form_std"> instant</font> </td>
         </tr>
         <tr> 
          <td class="main">notify_version:</td>
          <td class="main"> 
           <input type="text" name="notify_version" value="1.4">
          </td>
         </tr>
         <tr> 
          <td class="main">verify_sign:</td>
          <td class="main"> 
           <input type="text" name="verify_sign" value="DfDT6KaaWATTjfyzTIv4pohGWAs0XDitN19Y">
          </td>
         </tr>
        </table>
       </td>
      </tr>
      <tr>
        <td><img src="images/pixel_trans.gif" border="0" alt="" width="1" height="10"></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_image_submit('button_send.gif', 'Send'); ?></td>
      </form></tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
