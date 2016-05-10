<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

  Featured Products V1.1
  Expiry Functions
*/

// Auto expire featured products
  function tep_expire_featured() {
    tep_db_query("update " . TABLE_FEATURED . " set status = 0, date_status_change = now() where status = '1' and now() >= expires_date and expires_date > 0");
  }
?>
