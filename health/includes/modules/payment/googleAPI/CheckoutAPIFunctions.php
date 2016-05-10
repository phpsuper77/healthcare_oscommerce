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

/******************** Checkout Shopping Cart API ***********************/

/**
 * The CreateItem function constructs the XML for a single
 * <item> in a shopping cart.
 *
 * @param     $item_name            item name
 * @param     $item_desc            item description
 * @param     $quantity             quantity
 * @param     $unit_price           unit price
 * @param     $tt_selector          name of the tax table to 
 *                                      select for this item
 * @param     $private_item_data    XML to be appended as a child to 
 *                                      <merchant-private-item-data> 
 * @return    $dom_item_obj         <item> XML DOM
 */
function CreateItem($item_name, $item_desc, $quantity, $unit_price, 
    $tt_selector="", $private_item_data="") {

    global $dom_items_obj;
    $unit_price = (string)$unit_price;
    /*
     * Each of these parameters must have a value to create an <item>
     */
    $error_function_name = "CreateItem()";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "item_name", $item_name);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "item_description", $item_desc);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "quantity", $quantity);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "unit_price", $unit_price);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "GLOBALS[\"currency\"]", $GLOBALS["currency"]);

    // HTML entities need to be escaped properly
    $item_name = htmlentities($item_name);
    $item_desc = htmlentities($item_desc);

    // Create the <items> tag if this is the first item to be created
    if ($dom_items_obj == "") {
        $dom_items_obj = domxml_new_doc("1.0");
        $dom_items_obj->append_child(
            $dom_items_obj->create_element("items"));
    }

    $dom_item_obj = domxml_new_doc("1.0");

    // Create the <item> tag for the item to be created
    $dom_item = 
        $dom_item_obj->append_child(
            $dom_item_obj->create_element("item"));

    // Add the item name to the XML
    $dom_item_name = 
        $dom_item->append_child(
            $dom_item_obj->create_element("item-name"));

    $dom_item_name->append_child(
        $dom_item_obj->create_text_node($item_name));
    
    // Add the item description to the XML
    $dom_item_desc = $dom_item->append_child(
        $dom_item_obj->create_element("item-description"));

    $dom_item_desc->append_child(
        $dom_item_obj->create_text_node($item_desc));

    // Add the quantity to the XML
    $dom_quantity = $dom_item->append_child(
        $dom_item_obj->create_element("quantity"));

    $dom_quantity->append_child(
        $dom_item_obj->create_text_node($quantity));

    // Add the unit price for the item to the XML
    $dom_price = $dom_item->append_child(
        $dom_item_obj->create_element("unit-price"));

    $dom_price->set_attribute("currency", $GLOBALS["currency"]);
    $dom_price->append_child(
        $dom_item_obj->create_text_node($unit_price));

    /*
     * If there is an alternate-tax-table associated with this item,
     * specify the table's name using the <tax-table-selector> tag.
     */
    if ($tt_selector != "") {
        $dom_tt_selector = 
            $dom_item->append_child(
                 $dom_item_obj->create_element("tax-table-selector"));

        $dom_tt_selector->append_child(
            $dom_item_obj->create_text_node($tt_selector));
    }

    /*
     * If you have provided a value for the $private_item_data
     * variable, that value will be printed inside the
     * <merchant-private-item-data> tag.
     */

    if ($private_item_data != "") {
        $dom_private_item_data = domxml_open_mem($private_item_data);

        $dom_new_private_item_data = 
            $dom_item->append_child(
                $dom_item_obj->create_element("merchant-private-item-data"));

        $dom_private_item_data_root = 
            $dom_private_item_data->document_element();

        $dom_new_private_item_data->append_child(
            $dom_private_item_data_root->clone_node(true));
    }

    /*
     * The newly created item is added as a child of the <items> tag.
     */
    $dom_items_root = $dom_items_obj->document_element();
    $dom_item_root = $dom_item_obj->document_element();
    $dom_items_root->append_child($dom_item_root->clone_node(true));

    return $dom_item_obj;
}

function CreateNonUsPlaceArea($area_type, $area_place) {

    $error_function_name = "CreateNonUsPlaceArea(" . $area_type . ":" . 
        $area_place . ")";

    // The area_type must be specified for the function call to execute.
    CheckForError($GLOBALS["mp_type"], $error_function_name, $area_type, 
        $area_place);

    $dom_area_obj = domxml_new_doc("1.0");
    $dom_area = $dom_area_obj->append_child(
        $dom_area_obj->create_element("postal-area"));
    $dom_country_code = $dom_area->append_child($dom_area_obj->create_element("country-code"));
    $dom_country_code->append_child(
            $dom_area_obj->create_text_node($area_place));
    return $dom_area_obj;    
}

/**
 * The CreateShoppingCart function constructs the XML for the
 * <shopping-cart> element in a Checkout API request. Since the
 * <shopping-cart> element contains all of the items in the cart,
 * this function must be called after you have already called
 * the CreateItem function for each item in the order.
 *
 * @param   $cart_expiration          date and time in 
 *                                        "yyyy-mm-ddThh:mm:ss" format 
 * @param   $merchant_private_data    XML to be appended as a child to 
 *                                        <merchant-private-data> 
 * @return  $dom_shopping_cart_obj    <shopping-cart> XML DOM
 */
function CreateShoppingCart($cart_expiration="", $merchant_private_data="") {

    global $dom_items_obj, $dom_shopping_cart_obj;

    // Check for errors
    $error_function_name = "CreateShoppingCart()";
    
    /*
     * There must be at least one item in the shopping cart by the 
     * time you call this function or the function will log an error.
     */
    if ($dom_items_obj == "") {
        trigger_error("You must have at least one item.", E_USER_ERROR);
    }

    $dom_shopping_cart_obj = domxml_new_doc("1.0");

    // Create the <shopping-cart> element
    $dom_shopping_cart = $dom_shopping_cart_obj->append_child(
        $dom_shopping_cart_obj->create_element("shopping-cart"));

    $dom_items_root = $dom_items_obj->document_element();
    $dom_shopping_cart->append_child($dom_items_root->clone_node(true));
    
    /*
     * If there is an expiration date ($cart_expiration) for the cart,
     * include it in the <shopping-cart> XML.
     */
    if ($cart_expiration != "") {

        $dom_cart_expiration = $dom_shopping_cart->append_child(
            $dom_shopping_cart_obj->create_element("cart-expiration"));

        $dom_good_until_date = $dom_cart_expiration->append_child(
            $dom_shopping_cart_obj->create_element("good-until-date"));

        $dom_good_until_date->append_child(
            $dom_shopping_cart_obj->create_text_node($cart_expiration));
    }

    /*
     * If you have provided a value for the $merchant_private_data
     * variable, that value will be printed inside the
     * <merchant-private-data> tag.
     */
    if ($merchant_private_data != "") {
        $dom_merchant_private_data = 
            domxml_open_mem($merchant_private_data);

        $dom_new_merchant_private_data = $dom_shopping_cart->append_child(
            $dom_shopping_cart_obj->create_element("merchant-private-data"));

        $dom_merchant_private_data_root = 
            $dom_merchant_private_data->document_element();

        $dom_new_merchant_private_data->append_child(
            $dom_merchant_private_data_root->clone_node(true));
    }

    return $dom_shopping_cart_obj;
}

/**
 * The CreateUsCountryArea function is a wrapper function that calls the
 * CreateUsPlaceArea function. The CreateUsPlaceArea function, in turn,
 * creates and returns a <us-country-area> XML block.
 *
 * @param   $area_place       The U.S. region that should be included
 *                                in the XML block. Valid values are
 *                                CONTINENTAL_48, FULL_50_STATES and ALL.
 * @return  <us-country-area> XML DOM
 */
function CreateUsCountryArea($area_place) {
    return CreateUsPlaceArea("country", $area_place);
}
function CreateNonUsCountryArea($area_place) {
    return CreateNonUsPlaceArea("country", $area_place);
}

/**
 * The CreateUsStateArea function is a wrapper function that calls the
 * CreateUsPlaceArea function. The CreateUsPlaceArea function, in turn,
 * creates and returns a <us-state-area> XML block.
 *
 * @param   $area_place     The U.S. state that should be included
 *                              in the XML block. The value should be a
 *                              two-letter U.S. state abbreviation.
 * @return  <us-state-area> XML DOM
 */
function CreateUsStateArea($area_place) {
    return CreateUsPlaceArea("state", $area_place);
}

/**
 * The CreateUsZipArea function is a wrapper function that calls the
 * CreateUsPlaceArea function. The CreateUsPlaceArea function, in turn,
 * creates and returns a <us-zip-area> XML block.
 *
 * @param   $area_place   The zip code that should be included
 *                            in the XML block. The value should be a
 *                            five-digit zip code or a zip code pattern.
 * @return  <us-zip-area> XML DOM
 */
function CreateUsZipArea($area_place) {
    return CreateUsPlaceArea("zip", $area_place);
}

/**
 * The CreateUsPlaceArea function creates <us-country-area>, 
 * <us-state-area> and <us-zip-area> XML blocks.
 *
 * @param   $area_type       The type of XML object to be created. Valid
 *                               values are "country", "state" and "zip".
 * @param   $area_place      This value corresponds to the accepted
 *                               $area_place parameter values for the
 *                               CreateUsCountryArea, CreateUsStateArea and
 *                               CreateUsZipArea functions.
 * @return  $dom_area_obj    <us-country-area>, <us-state-area> 
 *                               or <us-zip-area> XMLDOM 
 */
function CreateUsPlaceArea($area_type, $area_place) {

    $error_function_name = "CreateUsPlaceArea(" . $area_type . ":" . 
        $area_place . ")";

    // The area_type must be specified for the function call to execute.
    CheckForError($GLOBALS["mp_type"], $error_function_name, $area_type, 
        $area_place);

    $dom_area_obj = domxml_new_doc("1.0");

    // Create the parent XML element for the $area_type
    $dom_area = $dom_area_obj->append_child(
        $dom_area_obj->create_element("us-" . $area_type . "-area"));

    /*
     * Create the elements that contain the $area_place data
     */
    if ($area_type == "state") {

        $dom_area_place = $dom_area->append_child(
            $dom_area_obj->create_element("state"));
        $dom_area_place->append_child(
            $dom_area_obj->create_text_node($area_place));

    } elseif ($area_type == "zip") {

        $dom_area_place = 
            $dom_area->append_child(
                $dom_area_obj->create_element("zip-pattern"));
        $dom_area_place->append_child(
            $dom_area_obj->create_text_node($area_place));

    } elseif ($area_type == "country") {
        $dom_area_place = 
            $dom_area->set_attribute("country-area", $area_place);
    }

    return $dom_area_obj;
}

/**
 * The CreateTaxArea function creates a <tax-area> XML DOM, which identifies
 * a geographic region where a tax rate applies.
 *
 * @param   $tax_area_type       Valid values are "country", 
 *                                   "state" and "zip"
 * @param   $tax_area_place      See the valid values for the
 *                                   $area_place parameter of the
 *                                   CreateUsPlaceArea function 
 * @return  $dom_tax_area_obj    <tax-area> XML DOM containing the 
 *                                   child elements that correspond to
 *                                   the specified $area_type
 */
function CreateTaxArea($area_type, $area_place,$non_US=false) {

    $error_function_name = "CreateTaxArea(" . $area_type . ")";

    // You must provide an $area_type value or the function will not execute.
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        $area_type, $area_type);

    // Create the <tax-area> element
    $dom_tax_area_obj = domxml_new_doc("1.0");
    $dom_tax_area = 
        $dom_tax_area_obj->append_child(
            $dom_tax_area_obj->create_element("tax-area"));

    /*
     * Call the CreateUsPlaceArea function to create the child
     * elements of the <tax-area> element
     */
    if ( $non_US===false ) {
      $dom_area = CreateUsPlaceArea($area_type, $area_place);
    }else{
      $dom_area = CreateNonUsPlaceArea($area_type, $area_place);
    }
    $dom_area_root = $dom_area->document_element();
    $dom_tax_area->append_child($dom_area_root->clone_node(true));

    return $dom_tax_area_obj;
}


/**
 * The CreateDefaultTaxRule function creates and returns a
 * <default-tax-rule> XML DOM.
 *
 * @param   $rate              The tax rate to assess for a
 *                                 given tax rule.
 * @param   $dom_tax_area      An XML DOM that identifies the
 *                                 area where a tax rate should be applied.
 * @param   $shipping_taxed    A Boolean value that indicates
 *                                 whether shipping costs are taxed
 *                                 in the specified tax area.
 * @return  $dom_default_tax_rule_obj    
 *                             <default-tax-rule> XML DOM
 */
function CreateDefaultTaxRule($rate, $dom_tax_area, $shipping_taxed="") {

    global $dom_default_tax_rules_obj;

    $error_function_name = "CreateDefaultTaxRule()";

    /*
     * You must specify a $rate and provide a $dom_tax_area object
     * for each tax rule
     */
    /*CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "rate", $rate);*/
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "dom_tax_area", $dom_tax_area);

    $dom_default_tax_rule_obj = domxml_new_doc("1.0");

    // Create the <default-tax-rule> element
    $dom_default_tax_rule = 
        $dom_default_tax_rule_obj->append_child(
            $dom_default_tax_rule_obj->create_element("default-tax-rule"));

    // Add a <shipping-taxed> element if a $shipping_taxed value is provided
    if ($shipping_taxed != "") {

        $dom_shipping_taxed = 
            $dom_default_tax_rule->append_child(
        $dom_default_tax_rule_obj->create_element("shipping-taxed"));

        $dom_shipping_taxed->append_child(
            $dom_default_tax_rule_obj->create_text_node($shipping_taxed));
    }

    // Add the tax rate for the tax rule
    $dom_rate = $dom_default_tax_rule->append_child(
        $dom_default_tax_rule_obj->create_element("rate"));
    $dom_rate->append_child($dom_default_tax_rule_obj->create_text_node($rate));

    // Add the tax area to the tax rule
    $dom_tax_area_root = $dom_tax_area->document_element();
    $dom_default_tax_rule->append_child($dom_tax_area_root->clone_node(true));

    /*
     * Create a <tax-rules> element if no other <default-tax-rule>
     * elements have been created. Append the rule to a list that
     * will appear under the <tax-rules> element within the
     * <default-tax-table> element
     */
    if ($dom_default_tax_rules_obj == "") {
        $dom_default_tax_rules_obj = domxml_new_doc("1.0");

        $dom_default_tax_rules_obj->append_child(
            $dom_default_tax_rules_obj->create_element("tax-rules"));
    }

    $dom_default_tax_rules_root = 
        $dom_default_tax_rules_obj->document_element();
    $dom_default_tax_rule_root = $dom_default_tax_rule_obj->document_element();
    $dom_default_tax_rules_root->append_child(
        $dom_default_tax_rule_root->clone_node(true));

   return $dom_default_tax_rule_obj;
}


/**
 * The CreateAlternateTaxRule function creates and returns an
 * <alternate-tax-rule> XML DOM.
 *
 * @param   $rate                    tax rate
 * @param   $dom_tax_area            <tax-area> XML DOM
 * @return  $dom_alt_tax_rule_obj    <alternate-tax-rule> XML DOM
 */
function CreateAlternateTaxRule($rate, $dom_tax_area) {

    global $dom_alt_tax_rules_obj;

    // Check for errors
    $error_function_name = "CreateAlternateTaxRule()";

    /*
     * You must specify a $rate and provide a $dom_tax_area object
     * for each tax rule
     */
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "rate", $rate);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "dom_tax_area", $dom_tax_area);

    $dom_alt_tax_rule_obj = domxml_new_doc("1.0");

    // Create the <alternate-tax-rule> element
    $dom_alt_tax_rule = $dom_alt_tax_rule_obj->append_child(
        $dom_alt_tax_rule_obj->create_element("alternate-tax-rule"));

    // Add the tax rate for the tax rule
    $dom_rate = $dom_alt_tax_rule->append_child(
        $dom_alt_tax_rule_obj->create_element("rate"));
    $dom_rate->append_child($dom_alt_tax_rule_obj->create_text_node($rate));

    // Add the tax area to the tax rule
    $dom_tax_area_root = $dom_tax_area->document_element();
    $dom_alt_tax_rule->append_child($dom_tax_area_root->clone_node(true));

    /*
     * Create an <alternate-tax-rules> element if this is the first
     * <alternate-tax-rule> to be created. Append the rule to a list 
     * that will appear under the <alternate-tax-rules> element within 
     * an <alternate-tax-table> element
     */
    if ($dom_alt_tax_rules_obj == "") {
        $dom_alt_tax_rules_obj = domxml_new_doc("1.0");

        $dom_alt_tax_rules_obj->append_child(
            $dom_alt_tax_rules_obj->create_element("alternate-tax-rules"));
    }

    $dom_alt_tax_rules_root = $dom_alt_tax_rules_obj->document_element();
    $dom_alt_tax_rule_root = $dom_alt_tax_rule_obj->document_element();
    $dom_alt_tax_rules_root->append_child(
        $dom_alt_tax_rule_root->clone_node(true));

   return $dom_alt_tax_rule_obj;
}


/**
 * The CreateAlternateTaxTable function creates and returns an
 * <alternate-tax-table> XML DOM. The XML will contain any 
 * <alternate-tax-rule> elements that have not already been included
 * in an <alternate-tax-table>.
 *
 * @param   $standalone    A Boolean value that indicates how taxes
 *                             should be calculated if there is no
 *                             matching <alternate-tax-rule> for the 
 *                             customer's area.
 * @param   $name          A name that is used to identify the tax table
 * @return  $dom_alt_tax_tables_obj
 *                         <alternate-tax-table> XML DOM
 */
function CreateAlternateTaxTable($standalone, $name) {

    global $dom_alt_tax_rules_obj, $dom_alt_tax_tables_obj;

    /*
     * There must be at least one alternate tax rule to include
     * in the <alternate-tax-table>. This tax table will include
     * any <alternate-tax-rule> elements that were created since
     * after the last call to the CreateAlternateTaxTable function.
     */
    if ($dom_alt_tax_rules_obj == "") {
        trigger_error("You must have at least one alternate tax rule.", 
            E_USER_ERROR);
    }

    // Check for errors
    $error_function_name = "CreateAlternateTaxTable()";
   
    /*
     * You must specify values for the $standalone and $name parameters
     */
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "standalone", $standalone);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "name", $name);

    // Create the <alternate-tax-rules> object
//    $dom_alt_tax_rules_obj->append_child(
//        $dom_alt_tax_rules_obj->create_element("alternate-tax-rules"));

    // Create the <alternate-tax-table> element
    $dom_alt_tax_table_obj = domxml_new_doc("1.0");
    $dom_alt_tax_table = $dom_alt_tax_table_obj->append_child(
        $dom_alt_tax_table_obj->create_element("alternate-tax-table"));

    // Add attributes for the $standalone and $name values
    $dom_alt_tax_table->set_attribute("standalone", $standalone);
    $dom_alt_tax_table->set_attribute("name", $name);

    /*
     * Add the <alternate-tax-rules> element as 
     * a child element of <alternate-tax-table>
     */
    $dom_alt_tax_rules_root = $dom_alt_tax_rules_obj->document_element();
    $dom_alt_tax_table->append_child($dom_alt_tax_rules_root->clone_node(true));

    /*
     * Create an <alternate-tax-tables> element, if one has not yet
     * been created, to contain all <alternate-tax-table> elements
     */
    if ($dom_alt_tax_tables_obj == "") {
        $dom_alt_tax_tables_obj = domxml_new_doc("1.0");
        $dom_alt_tax_tables_obj->append_child(
            $dom_alt_tax_tables_obj->create_element("alternate-tax-tables"));
    }

    /*
     * Add the <alternate-tax-table> element as a child of
     * the <alternate-tax-tables> element
     */
    $dom_alt_tax_tables_root = $dom_alt_tax_tables_obj->document_element();
    $dom_alt_tax_table_root = $dom_alt_tax_table_obj->document_element();
    $dom_alt_tax_tables_root->append_child(
        $dom_alt_tax_table_root->clone_node(true));

    $dom_alt_tax_rules_obj = domxml_new_doc("1.0");
    $dom_alt_tax_rules_obj->append_child(
        $dom_alt_tax_rules_obj->create_element("alternate-tax-rules"));

   return $dom_alt_tax_tables_obj;
}


/**
 * The CreateTaxTables element constructs the <tax-tables> XMLDOM.
 *
 * @param   $merchant_calculated    A Boolean value that indicates
 *                                      whether tax for the order is
 *                                      calculated using a special process.
 * @return  $dom_tax_tables_obj     <tax-tables> XML DOM
 */
function CreateTaxTables($merchant_calculated) {

    global $dom_default_tax_rules_obj, $dom_alt_tax_tables_obj, 
        $dom_tax_tables_obj;

    // Check for errors
    $error_function_name = "CreateTaxTables()";
   
    /*
     * You must have already created the <default-tax-table> XML DOM
     * before calling this function. You must also provide a value
     * for the $merchant_calculated parameter.
     */
    if ($dom_default_tax_rules_obj == "") {
        trigger_error("You must have have at least one default tax rule.", 
            E_USER_ERROR);
    }
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "merchant_calculated", $merchant_calculated);

    // Create a <default-tax-table> element
    $dom_default_tax_table_obj = domxml_new_doc("1.0");
    $dom_default_tax_table = 
        $dom_default_tax_table_obj->append_child(
            $dom_default_tax_table_obj->create_element("default-tax-table"));

    /*
     * Add <default-tax-rule> elements as children of
     * the <default-tax-table> element
     */
    $dom_default_tax_rules_root = 
        $dom_default_tax_rules_obj->document_element();
    $dom_default_tax_table->append_child(
        $dom_default_tax_rules_root->clone_node(true));

    // Create the <tax-tables> element
    $dom_tax_tables_obj = domxml_new_doc("1.0");
    $dom_tax_tables = $dom_tax_tables_obj->append_child(
        $dom_tax_tables_obj->create_element("tax-tables"));

    // Set the $merchant-calculated attribute on the <tax-tables> element
    if ($merchant_calculated != "") {
        $dom_tax_tables->set_attribute(
            "merchant-calculated", $merchant_calculated);
    }

    // Make the <default-tax-table> element a child of <tax-tables> element
    $dom_default_tax_table_root = 
        $dom_default_tax_table_obj->document_element();
    $dom_tax_tables->append_child(
        $dom_default_tax_table_root->clone_node(true));

    // Add the <alternate-tax-tables> as children of <tax-tables>
    if ($dom_alt_tax_tables_obj != "") {
        $dom_alt_tax_tables_root = 
            $dom_alt_tax_tables_obj->document_element();
        $dom_tax_tables->append_child(
            $dom_alt_tax_tables_root->clone_node(true));
    }

   return $dom_tax_tables_obj;
}

/**
 * The AddAllowedAreas function is a wrapper function that calls the
 * AddAreas function. The AddAreas function, in turn,
 * creates and returns an <allowed-areas> XML DOM.
 *
 * @param   $country_area    See the $country_area parameter
 *                               of the AddAreas function for
 *                               a list of valid values
 * @param   $state_area      See the $state_areas parameter
 *                               of the AddAreas function for
 *                               a list of valid values
 * @param   $zip_area        See the $zip_areas parameter
 *                               of the AddAreas function for
 *                               a list of valid values
 * @return  <shipping-restrictions> XML DOM with the allowed area added
 */
function AddAllowedAreas($country_area="", $state_areas=array(),
    $zip_areas=array(),$nonUS=false) {
    return AddAreas($country_area, $state_areas, $zip_areas, "allowed");
}

/**
 * The AddExcludedAreas function is a wrapper function that calls the
 * AddAreas function. The AddAreas function, in turn,
 * creates and returns an <excluded-areas> XML DOM.
 *
 * @param   $country_area    See the $country_area parameter
 *                               of the AddAreas function for
 *                               a list of valid values
 * @param   $state_area      See the $state_areas parameter
 *                               of the AddAreas function for
 *                               a list of valid values
 * @param   $zip_area        See the $zip_areas parameter
 *                               of the AddAreas function for
 *                               a list of valid values
 * @return  <shipping-restrictions> XML DOM with the excluded area added
 */
function AddExcludedAreas($country_area="", $state_areas=array(),
    $zip_areas=array()) {
    return AddAreas($country_area, $state_areas, $zip_areas, "excluded");
}

/**
 * Creates <allowed-areas> or <excluded-areas> XMLDOM objects.
 *
/**
 * The AddAreas function creates a list of regions where shipping options
 * are either available or unavailable. The first three parameters identify
 * the regions where the shipping option is available or unavailable. The
 * final parameter indicates whether the shipping option is available.
 *
 * @param   $country_area       An region of the country where the
 *                                  shipping option is either available or
 *                                  unavailable. Valid values are
 *                                  CONTINENTAL_48, FULL_50_STATES and ALL.
 * @param   $state_areas        An array of states where the shipping
 *                                  option is either available or unavailable.
 *                                  Each item in the array should be a
 *                                  two-letter U.S. state abbreviation.
 *                                  in the XML block. The value should be a
 *                                  five-digit zip code or a zip code pattern.
 * @param   $zip_areas          An array of zip codes where the shipping
 *                                  option is either available or unavailable.
 *                                  Each item in the array should be a
 *                                  five-digit zip code or a zip code pattern.
 * @param   $allow_or_exclude   Indicates whether the shipping option
 *                                  is available or unavailable in the
 *                                  specified regions. Valid values are
 *                                  "allowed" and "excluded".
 * @return  $dom_shipping_restrictions_obj  
 *                              <shipping-restrictions> XML DOM with 
 *                                  the area added
 */
function AddAreas($country_area="", $state_areas=array(), $zip_areas=array(), 
    $allow_or_exclude) {

    global $dom_areas_obj, $dom_shipping_restrictions_obj;

    // Check for errors
    $error_function_name = "CreateAllowedAreas()";
   
    // Verify that at least one region has been specified.
    if (($country_area == "") && ($state_areas == "") && ($zip_areas == "")) {
        trigger_error("You must provide at least one area.", E_USER_ERROR);
    }

    // Verify that the $allow_or_exclude parameter contains a valid value.
    $error_type = "INVALID_ALLOW_OR_EXCLUDE_VALUE";
    if (($allow_or_exclude != "allowed") && ($allow_or_exclude != "excluded")) {
        trigger_error("Areas must either be 'allowed' or 'excluded'.", 
            E_USER_ERROR);
    }

    // Verify that $state_areas and $zip_areas parameters are arrays.
    $error_type = "INVALID_INPUT_ARRAY";
    if (($state_areas != "") && !is_array($state_areas)) {
        trigger_error(error_msg($error_type, $error_function_name, 
            $allow_or_exclude . "_state"), E_USER_ERROR);
    }

    if (($zip_areas != "") && !is_array($zip_areas)) {
        trigger_error(error_msg($error_type, $error_function_name, 
            $allow_or_exclude . "_zip"), E_USER_ERROR);
    }
   
    // Create the <allowed-areas> or <excluded-areas> element
    $dom_areas_obj = domxml_new_doc("1.0");
    $dom_areas = $dom_areas_obj->append_child(
        $dom_areas_obj->create_element($allow_or_exclude . "-areas"));

if ( $country_area=='GB' ) {
        $dom_us_country_area = CreateNonUsCountryArea($country_area);
   $dom_country_root = $dom_us_country_area->document_element();
   $dom_areas->append_child($dom_country_root->clone_node(true));
}else{
    // Add the <us-country-area> element if a $country_area is provided
    if ($country_area != "") {
        $dom_us_country_area = CreateUsCountryArea($country_area);
   $dom_country_root = $dom_us_country_area->document_element();
   $dom_areas->append_child($dom_country_root->clone_node(true));
    }

    // Add a <us-state-area> element for each item in the $state_areas array
    if ($state_areas != "") {
        foreach ($state_areas as $state) {
            $dom_us_state_area = CreateUsStateArea($state);
            $dom_state_root = $dom_us_state_area->document_element();
            $dom_areas->append_child($dom_state_root->clone_node(true));
        }
    }

    // Add a <us-zip-area> element for each item in the $zip_areas array
    if ($zip_areas != "") {
        foreach ($zip_areas as $zip) {
            $dom_us_zip_area = CreateUsZipArea($zip);
            $dom_zip_root = $dom_us_zip_area->document_element();
            $dom_areas->append_child($dom_zip_root->clone_node(true));
        }
    }
}
    /*
     * Create a <shipping-restrictions> parent element if one has
     * not already been created.
     */
    if ($dom_shipping_restrictions_obj == "") {
        $dom_shipping_restrictions_obj = domxml_new_doc("1.0");

        $dom_shipping_restrictions = 
            $dom_shipping_restrictions_obj->append_child(
                $dom_shipping_restrictions_obj->create_element(
                    "shipping-restrictions"));
    }

    $dom_areas_root = $dom_areas_obj->document_element();
    $dom_shipping_restrictions_root = 
        $dom_shipping_restrictions_obj->document_element();
    $dom_shipping_restrictions_root->append_child(
        $dom_areas_root->clone_node(true));

    return $dom_shipping_restrictions_obj;
}

/**
 * The CreateFlatRateShipping function is a wrapper function that calls the
 * CreateShipping function. The CreateShipping function, in turn,
 * creates and returns a <flat-rate-shipping> XML DOM.
 *
 * @param   $name     A name used to identify the shipping option
 * @param   $price    The cost of the shipping option
 * @param   $dom_shipping_restrictions_obj
 *                    An XML DOM that identifies areas where the shipping
 *                    option is available or unavailable
 * @return  <flat-rate-shipping> XML DOM
 */
function CreateFlatRateShipping($name, $price, 
    $dom_shipping_restrictions_obj="") {
    return CreateShipping("flat-rate-shipping", $name, $price,
        $dom_shipping_restrictions_obj);
}

/**
 * The CreateMerchantCalculatedShipping function is a wrapper function 
 * that calls the CreateShipping function. The CreateShipping function, 
 * in turn, creates and returns a <merchant-calculated-shipping> XML DOM.
 *
 * @param   $name     A name used to identify the shipping option
 * @param   $price    The cost of the shipping option
 * @param   $dom_shipping_restrictions_obj
 *                    An XML DOM that identifies areas where the shipping
 *                    option is available or unavailable
 * @return  <merchant-calculated-shipping> XML DOM
 */
function CreateMerchantCalculatedShipping($name, $price, 
    $dom_shipping_restrictions_obj="") {
    return CreateShipping("merchant-calculated-shipping", $name, $price,
        $dom_shipping_restrictions_obj);
}

/**
 * The CreatePickup function is a wrapper function that calls the 
 * CreateShipping function. The CreateShipping function, in turn, 
 * creates and returns a <pickup> XML DOM.
 *
 * @param   $name     A name used to identify the shipping option
 * @param   $price    The cost of the shipping option
 * @return  <pickup> XML DOM
 */
function CreatePickup($name, $price) {
    return CreateShipping("pickup", $name, $price);
}

/**
 * The CreateShipping function creates and returns <flat-rate-shipping>,
 * <merchant-calculated-shipping> or <pickup> XML DOM objects. Each call 
 * to this function identifies the type of shipping option, the cost of 
 * the shipping option as well as a name that can be used to identify the 
 * shipping option. The function also accepts shipping restrictions for 
 * <flat-rate-shipping> and <merchant-calculated-shipping>
 *
 * @param   $shipping_type    Identifies the type of shipping. Valid values
 *                                are "flat-rate-shipping",
 *                                "merchant-calculated-shipping" and "pickup"
 * @param   $name             A name used to identify the shipping option
 * @param   $price            The cost of the shipping option
 * @param   $dom_shipping_restrictions_obj
 *                            An XML DOM that identifies areas where the 
 *                                shipping option is available or unavailable
 * @return  $dom_shipping_restrictions_obj  
 *                            <flat-rate-shipping>, 
 *                                <merchant-calculated-shipping>,
 *                                or <pickup> XML DOM
 */
function CreateShipping($shipping_type, $name, $price, 
    $dom_shipping_restrictions_obj="") {

    global $dom_shipping_methods_obj;

    // Verify that there are values for all required parameters
    $error_function_name = "CreateShipping(" . $shipping_type . ")";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "name", $name);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "price", $price);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "GLOBALS[\"currency\"]", $GLOBALS["currency"]);

    /*
     * Create a new parent element using the $shipping_type
     * as the element name
     */
    $dom_shipping_obj = domxml_new_doc("1.0");
    $dom_shipping = $dom_shipping_obj->append_child(
        $dom_shipping_obj->create_element($shipping_type));

    // Set the name and price for the shipping option
    $dom_shipping->set_attribute("name", $name);
    $dom_price = $dom_shipping->append_child(
        $dom_shipping_obj->create_element("price"));
    $dom_price->set_attribute("currency", $GLOBALS["currency"]);
    $dom_price->append_child($dom_shipping_obj->create_text_node($price));

    /*
     * Add shipping-restrictions for <flat-rate-shipping>
     * and <merchant-calculated-shipping>
     */
    if (($shipping_type == "flat-rate-shipping" || 
      $shipping_type == "merchant-calculated-shipping") && 
      $dom_shipping_restrictions_obj != "") 
    {
        $dom_shipping_restrictions_root = 
            $dom_shipping_restrictions_obj->document_element();
        $dom_shipping->append_child(
            $dom_shipping_restrictions_root->clone_node(true));
    }

    if ($dom_shipping_methods_obj == "") {
        $dom_shipping_methods_obj = domxml_new_doc("1.0");
        $dom_shipping_methods_obj->append_child(
            $dom_shipping_methods_obj->create_element("shipping-methods"));
    }

    // Add the shipping method to the XML request
    $dom_shipping_methods_root = $dom_shipping_methods_obj->document_element();
    $dom_shipping_root = $dom_shipping_obj->document_element();
    $dom_shipping_methods_root->append_child(
        $dom_shipping_root->clone_node(true));

   return $dom_shipping_obj;
}


/**
 * The CreateMerchantCalculations function creates and returns a 
 * <merchant-calculations> XML DOM.
 *
 * @param   $merchant_calc_url           Callback URL for merchant 
 *                                           calculations
 * @param   $accept_merchant_coupons     Boolean value that indicates
 *                                           whether Google Checkout should 
 *                                           display an option for customers 
 *                                           to enter coupon codes for an 
 *                                           order
 * @param   $accept_gift_certificates    Boolean value that indicates
 *                                           whether Google Checkout should 
 *                                           display an option for customers 
 *                                           to enter gift certificate codes 
 * @return  $dom_merchant_calc_obj       <merchant-calculations> XML DOM
 */
function CreateMerchantCalculations($merchant_calc_url, $accepts_coupons="", 
    $accept_gift_certificates="") {

    global $dom_merchant_calc_obj;

    // Verify that there is a value for the $merchant_calc_url parameter
    $error_function_name = "CreateMerchantCalculations()";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "merchant_calc_url", $merchant_calc_url);

    // Create the <merchant-calculations> element
    if ($dom_merchant_calc_obj == "") {
        $dom_merchant_calc_obj = domxml_new_doc("1.0");

        $dom_merchant_calc = $dom_merchant_calc_obj->append_child(
            $dom_merchant_calc_obj->create_element("merchant-calculations"));
    }

    // Create the <merchant-calculations-url> element
    $dom_merchant_calc_url = $dom_merchant_calc->append_child(
        $dom_merchant_calc_obj->create_element("merchant-calculations-url"));
    $dom_merchant_calc_url->append_child(
        $dom_merchant_calc_obj->create_text_node($merchant_calc_url));

    // Create the <accepts-merchant-coupons> element
    if ($accepts_coupons != "") {

        $dom_accepts_coupons = $dom_merchant_calc->append_child(
            $dom_merchant_calc_obj->create_element("accept-merchant-coupons"));

        $dom_accepts_coupons->append_child(
            $dom_merchant_calc_obj->create_text_node($accepts_coupons));
    }

    // Create the <accepts-gift-certificates> element
    if ($accept_gift_certificates != "") {

        $dom_accept_gift_certificates = $dom_merchant_calc->append_child(
            $dom_merchant_calc_obj->create_element("accept-gift-certificates"));

        $dom_accept_gift_certificates->append_child(
            $dom_merchant_calc_obj->create_text_node(
                $accept_gift_certificates));
    }

   return $dom_merchant_calc_obj;
}


/**
 * The CreateMerchantCheckoutFlowSupport function builds a 
 * <merchant-checkout-flow-support> XML DOM. This XML contains
 * information about taxes, shipping and other custom calculations
 * to be used in the checkout process. The XML also contains URLs
 * used during the checkout process, such as URLs for the customer
 * to edit a shopping cart or to continue shopping.
 * 
 * @param   $edit_cart_url              URL to visit if the customer wants 
 *                                          to edit the shopping cart 
 * @param   $continue_shopping_url      URL to visit if the customer wants
 *                                          to continue shopping
 * @return  $dom_mc_flow_support_obj    <merchant-checkout-flow-support> 
 *                                          XML DOM
 */
function CreateMerchantCheckoutFlowSupport($edit_cart_url="", 
    $continue_shopping_url="",$tax_calc_uk=false, $affiliate_gc="") {

    // The abbreviation "mc" is used for "merchant_checkout"
    global $dom_shipping_methods_obj, $dom_tax_tables_obj, 
        $dom_merchant_calc_obj, $dom_mc_flow_support_obj;

    $dom_mc_flow_support_obj = domxml_new_doc("1.0");

    // Create the <merchant-checkout-flow-support> element
    $dom_mc_flow_support = 
        $dom_mc_flow_support_obj->append_child(
            $dom_mc_flow_support_obj->create_element(
                "merchant-checkout-flow-support"));

    // Add the <edit-cart-url> element
    if ($edit_cart_url != "") {

        $dom_edit_cart_url = $dom_mc_flow_support->append_child(
            $dom_mc_flow_support_obj->create_element("edit-cart-url"));

        $dom_edit_cart_url->append_child(
            $dom_mc_flow_support_obj->create_text_node($edit_cart_url));
    }
   
    // Add the <continue-shopping-url> element
    if ($continue_shopping_url != "") {
        $dom_continue_shopping_url = 
        $dom_mc_flow_support->append_child(
            $dom_mc_flow_support_obj->create_element("continue-shopping-url"));
        $dom_continue_shopping_url->append_child(
            $dom_mc_flow_support_obj->create_text_node($continue_shopping_url));
    }

    // Add <shipping-methods> element
    if ($dom_shipping_methods_obj != "") {
        $dom_shipping_methods_root = 
        $dom_shipping_methods_obj->document_element();
        $dom_mc_flow_support->append_child(
            $dom_shipping_methods_root->clone_node(true));
    }

    // Add <tax-tables> element
    if ($dom_tax_tables_obj != "") {
        $dom_tax_tables_root = $dom_tax_tables_obj->document_element();
        $dom_mc_flow_support->append_child(
            $dom_tax_tables_root->clone_node(true));
    }

    // Add <merchant-calculations> element
    if ($dom_merchant_calc_obj != "") {
        $dom_merchant_calc_root = $dom_merchant_calc_obj->document_element();
        $dom_mc_flow_support->append_child(
            $dom_merchant_calc_root->clone_node(true));
    }
    if ( $tax_calc_uk ) {
      $dom_round_policy = $dom_mc_flow_support->append_child(
            $dom_mc_flow_support_obj->create_element("rounding-policy"));
      $dom_round_mode = $dom_round_policy->append_child(
            $dom_mc_flow_support_obj->create_element("mode"));            
      $dom_round_rule = $dom_round_policy->append_child(
            $dom_mc_flow_support_obj->create_element("rule"));
      $dom_round_mode->append_child($dom_mc_flow_support_obj->create_text_node('HALF_UP'));
      $dom_round_rule->append_child($dom_mc_flow_support_obj->create_text_node('TOTAL'));            

    }
    if ($affiliate_gc != "" && is_array($affiliate_gc)) {
        $affiliate_xml = affiliate_gc::xml();
        $dom_parameterized_urls = $dom_mc_flow_support->append_child(
            $dom_mc_flow_support_obj->create_element("parameterized-urls"));
        foreach ( $affiliate_gc as $affiliate_name ) {
          if ( isset($affiliate_xml[$affiliate_name]) ) {
            $dom_parameterized_url = $dom_parameterized_urls->append_child(
                $dom_mc_flow_support_obj->create_element("parameterized-url"));
            $dom_parameterized_url->set_attribute("url", str_replace(' ','%20',$affiliate_xml[$affiliate_name]['url'] ) ); 
            //$dom_parameterized_url->set_attribute("url", $affiliate_xml[$affiliate_name]['url']);
            if ( isset($affiliate_xml[$affiliate_name]['params']) && is_array($affiliate_xml[$affiliate_name]['params']) ) {
              $dom_parameters = $dom_parameterized_url->append_child(
                $dom_mc_flow_support_obj->create_element("parameters"));
              foreach( $affiliate_xml[$affiliate_name]['params'] as $p_name=>$p_type ) {
                $dom_url_parameter = $dom_parameters->append_child(
                  $dom_mc_flow_support_obj->create_element("url-parameter"));
                $dom_url_parameter->set_attribute('name',$p_name); 
                $dom_url_parameter->set_attribute('type',$p_type);
              }
            }
          }
        }
    }
   return $dom_mc_flow_support_obj;
}


/**
 * The CreateCheckoutShoppingCart function returns the 
 * <checkout-shopping-cart> XML DOM, which contains all of the items 
 * and checkout-related information for an order.
 *
 * @return  <checkout-shopping-cart> XML
 */
function CreateCheckoutShoppingCart() {

    global $dom_shopping_cart_obj, $dom_mc_flow_support_obj, 
        $dom_checkout_flow_support_obj, $dom_checkout_shopping_cart_obj, $dom_shipping_restrictions_obj, $dom_checkout_shipping_obj;

    /*
     * Verify that there is a <shopping-cart> XML DOM and a
     * <merchant-checkout-flow-support> XML DOM
     */
    $error_function_name = "CreateCheckoutShoppingCart()";
    if ($dom_shopping_cart_obj == "") {
        trigger_error("You must have a shopping cart.", E_USER_ERROR);
    } else if ($dom_mc_flow_support_obj == "") {
        trigger_error("You must have a merchant checkout flow support.",
            E_USER_ERROR);
    }

    /*
     * Create the <checkout-flow-support> element and add
     * the <merchant-checkout-flow-support> element as a child element
     */
    $dom_checkout_flow_support_obj = domxml_new_doc("1.0");
    $dom_mc_flow_support_root = $dom_mc_flow_support_obj->document_element();
    $dom_checkout_flow_support = $dom_checkout_flow_support_obj->append_child(
        $dom_checkout_flow_support_obj->create_element(
            "checkout-flow-support"));
    $dom_checkout_flow_support->append_child(
        $dom_mc_flow_support_root->clone_node(true));

    // Create the <checkout-shopping-cart> element
    $dom_checkout_shopping_cart_obj = domxml_new_doc("1.0");
    $dom_checkout_shopping_cart = 
        $dom_checkout_shopping_cart_obj->append_child(
            $dom_checkout_shopping_cart_obj->create_element(
                "checkout-shopping-cart"));

    $dom_checkout_shopping_cart->set_attribute("xmlns", $GLOBALS["schema_url"]);

    /* 
     * Add the <shopping-cart> element as a child element of the 
     * <checkout-shopping-cart> element
     */
    $dom_shopping_cart_root = $dom_shopping_cart_obj->document_element();
    $dom_checkout_shopping_cart->append_child(
        $dom_shopping_cart_root->clone_node(true));

    $dom_checkout_flow_support_root = 
        $dom_checkout_flow_support_obj->document_element();
    $dom_checkout_shopping_cart->append_child(
        $dom_checkout_flow_support_root->clone_node(true));

    return $dom_checkout_shopping_cart_obj->dump_mem();
}

/** End of file **/

?>