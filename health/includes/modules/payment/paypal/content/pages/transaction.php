<?php
/*
  $Id: transaction.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/
?>
<table width="100%" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td width="150"><?php echo $page->image('pixel.gif','','150','1'); ?></td>
  <td width="6"><?php echo $page->image('pixel.gif','','6','1'); ?></td>
  <td width="100%"><?php echo $page->image('pixel.gif','','432','1'); ?></td>
</tr>
<tr>
  <td colspan="3">
<?php if($paypal->isReversal()) {
  switch($paypal->info['payment_status']){
    case 'Reversed': $reversalType = ENTRY_REVERSED; break;
    case 'Refunded': $reversalType = ENTRY_REFUND; break;
  }
?>
    <span class="pptextbold"><?php echo $reversalType; ?></span>
<?php } else { ?>
    <span class="pptextbold"><?php echo ENTRY_PAYMENT_RECEIVED; ?></span>
<?php } ?>
    <span class="ppsmalltext">(ID # <?php echo $paypal->info['txn_id']; ?>)</span>
  </td>
</tr>
<tr><td colspan=3><br class="h10"></td></tr>
<tr><td colspan=3><br class="h10"></td></tr>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_CUSTOMER_NAME; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->customer['first_name'].' '.$paypal->customer['last_name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if(!$paypal->isReversal()) { ?>(The sender of this payment is <span class="ppem106"><?php echo ucfirst($paypal->customer['payer_status']); ?></span>)<?php } ?></td>
</tr>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_CUSTOMER_EMAIL; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->customer['payer_email']; ?></td>
</tr>
<?php if($paypal->info['for_auction'] === 'true') { ?>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_CUSTOMER_AUCTION_BUYER_ID; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->customer['auction_buyer_id']; ?></td>
</tr>
<?php } ?>
<tr><td colspan=3><hr class="dotted"></td></tr>
<tr><td colspan=3><br class="h10"></td></tr>
<tr valign=top>
  <td class="pplabel" align=right><?php echo ENTRY_TXN_GROSS_AMOUNT; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->format($paypal->txn['mc_gross'],$paypal->txn['mc_currency']); ?></td>
</tr>
<tr valign=top>
  <td class="pplabel" align=right><?php echo ENTRY_TXN_FEE_AMOUNT; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->format($paypal->txn['mc_fee'],$paypal->txn['mc_currency']); ?></td>
</tr>
<tr valign=top>
  <td class="pplabel" align=right><?php echo ENTRY_TXN_NET_AMOUNT; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->format($paypal->txn['mc_gross']-$paypal->txn['mc_fee'],$paypal->txn['mc_currency']); ?></td>
</tr>
<tr><td colspan=3><hr class="dotted"></td></tr>
<tr><td colspan=3><br class="h10"></td></tr>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_INFO_DATE; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->date($paypal->info['date_added']); ?></td>
</tr>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_INFO_TIME; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->time($paypal->info['date_added']).' '.$paypal->info['payment_time_zone']; ?></td>
</tr>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_TXN_STATUS; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->info['payment_status']; ?></td>
</tr>
<?php if($paypal->isPending()) { ?>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_TXN_PENDING_REASON; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->info['pending_reason']; ?></td>
</tr>
<?php } elseif($paypal->isReversal()) { ?>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_TXN_REASON; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->info['reason_code']; ?></td>
</tr>
<?php } ?>
<tr><td colspan=3><hr class="dotted"></td></tr>
<tr><td colspan=3><br class="h10"></td></tr>
<tr valign=top>
  <td class="pplabel" align=right><?php echo ENTRY_CUSTOMER_SHIPPING_ADDRESS; ?><br></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext"><?php echo $paypal->customer['address_name']; ?><br><?php echo $paypal->customer['address_street']; ?><br><?php echo $paypal->customer['address_city']; ?>, <?php echo $paypal->customer['address_state']; ?>  <?php echo $paypal->customer['address_zip']; ?><br><?php echo $paypal->customer['address_country']; ?>
  <?php if(!$paypal->isReversal()) { ?><br><span class="ppinlinegreen"><b><?php echo ucfirst($paypal->customer['address_status']); ?></b></span><?php } ?>
  </td>
</tr>
<tr><td colspan=3><hr class="dotted"></td></tr>
<tr><td colspan=3><br class="h10"></td></tr>
<tr valign="top">
  <td align="right" class="pplabel"><?php echo ENTRY_TXN_PAYMENT_TYPE; ?></td>
  <td><br class="text_spacer"></td>
  <td class="ppsmalltext" style="vertical-align: middle"><?php echo $paypal->displayPaymentType(); ?></td>
</tr>
</table>