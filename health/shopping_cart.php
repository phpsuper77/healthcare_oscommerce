<?php
/*
  $Id: shopping_cart.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
*/

    require("includes/application_top.php");
	include_once('controllers/front/FrontController.php');
	$controller = new FrontController();
	$canonical_tag = $controller->get_canonical_tag();


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
// BOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller
// Validate Cart for checkout
  $valid_to_checkout= true;
  $cart->get_products(true);
  if (!$valid_to_checkout) {
//    $messageStack->add_session('header', 'Please update your order ...', 'error');
//    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
// EOF: WebMakers.com Added: Attributes Sorter and Copier and Quantity Controller

  $content = CONTENT_SHOPPING_CART;
  $content = CONTENT_SHOPPING_CART;
  $javascript = $content . '.js.php';

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>