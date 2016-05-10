<?php
/*
$Id: specials.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2003 osCommerce

Released under the GNU General Public License
*/

////
// Auto expire products on special
function tep_expire_specials() {
  tep_db_query("update " . TABLE_SPECIALS . " set status = 0, date_status_change = now() where status = '1' and now() >= expires_date and expires_date > 0");
}

function tep_check_selemaker(){
  Global $customer_groups_id, $salemaker_array;
  $salemaker_array = array();
  $salemaker_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type, sale_categories_all, sale_pricerange_from, sale_pricerange_to from " . TABLE_SALEMAKER_SALES . " where sale_status = '1' and groups_id = '" . (int)$customer_groups_id . "' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_deduction_value > 0) and (sale_date_end >= now() or sale_date_end = '0000-00-00') ");
  while ($salemaker_data = tep_db_fetch_array($salemaker_query)){
    $salemaker_array[] = array('sale_specials_condition' => $salemaker_data['sale_specials_condition'],
    'sale_deduction_value' => $salemaker_data['sale_deduction_value'],
    'sale_deduction_type' => $salemaker_data['sale_deduction_type'],
    'sale_pricerange_from' => $salemaker_data['sale_pricerange_from'],
    'sale_pricerange_to' => $salemaker_data['sale_pricerange_to'],
    'sale_categories_all' => (tep_not_null($salemaker_data['sale_categories_all'])?split(",", trim($salemaker_data['sale_categories_all'], " ,")):""));
  }
}
?>