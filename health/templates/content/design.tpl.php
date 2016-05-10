<script language="javascript"><!--
var selected;
var submitter = null;
function submitFunction() {
  submitter = null;
  if(document.checkout_payment.cot_gv != null && document.checkout_payment.cot_gv != 'undefined')
  {
    if(document.checkout_payment.cot_gv.checked)
      submitter = 1;
  }
}

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
<?php
  require(DIR_WS_CONTENT . CONTENT_INDEX_DEFAULT . '.tpl.php');
  echo '<br><hr width="100%"><br>';
  $cPath = 3;
  $current_category_id = 3;
  require(DIR_WS_CONTENT . CONTENT_INDEX_NESTED . '.tpl.php');

  echo '<br><hr width="100%"><br>';
  $cPath = '3_10';
  $current_category_id = 10;
  require(DIR_WS_CONTENT . CONTENT_INDEX_PRODUCTS . '.tpl.php');
  $current_category_id = 0;
  $cPath = '';
  echo '<br><hr width="100%"><br>';
  require(DIR_WS_CONTENT . CONTENT_PRODUCT_INFO . '.tpl.php');
  echo '<br><hr width="100%"><br>';
  require(DIR_WS_CONTENT . CONTENT_CREATE_ACCOUNT . '.tpl.php');
  echo '<br><hr width="100%"><br>';
  require(DIR_WS_CONTENT . CONTENT_LOGIN . '.tpl.php');
  echo '<br><hr width="100%"><br>';
  require(DIR_WS_CONTENT . CONTENT_SHOPPING_CART . '.tpl.php');
  echo '<br><hr width="100%"><br>';
  ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <form name="checkout_payment" method="post" onsubmit="return check_form();"><table border="0" width="100%" cellspacing="0" cellpadding="10">
      <tr>
        <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td  class="pageHeading">Payment Information</td>

<td align=right><?=tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', 'Payment Information');?></td>
  </tr>
</table>
          

        </td>
      </tr>
 
      <tr>
        <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="contentBoxHeadingLeft">&nbsp;</td>

    <td class="contentBoxHeadingCenter">Billing Address</td>
    <td class="contentBoxHeadingRight" nowrap>&nbsp;</td>
  </tr>
</table>
        
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
          <tr >
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
              <tr>
                <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>

                <td class="main" width="50%" valign="top">Please choose from your address book where you would like the invoice to be sent to.<br><br><a href="http://217.160.122.98/checkout_payment_address.php"><img src="templates/Original/images/buttons/english/button_change_address.png" border="0" alt="Change Address" title=" Change Address " width="133" height="22" class="transpng"></a></td>
                <td align="right" width="50%" valign="top"><table border="0" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main" align="center" valign="top"><b>Billing Address:</b><br><img src="images/arrow_south_east.gif" border="0" alt="" width="50" height="31"></td>
                    <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td class="main" valign="top">test test<br> main 1234<br> Dover, DE    19901<br> United States</td>

                    <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
 
      <tr>

        <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="contentBoxHeadingLeft">&nbsp;</td>
    <td class="contentBoxHeadingCenter">Payment Method</td>
    <td class="contentBoxHeadingRight" nowrap>&nbsp;</td>
  </tr>
</table>
        <table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">

          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
              <tr>
                <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td class="main" width="100%" colspan="2">Currently this is no payment method available to use on this order.</td>
                <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              </tr>
              <tr>

                <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td class="main" colspan="3" width=100%><b>Credit Card<script language="JavaScript" type="text/javascript">
<!--
function displayFields(){
var cObj = document.checkout_payment.protx_direct_cc_type;
  if (cObj != undefined && cObj != null){
    if (cObj.options[cObj.selectedIndex].value == 'SOLO' || cObj.options[cObj.selectedIndex].value == 'SWITCH'){
      document.getElementById('cc_start_date').style.display="";
      document.getElementById('cc_issue_number').style.display="";
    }else{
      document.getElementById('cc_start_date').style.display="none";
      document.getElementById('cc_issue_number').style.display="none";
    }
  }
}
-->
</script>
</b></td>
                    <td class="main" align="right">
<input type="hidden" name="payment" value="protx_direct">                    </td>

                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                  </tr>
                  <tr>
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                    <td colspan="4"><table border="0" cellspacing="0" cellpadding="2">
                      <tr >
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main">Credit Card Type:</td>

                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><select name="protx_direct_cc_type" onchange="displayFields();"><option value="UKE">Electron</option><option value="MC">Mastercard</option><option value="SOLO">Solo</option><option value="SWITCH">Switch / Maestro</option><option value="VISA">Visa</option><option value="DELTA">Visa Delta</option></select></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr >
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>

                        <td class="main">Credit Card Owner:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><input type="text" name="protx_direct_cc_owner" value="test test"></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr >
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main">Credit Card Number:</td>

                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><input type="text" name="protx_direct_cc_number"></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr id="cc_start_date">
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main">Credit Card Start Date:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>

                        <td class="main"><select name="protx_direct_cc_start_month"><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>&nbsp;<select name="protx_direct_cc_start_year"><option value="03">2003</option><option value="04">2004</option><option value="05">2005</option><option value="06">2006</option><option value="07">2007</option></select></td>

                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr >
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main">Credit Card Expiry Date:</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><select name="protx_direct_cc_expires_month"><option value="01">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select>&nbsp;<select name="protx_direct_cc_expires_year"><option value="07">2007</option><option value="08">2008</option><option value="09">2009</option><option value="10">2010</option><option value="11">2011</option><option value="12">2012</option><option value="13">2013</option><option value="14">2014</option><option value="15">2015</option><option value="16">2016</option></select></td>

                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                      <tr id="cc_issue_number">
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main">Issue Number (Switch/Maestro/Solo cards):</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><input type="text" name="protx_direct_cc_issue" size=2, maxlength=2></td>
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>

                      </tr>
                      <tr >
                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main">CVV Number (<a href="cvv.htm" target="_blank">More Info</a>)</td>
                        <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                        <td class="main"><script language="JavaScript" type="text/javascript">
<!--
displayFields();
-->
</script>
<input type="text" name="protx_direct_cc_cvv" size=4, maxlength=4></td>

                        <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                      </tr>
                    </table></td>
                    <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
                  </tr>
                </table></td>
                <td><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
              </tr>
            </table></td>

          </tr>
        </table></td>
      </tr>
 
<tr>
   <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="contentBoxHeadingLeft">&nbsp;</td>
    <td class="contentBoxHeadingCenter">Credits Available</td>

    <td class="contentBoxHeadingRight" nowrap>&nbsp;</td>
  </tr>
</table>
<table border="0" width="100%" cellspacing="1" cellpadding="0" class="contentBox">
     <tr><td><table border="0" width="100%" cellspacing="0" cellpadding="2" class="contentBoxContents">
       <tr><td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>
           <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
 <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >
   <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>     <td class="main" width="50%"><b>Gift Vouchers/Discount Coupons</b></td><td class="main">&nbsp;</td><td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td>  </tr>

<tr>
 <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td> <td class="main">
Enter Redeem Code&nbsp;&nbsp;</td> <td align="right"><input type="text" name="gv_redeem_code"></td> <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td></tr>
<tr>
 <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td> <td class="main">
&nbsp;</td> <td align="right"><input type="image" src="templates/Original/images/buttons/english/button_redeem.png" border="0" alt="Redeem" title=" Redeem "  onClick="submitFunction()"  name="submit_redeem" class="transpng" width="93" height="22"></td> <td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td></tr>
                           </table></td><td width="10"><img src="images/pixel_trans.gif" border="0" alt="" width="10" height="1"></td></tr></table></td></tr></table></td></tr>
</table>
<?
  echo '<br><hr width="100%"><br>';
  require(DIR_WS_CONTENT . CONTENT_ALL_PRODS . '.tpl.php');
  echo '<br><hr width="100%"><br>';
?>
