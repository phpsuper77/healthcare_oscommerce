<?php
/**
 * Copyright (C) 2006 Google Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

if (!function_exists('domxml_new_doc')) {
  require_once((defined('DIR_FS_CATALOG_MODULES')?DIR_FS_CATALOG_MODULES:DIR_WS_MODULES).'../classes/xml4.php');
}
// defined or not defined
//define('GOOGLE_FORCE_TO_USA','1');

SetGlobalVariables();

function SetGlobalVariables() {
    global $currency;

    /*
     * The $GLOBALS["logfile"] variable identifies the file where 
     * messages will be logged. You can change the variable's value
     * to change the log file's location.
     */
    $GLOBALS["logfile"] = DIR_FS_CATALOG."temp/log.out";

    /*
     * The $GLOBALS["error_report_type"] variable specifies the
     * manner in which errors will be reported. There are three
     * possible values:
     * 1 = Log the error message to the error log file
     * 2 = Display the error message in the browser
     * 3 = Log the error message to the error log file
     *         and also display it in the browser
     *
     * Error messages are for debugging purposes only. When you are
     * done with integration, change $GLOBALS["error_report_type"] to 1
     * so that no error messages will be displayed to the end user.
     *
     * You may also change the value of the $GLOBALS["error_logfile"]
     * variable to change the location where errors will be logged.
     */
    $GLOBALS["error_report_type"] = "1";
    $GLOBALS["error_logfile"] = DIR_FS_CATALOG."temp/error.log";

    /*
     * The $GLOBALS["currency"] variable specifies a default currency
     * that is used in several places throughout the PHP libraries.
     * You will need to update this value if you sell products in
     * currencies other than U.S. dollars. The variable's value should
     * be a three-letter ISO 4217 currency code:
     * http://www.iso.org/en/prods-services/popstds/currencycodeslist.html
     *
     * If you sell products in multiple currencies, you may need to
     * implement a function that returns the appropriate currency code
     * for each user.
     */
//  e.g.  $GLOBALS["currency"] = "USD";
    if ( defined('GOOGLE_FORCE_TO_USA') ) {
      $GLOBALS["currency"] = "USD";
    }else{
      $GLOBALS["currency"] = $currency;
    }
    

    // This constant identifies the location of the Google Checkout XML schema
    $GLOBALS["schema_url"] = "http://checkout.google.com/schema/2";

    /* 
     * These two function calls set global variables for your
     * merchant ID and merchant key 
     */
    $GLOBALS["merchant_id"] = GetMerchantID();
    $GLOBALS["merchant_key"] = GetMerchantKey();

    /* These constants specify the URLs to which Google Checkout API requests 
     * are sent
     * +++ CHANGE ME +++
     * Please remember that your production systems must send requests to
     * https://checkout.google.com rather than https://sandbox.google.com
     */
    $base_url = "https://sandbox.google.com/cws/v2/Merchant/" .  $GLOBALS["merchant_id"];
    if(MODULE_PAYMENT_GOOGLECHECKOUT_MODE == 'https://checkout.google.com/') 
      $base_url = "https://checkout.google.com/cws/v2/Merchant/" .  $GLOBALS["merchant_id"];
    
    $GLOBALS["checkout_url"] = $base_url . "/checkout";
    $GLOBALS["checkout_diagnose_url"] = $base_url . "/checkout/diagnose";
    $GLOBALS["request_url"] = $base_url . "/request";
    $GLOBALS["request_diagnose_url"] = $base_url . "/request/diagnose";

    /*
     * This function call indicates that the error_handler function
     * should be called if any errors occur when executing the code 
     */
    // This is not very good idea for OSC at least
    //set_error_handler("error_handler");

    // This constant is used to report errors caused by missing parameters
    $GLOBALS["mp_type"] = "MISSING_PARAM";
}

/*
 * This function securely fetches and returns your Merchant ID.
 */
function GetMerchantID() {
    /*
     * +++ CHANGE ME +++
     * Please set the return value to your Google Checkout merchant ID.
     * This change is mandatory or this code will not work.
     */
    return MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID;
}

/*
 * This function securely fetches and returns your Merchant Key.
 */
function GetMerchantKey() {
    /*
     * +++ CHANGE ME +++
     * Please set the return value to your Google Checkout merchant key.
     * This change is mandatory or this code will not work.
     */
    return MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY;
}

/**
 * The CalcHmacSha1 function computes the HMAC-SHA1 signature that you need
 * to send a Checkout API request. The signature is used to verify the
 * integrity of the data in your API request.
 *
 * @param    $data    message data
 * @return   $hmac    value of the calculated HMAC-SHA1
 */
function CalcHmacSha1($data) {

    $key = $GLOBALS["merchant_key"];

    // Check for errors
    $error_function_name = "CalcHmacSha1()";

    // The $data and $key variables must be populated
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "data", $data);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "GLOBALS[\"merchant_key\"]", $key);

    $blocksize = 64;
    $hashfunc = 'sha1';

    if (strlen($key) > $blocksize) {
        $key = pack('H*', $hashfunc($key));
    }

    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    $hmac = pack('H*', $hashfunc(($key^$opad).pack('H*', $hashfunc(($key^$ipad).$data))));
    return $hmac;
}


/**
 * The SendRequest function verifies that you have provided values for
 * all of the parameters needed to send a Google Checkout
 * Checkout or Order Processing API request. It then logs the request, 
 * calls the GetCurlResponse function to execute the request, 
 * and logs the response.
 *
 * @param    $request     XML API request
 * @param    $post_url    URL address to which the request will be sent
 * @return   $response    synchronous response from the Google Checkout 
 *                            server
 */

function SendRequest($request, $post_url) {

    // Check for errors
    $error_function_name = "SendRequest()";

    // Check for missing parameters
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "request", $request);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "post_url", $post_url);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "GLOBALS[\"merchant_id\"]", $GLOBALS["merchant_id"]);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "GLOBALS[\"merchant_key\"]", $GLOBALS["merchant_key"]);

    // Log outgoing message
    LogMessage($GLOBALS["logfile"], $request);

    // Execute the API request and capture the response to the request
    $response = GetCurlResponse($request, $post_url);

    // Log incoming message
    LogMessage($GLOBALS["logfile"], $response);

    // Return the response to the API request
    return $response;
}

/**
 * The GetCurlResponse function sends an API request to Google Checkout 
 * and returns the response. The HTTP Basic Authentication scheme is 
 * used to authenticate the message.
 *
 * This function utilizes cURL, client URL library functions.
 * cURL is supported in PHP 4.0.2 or later versions, documented at
 * http://us2.php.net/curl
 *
 * @param    $request     XML API request
 * @param    $post_url    URL address to which the request will be sent
 * @return   $response    synchronous response from the Google Checkout 
 *                            server
 */

function GetCurlResponse($request, $post_url) {
  global $insert_id;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    /*
     * The function sets HTTP headers and uses your merchant ID and
     * merchant key if you are sending a Checkout API request.
     */
    $pos = strpos($post_url, "checkout");
    //if ($pos !== false) {
        // Set Content-type and Accept header information
        $header = array();
        $header[] = "Content-type: application/xml";
        $header[] = "Accept: application/xml";
        curl_setopt($ch, CURLOPT_USERPWD, MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTID . 
            ":" . MODULE_PAYMENT_GOOGLECHECKOUT_MERCHANTKEY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    //}
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

    // Execute the API request.
    $response = curl_exec($ch);
        
    /*
     * Verify that the request executed successfully. Note that a
     * successfully executed request does not mean that your request
     * used properly formed XML.
     */
/*
echo '<pre>';var_dump($response);echo '</pre>';     
echo '<pre>';var_dump(curl_errno($ch));echo '</pre>';     
echo '<pre>';var_dump(curl_errno($ch),curl_error($ch));echo '</pre>';
die;
*/
    if (curl_errno($ch)) {
        curl_close($ch);
        // clear all order stuff
        tep_db_query("delete from " . TABLE_ORDERS . " where orders_id='" . $insert_id . "'");
        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS . " where orders_id='" . $insert_id . "'");
        tep_db_query("delete from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id='" . $insert_id . "'");
        tep_db_query("delete from " . TABLE_ORDERS_TOTAL . " where orders_id='" . $insert_id . "'");
        tep_db_query("delete from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id='" . $insert_id . "'");
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=googlecheckout', 'SSL'));
        //trigger_error(curl_error($ch), E_USER_ERROR);
    } else {
        curl_close($ch);
    }

    // Return the response to the API request
    return $response;
}

/**
 * The DisplayDiagnoseResponse function is a debugging function that 
 * sends a Google Checkout API request and then evaluates the Google Checkout 
 * response to determine whether the request used valid XML. If the request 
 * did not use valid XML, the function displays an error message and a link
 * where you can edit the XML and then try to validate it again.
 *
 * This function calls the SendRequest function to execute the API request.
 *
 * @param    $request     XML API request
 * @param    $post_url    URL address to POST the form
 * @param    $xml         Unencoded version of XML used in API request
 * @param    $action      This variable indicates whether the function
 *                              should print a form on the page containing
 *                              information about the API request if the XML 
 *                              is invalid.
 * @return    $response   Boolean (true=XML is valid;false=XML is invalid)
 */

function DisplayDiagnoseResponse($request, $post_url, $xml, $action) {

    // Execute the API request and capture the Google Checkout server's response
    $diagnose_response = 
        SendRequest($request, $post_url);

    /*
     * If the function finds that the request contained valid XML, the
     * $validated variable will be set to true
     */
    $validated = false;

    $dom_response = domxml_open_mem($diagnose_response);
    $root_element = $dom_response->document_element();
    $root_tag = $root_element->tagname();

    /*
     * This if-else block determines whether the API response indicates
     * that the response contained invalid XML or if there was some other
     * problem associated with the request, such as an invalid signature.
     */
    if ($root_tag == "diagnosis") {
        $error_message = $root_element->get_elements_by_tagname("warnings");
        if (!(empty($error_message))) {
            $string = $error_message[0]->get_elements_by_tagname("string");
            $result = $string;
        } else {
            $validated = true;
        }
    } else if ($root_tag == "error") {
        $error_message = $root_element->get_elements_by_tagname("error-message");
        $result = $error_message;
        $warning_messages = $root_element->get_elements_by_tagname("string");
    } else if ($root_tag == "request-received") {
        $validated = true;
    }

    /*
     * If the request is invalid, print the reason that the request is
     * invalid if the $GLOBALS["error_report_type"] variable indicates 
     * that errors should be displayed in the user's browser. Also display
     * a link to a tool where the user can edit the XML request unless the
     * validation request was submitted from that tool.
     */
    if ($validated == false && ($GLOBALS["error_report_type"] == "2" || 
        $GLOBALS["error_report_type"] == "3"))
    {
        echo "<tr><td style=\"color:red\"><p>" .
            "<span style=\"text-align:center\">" .
            "<h2>This XML is NOT Validated!</h2></span></p>";
        foreach($result as $message) {
            echo "<p style=\"text-align:left\"><b>" . 
            htmlentities($message->get_content()) . "</b></p>";
        }
        if (($root_tag == "error") && (sizeof($warning_messages)) > 0) {
            foreach ($warning_messages as $message) {
                echo "<p style=\"text-align:left\"><b>" . 
                htmlentities($message->get_content()) . "</b></p>";
            }
        }
        if ($action == "debug") {
            echo "<p><form method=POST action=DebuggingTool.php>";
            echo "<input type=\"hidden\" name=\"xml\" value=\"" . 
                htmlentities($xml) . "\"/>";
            echo "<input type=\"hidden\" name=\"openFile\" value=\"false\"/>";
            echo "<input type=\"hidden\" name=\"toolType\" " .
                "value=\"Validate XML\"/>";
            echo "<input type=\"submit\" name=\"Debug\" value=\"Debug XML\"/>";
            echo "</form></p></td></tr>";
        }
    }

    /*
     * Return a Boolean value indicating whether the request
     * contained valid XML.
     */
    return $validated;
}

/**
 * The CheckForError function determines whether a parameter has a null
 * value and prints the appropriate error message if the parameter does
 * have a null value.
 *
 * @param    $error_type       The type of error being flagged.
 *                                   e.g. GLOBALS["MISSING_PARAM"]
 * @param    $function_name    The function where the error occurred
 * @param    $param_name       The name of the parameter being checked
 * @param    $param_value      The parameter value submitted to the function
 */

function CheckForError($error_type, $function_name, $param_name, 
    $param_value="") {

    // Log an error if the parameter value is null
    if ($param_value == "") {
        echo '<!--';
        print_r(debug_backtrace());
        trigger_error(error_msg($error_type, $function_name, 
            $param_name, $param_value), E_USER_ERROR);
    }
}


/*
 * The error_msg function returns the error message that should be
 * logged for the $error_type.
 *
 * @param    $error_type       The type of error being flagged.
 *                                 e.g. GLOBALS["MISSING_PARAM"],
 *                                 "INVALID_INPUT_ARRAY", "MISSING_CURRENCY"
 *                                 "MISSING_TRACKING"
 * @param    $function_name    The function where the error occurred
 * @param    $param_name       The name of the parameter being checked
 * @param    $param_value      The parameter value submitted to the function
 * @return   $errstr           error message 
 */
function error_msg($error_type, $function_name, $param_name="", 
    $param_value="") {

    /*
     * This code block selects the error message that corresponds to
     * the value of the $error_type variable.
     *
     * +++ CHANGE ME +++
     * You can change any of the error messages logged for these errors.
     */

    switch ($error_type) {

        /*
         * MISSING_PARAM error
         * A function call omits a required parameter.
         */
        case "MISSING_PARAM":
            $errstr = "Error calling function \"" . $function_name . 
                "\": Missing Parameter: \"$" . $param_name . 
                "\" must be provided.";
            break;

        /*
         * INVALID_INPUT_ARRAY error
         * AddAreas() function called with invalid value for
         * $state_areas or $zip_areas parameter
         */
        case "INVALID_INPUT_ARRAY":
            $errstr = "Error calling function \"" . $function_name . 
                "\": Invalid Input: \"" . $param_name . 
                "\" should be an array.";
            break;

        /*
         * MISSING_CURRENCY error
         * The $GLOBALS["currency"] value is empty.
         */
        case "MISSING_CURRENCY":
            $errstr = "Error calling function \"" . $function_name . 
                "\": Missing Parameter: \"\$GLOBALS[\"currency\"]\"" . 
                "must be set when the \"\$amount\" is set.";
            break;

        /*
         * INVALID_ALLOW_OR_EXCLUDE_VALUE error
         * AddAreas() function called with invalid value for 
         * $allow_or_exclude parameter.
         */
        case "INVALID_ALLOW_OR_EXCLUDE_VALUE";
            $errstr = "Error calling function \"" . $function_name . 
                "\": Areas must either be allowed or excluded.";
            break;

        /*
         * MISSING_TRACKING error
         * The ChangeShippingInfo() function in 
         * OrderProcessingAPIFunctions.php is being called without 
         * specifying a tracking number even though a shipping 
         * carrier is specified.
         */
        case "MISSING_TRACKING":
            $errstr = "Error calling function \"" . $function_name . 
                "\": Missing Parameter: \"\$tracking_number\" must be set " .
                "if the \"\$carrier\" is set.";
            break;

        default:
            break;
    }

    return $errstr;
}


/*
 * The error_handler function is called by the PHP function 
 * trigger_error. The SetGlobalVariables function specifies that 
 * the error_handler function should be used to handle errors.
 *
 * You may modify this function to log Google Checkout errors in a 
 * manner consistent with the rest of your web application.
 *
 * This function logs errors to $GLOBALS["error_logfile"], which
 * is defined in the SetGlobalVariables function.
 */

function error_handler($errno, $errstr, $errfile, $errline) {

    /*
     * +++ CHANGE ME +++
     * You may modify this function to log errors differently.
     */
    $time = date("r", time());

    $errstr_echo = $time . "<br>";
    $errstr_echo .= "Fatal error in line $errline of file" . $errfile . "<br>";
    $errstr_echo .= "- " . $errstr . "<br><br>";
    
    $errstr_log = $time . "\r\n";
    $errstr_log .= "Fatal error in line $errline of file" . $errfile . "\r\n";
    $errstr_log .= "- " . $errstr . "\r\n\r\n";

    if ($GLOBALS["error_report_type"] == 2 || $GLOBALS["error_report_type"] == 3) {
        echo $errstr_echo;
    }
    if ($GLOBALS["error_report_type"] == 1 || $GLOBALS["error_report_type"] == 3) {
        error_log($errstr_log, 3, $GLOBALS["error_logfile"]);  
    }

    die();
}

/**
 * The logMessage function logs a message to a local file. The function
 * also logs the time that the message is logged.
 * 
 * @param  $log           The filename to which the message should be logged
 * @param  $message       message to be written
 */
function LogMessage($log, $message) {

    if ($GLOBALS["error_report_type"] == "1" || $GLOBALS["error_report_type"] == "3")
    { 
    // Print out the notification message to a local file
    if (!$log_file = fopen($log, "a")) {
        $errstr = "Cannot open '" . $log . "' file.";
        trigger_error($errstr, E_USER_ERROR);
    }
    fwrite($log_file, sprintf("\r\n\r\n%s", date("r", time())));
    fwrite($log_file, sprintf("\r\n%s", $message));
    fclose($log_file);
    }
}

/** End of file **/

?>
