<?php

require('includes/application_top.php');
require('includes/functions/faqdesk_general.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_FAQDESK_SEARCH_RESULT);

$error = 0; // reset error flag to false
$errorno = 0;

if ( ($HTTP_GET_VARS['keywords'] == "" || strlen($HTTP_GET_VARS['keywords']) < 1) &&
	($HTTP_GET_VARS['dfrom'] == ""    || $HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING || strlen($HTTP_GET_VARS['dfrom']) < 1 ) &&
	($HTTP_GET_VARS['dto'] == ""      || $HTTP_GET_VARS['dto']   == DOB_FORMAT_STRING || strlen($HTTP_GET_VARS['dto']) < 1) &&
	($HTTP_GET_VARS['pfrom'] == ""    || strlen($HTTP_GET_VARS['pfrom']) < 1) &&
	($HTTP_GET_VARS['pto'] == ""      || strlen($HTTP_GET_VARS['pto']) < 1) ) {
		$errorno += 1;
		$error = 1;
}

if ($HTTP_GET_VARS['dfrom'] == DOB_FORMAT_STRING)
{	$dfrom_to_check = "";
}else{
	$dfrom_to_check = $HTTP_GET_VARS['dfrom'];
}

if ($HTTP_GET_VARS['dto'] == DOB_FORMAT_STRING){
	$dto_to_check = "";
}else{
	$dto_to_check = $HTTP_GET_VARS['dto'];
}

if (strlen($dfrom_to_check) > 0) {
	if (!tep_checkdate($dfrom_to_check, DOB_FORMAT_STRING, $dfrom_array)) {
		$errorno += 10;
	$error = 1;
	}
}

if (strlen($dto_to_check) > 0) {
	if (!tep_checkdate($dto_to_check, DOB_FORMAT_STRING, $dto_array)) {
		$errorno += 100;
		$error = 1;
	}
}

if (strlen($dfrom_to_check) > 0 && !(($errorno & 10) == 10) &&
	strlen($dto_to_check) > 0 && !(($errorno & 100) == 100)) {
	if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
		$errorno += 1000;
		$error = 1;
	}
}

if (strlen($HTTP_GET_VARS['pfrom']) > 0) {
	$pfrom_to_check = $HTTP_GET_VARS['pfrom'];
	if (!settype($pfrom_to_check, "double")) {
		$errorno += 10000;
		$error = 1;
	}
}

if (strlen($HTTP_GET_VARS['pto']) > 0) {
	$pto_to_check = $HTTP_GET_VARS['pto'];
	if (!settype($pto_to_check, "double")) {
		$errorno += 100000;
		$error = 1;
	}
}

if (strlen($HTTP_GET_VARS['pfrom']) > 0 && !(($errorno & 10000) == 10000) &&
	strlen($HTTP_GET_VARS['pto']) > 0 && !(($errorno & 100000) == 100000)) {
	if ($pfrom_to_check > $pto_to_check) {
		$errorno += 1000000;
		$error = 1;
	}
}

if (strlen($HTTP_GET_VARS['keywords']) > 0) {
	if (!tep_parse_search_string(stripslashes($HTTP_GET_VARS['keywords']), $search_keywords)) {
		$errorno += 10000000;
		$error = 1;
	}
}


//FILENAME_FAQDESK_SEARCH
if ($error == 1) {
	tep_redirect(tep_href_link(FILENAME_FAQDESK_INDEX, 'errorno=' . $errorno . '&' . tep_get_all_get_params(array('x', 'y')), 'NONSSL'));
} else {

	$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_FAQDESK_INDEX, '', 'NONSSL'));
	$breadcrumb->add(NAVBAR_TITLE2, tep_href_link(FILENAME_FAQDESK_SEARCH_RESULT, 'keywords=' . $HTTP_GET_VARS['keywords'] . '&search_in_description=' . $HTTP_GET_VARS['search_in_description'] . '&pfrom=' . $HTTP_GET_VARS['pfrom'] . '&pto=' . $HTTP_GET_VARS['pto'] . '&dfrom=' . $HTTP_GET_VARS['dfrom'] . '&dto=' . $HTTP_GET_VARS['dto']));

//$javascript = "support.js";
}

$content = CONTENT_FAQDESK_SEARCH_RESULT;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>

