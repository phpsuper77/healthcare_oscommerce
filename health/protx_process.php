<?php
//
// +----------------------------------------------------------------------+
// + osCommerce, Open Source E-Commerce Solutions                         +
// +----------------------------------------------------------------------+
// | Copyright (c) 2006-2009 Tom Hodges-Hoyland                           |
// |                                                                      |
// | Portions Copyright (c) 2003 - 2007 osCommerce                        |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.gnu.org/copyleft/gpl.html.                                |
// +----------------------------------------------------------------------+
// | includes/modules/payment/protx_direct.php                            |
// | Released under GPL                                                   |
// | v2.22-v5.1 by Thomas Hodges-Hoyland (perfectpassion / tomh):         |
// |                             tom.hodges-hoyland@oscommerceproject.org |
// +----------------------------------------------------------------------+


require_once('includes/application_top.php');

if (!function_exists('protxCleanUrl'))
{
	function protxCleanUrl($url)
	{
	  return str_replace('&amp;', '&', $url);
	}
}

$nojs = (isset($_REQUEST['nojs']) ? TRUE : FALSE);

if (tep_not_null($_GET['action']))
{
  $action = $_GET['action'];

	if ($action == 'process')
	{

    // Code taken from checkout_process.php to prepare the order before processing payment

      // if the customer is not logged on, redirect them to the login page
      if (!tep_session_is_registered('customer_id'))
      {
        if ($nojs)
        {
          $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
          tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
        }
        else
        {
          echo '<script type="text/javascript"> top.location.href="'.tep_href_link(FILENAME_LOGIN, '', 'SSL').'";</script>';
          tep_exit();
        }
      }

    // if there is nothing in the customers cart, redirect them to the shopping cart page
      if ($cart->count_contents() < 1)
      {
        if ($nojs)
        {
        	tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        }
        else
        {
          echo '<script type="text/javascript"> top.location.href="'.tep_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL').'";</script>';
          tep_exit();
        }
      }

    // if no shipping method has been selected, redirect the customer to the shipping method selection page
      if (!tep_session_is_registered('shipping') || !tep_session_is_registered('sendto'))
      {
        if ($nojs)
        {
        	tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
        }
        else
        {
          echo '<script type="text/javascript"> top.location.href="'.tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'";</script>';
          tep_exit();
        }
      }

      if ( (tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')) )
      {
        if ($nojs)
        {
        	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
        }
        else
        {
          echo '<script type="text/javascript"> top.location.href="'.tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL').'";</script>';
          tep_exit();
        }
     }

    // avoid hack attempts during the checkout procedure by checking the internal cartID
      if (isset($cart->cartID) && tep_session_is_registered('cartID'))
      {
        if ($cart->cartID != $cartID) {
          if ($nojs)
          {
          	tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
          }
          else
          {
            echo '<script type="text/javascript"> top.location.href="'.tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'";</script>';
            tep_exit();
          }
        }
      }

    // load selected payment module
      require(DIR_WS_CLASSES . 'payment.php');
      $payment_modules = new payment($payment);

    // load the selected shipping module
      require(DIR_WS_CLASSES . 'shipping.php');
      $shipping_modules = new shipping($shipping);

      require(DIR_WS_CLASSES . 'order.php');
      $order = new order;

    // Stock Check
      $any_out_of_stock = false;
      if (STOCK_CHECK == 'true') {
        for ($i = 0, $n = sizeof($order->products); $i<$n; $i++) {
          if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
            $any_out_of_stock = true;
          }
        }
        // Out of Stock
        if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
          if ($nojs)
          {
            tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
          }
          else
          {
            echo '<script type="text/javascript"> top.location.href="'.tep_href_link(FILENAME_SHOPPING_CART).'";</script>';
            tep_exit();
          }
        }
      }

      $payment_modules->update_status();

      if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
        tep_redirect(protxCleanUrl(tep_href_link(( defined('ONE_PAGE_POST_PAYMENT')?FILENAME_CHECKOUT_CONFIRMATION:FILENAME_CHECKOUT_PAYMENT ), 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL')));
      }

      require(DIR_WS_CLASSES . 'order_total.php');
      $order_total_modules = new order_total;

      $order_totals = $order_total_modules->process();
    //END CHECKOUT_PROCESS.PHP CODE
    if ( defined('ONE_PAGE_POST_PAYMENT') && ereg(FILENAME_CHECKOUT_CONFIRMATION,$_SERVER['HTTP_REFERER'])){
      if (is_array($payment_modules->modules)) {
        $payment_modules->pre_confirmation_check();
      }
    }


		$response = $GLOBALS['protx_direct']->start_transaction();

		if ($response['authorised'] === FALSE)
		{
		  $msg = 'Sorry your payment could not be processed.';
		  if ($nojs)
		  {
		  	tep_redirect(protxCleanUrl(tep_href_link(( defined('ONE_PAGE_POST_PAYMENT')?FILENAME_CHECKOUT_CONFIRMATION:FILENAME_CHECKOUT_PAYMENT ), 'payment_error=protx_direct&error='.urlencode($msg . ' (' . $response['detail'].')'), 'SSL')));
		  }
		  else
		  {
  			echo '<strong><span style="color: red;">'.$msg.'</span></strong><br><br>'.$response['detail'];
		  }
		}
		elseif ($response['authorised'] === TRUE)
		{
		  tep_session_register('protx_id');
		  $_SESSION['protx_id'] = $GLOBALS['protx_direct']->protx_id;
		  if ($nojs)
		  {
		  	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));
		  }
		  else
		  {
		    echo '<script type="text/javascript">window.location.href="'.tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL').'";</script>';
		    tep_exit();
		  }
		}
		elseif ($response['authorised'] == '3DAUTH')
		{
		  tep_session_register('protx_PAReq');
		  $_SESSION['protx_PAReq'] = $response['detail']['PAReq'];

		  tep_session_register('protx_MD');
		  $_SESSION['protx_MD'] = $response['detail']['MD'];

		  tep_session_register('protx_ACSURL');
		  $_SESSION['protx_ACSURL'] = $response['detail']['ACSURL'];

		  if ($nojs)
		  {
		    tep_redirect(protxCleanUrl(tep_href_link(FILENAME_PROTX_PROCESS, 'nojs&action=iframe&termurl='.urlencode(tep_href_link(FILENAME_PROTX_PROCESS, 'nojs&action=3Dreturn&protx_id='.$GLOBALS['protx_direct']->protx_id, 'SSL')), 'SSL')));
		  }
		  else
		  {
		    echo '<iframe src="'.tep_href_link(FILENAME_PROTX_PROCESS, 'action=iframe&termurl='.urlencode(str_replace('&amp;', '&', tep_href_link(FILENAME_PROTX_PROCESS, 'action=3Dreturn&protx_id='.$GLOBALS['protx_direct']->protx_id, 'SSL'))), 'SSL').'" id="3Dsecure" style="width: 100%; height: 400px; border: none;"></iframe>';
		    tep_exit();
		  }
		}
	}
	elseif ($action == 'iframe')
	{
	  echo
        '<html>
          <head>
          <title>3D-Secure Validation</title>
            <script type="text/javascript">
             function OnLoadEvent() { document.getElementById(\'theform\').submit(); }
            </script>
          </head>
          <body OnLoad="OnLoadEvent();">
            <form id="theform" action="'.$_SESSION['protx_ACSURL'].'" method="POST" onsubmit="document.getElementById(\'submit_go\').disabled=\'true\';" />
              <input type="hidden" name="PaReq" value="'.$_SESSION['protx_PAReq'].'" />
              <input type="hidden" name="TermUrl" value="'.urldecode($_GET['termurl']).'" />
              <input type="hidden" name="MD" value="'.$_SESSION['protx_MD'].'" />
            <noscript>
              <center>
                <p>Your card issuer requires you to validate this transaction using Verified by Visa / MasterCard SecureCode</p>
                <p>Please click button below to be transferred to your bank\'s website to authenticate your card</p>
                <p><input type="submit" value="Go" id="submit_go" /></p>
              </center>
            </noscript>
           </form>
          </body>
        </html>';

    tep_session_unregister('protx_ACSURL');
    tep_session_unregister('protx_PAReq');
    tep_session_unregister('protx_MD');

    tep_exit();
	}
	elseif ($action == '3Dreturn')
	{
    require(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment($payment);

    $GLOBALS['protx_direct']->protx_id = (int)$_GET['protx_id'];
    $response = $GLOBALS['protx_direct']->do3Dreturn();

		if ($response['authorised'] === FALSE)
		{
		  $msg = 'Sorry your payment could not be processed.';
		  if ($nojs)
		  {
		    tep_redirect(protxCleanUrl(tep_href_link(( defined('ONE_PAGE_POST_PAYMENT')?FILENAME_CHECKOUT_CONFIRMATION:FILENAME_CHECKOUT_PAYMENT ), 'payment_error=protx_direct&error='.urlencode($msg . ' (' . $response['detail'].')'), 'SSL')));
		  }
		  else
		  {
			  echo '<strong><span style="color: red;">'.$msg.'</span></strong><br><br>'.$response['detail'];
		  }
		}
		elseif ($response['authorised'] === TRUE)
		{
		  tep_session_register('protx_id');
		  $_SESSION['protx_id'] = $GLOBALS['protx_direct']->protx_id;
		  if ($nojs)
		  {
		  	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL'));
		  }
		  else
		  {
		    echo '<script type="text/javascript">top.location.href="'.tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL').'";</script>';
		    tep_exit();
		  }
		}
	}
}