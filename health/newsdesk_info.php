<?php

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSDESK_INFO);

// set application wide parameters
// this query set is for NewsDesk

// lets retrieve all $HTTP_GET_VARS keys and values..
$get_params = tep_get_all_get_params();
$get_params_back = tep_get_all_get_params(array('reviews_id')); // for back button
$get_params = substr($get_params, 0, -1); //remove trailing &
if ($get_params_back != '') {
    $get_params_back = substr($get_params_back, 0, -1); //remove trailing &
} else {
    $get_params_back = $get_params;
}


//$javascript = "support.js";


$content = CONTENT_NEWSDESK_INFO;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>