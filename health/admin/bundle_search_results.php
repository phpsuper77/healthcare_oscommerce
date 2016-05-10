<?php
/*
  $Id: bundle_search_results.php,v 1.1.1.1 2005/12/03 21:36:01 max Exp $
*/

  require('includes/application_top.php');

  require_once 'JsHttpRequest.php';
  $JsHttpRequest =& new Subsys_JsHttpRequest_Php(CHARSET);
  $q = $_REQUEST['q'];
  $prid = $_REQUEST['prid'];

  $products_string = '';
  $products_query = tep_db_query("select distinct p.products_id, pd.products_name, count(sp.sets_id) is_bundle_set from " . TABLE_PRODUCTS . " p left join " . TABLE_SETS_PRODUCTS . " sp on sp.sets_id = p.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and (p.products_model like '%" . tep_db_input($q) . "%' or pd.products_name like '%" . tep_db_input($q) . "%') and p.products_id <> '" . (int)$prid . "' group by p.products_id having is_bundle_set = 0 order by p.sort_order, pd.products_name");
  while ($products = tep_db_fetch_array($products_query)) {
    $products_string .= '<option id="' . $products['products_id'] . '" value="prod_' . $products['products_id'] . '" style="COLOR:#555555">' . $products['products_name'] . '</option>';
  }

  $_RESULT = array(
    'tf' => '<select name="sets_select" size="16" style="width:100%">' . $products_string . '</select>'
  );
?>