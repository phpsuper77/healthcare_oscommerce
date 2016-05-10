<?php
// {{
reset($HTTP_POST_VARS);
while (list($key, $value) = each($HTTP_POST_VARS)) {
  if (!is_array($HTTP_POST_VARS[$key])) {
    if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key] = stripslashes($value);
  } else {
    while (list($k, $v) = each($value)) {
      if (!is_array($HTTP_POST_VARS[$key][$k])) {
        if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k] = stripslashes($v);
      } else {
        while (list($k1, $v1) = each($v)){
          if (!is_array($HTTP_POST_VARS[$key][$k][$k1])) {
            if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k][$k1] = stripslashes($v1);
          } else {
            while (list($k2, $v2) = each($v1)) {
              if (!is_array($HTTP_POST_VARS[$key][$k][$k1][$k2])){
                if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k][$k1][$k2] = stripslashes($v2);
              }else{
                while (list($k3, $v3) = each($v2)){
                  if (get_magic_quotes_gpc()) $HTTP_POST_VARS[$key][$k][$k1][$k2][$k3] = stripslashes($v3);
                }
              }
            }
          }
        }
      }
    }
  }
}
// }}
$parameters = array('products_name' => '',
'direct_url' => '',
'products_description' => '',
'products_url' => '',
'products_id' => '',
'products_quantity' => '',
'products_model' => '',
'sort_order' => '',
'products_image' => '',
'products_seo_page_name' => '',
'products_image_med' => '',
'products_image_lrg' => '',
'products_image_sm_1' => '',
'products_image_xl_1' => '',
'products_image_sm_2' => '',
'products_image_xl_2' => '',
'products_image_sm_3' => '',
'products_image_xl_3' => '',
'products_image_sm_4' => '',
'products_image_xl_4' => '',
'products_image_sm_5' => '',
'products_image_xl_5' => '',
'products_image_sm_6' => '',
'products_image_xl_6' => '',
'products_image_alt_1' => '',
'products_image_alt_2' => '',
'products_image_alt_3' => '',
'products_image_alt_4' => '',
'products_image_alt_5' => '',
'products_image_alt_6' => '',
'products_price' => '',
'products_weight' => '',
'products_date_added' => '',
'products_last_modified' => '',
'products_date_available' => '',
'products_status' => '',
'products_tax_class_id' => '',
'manufacturers_id' => '',
'products_file' => '',
'products_price_discount' => '',
'vat_exempt_flag'=>0,
);

$pInfo = new objectInfo($parameters);

if (VENDOR_ENABLED == 'true'){
  $vendors_array = array(array('id'=>0, 'text' => TEXT_NONE));
  $query = tep_db_query("select * from " . TABLE_VENDOR . " where vendor_status = 1 order by vendor_lastname");
  while ($data = tep_db_fetch_array($query)){
    $vendors_array[] = array('id' => $data['vendor_id'], 'text' => $data['vendor_firstname'] . ' ' . $data['vendor_lastname']);
  }
}

if (isset($HTTP_GET_VARS['pID']) && empty($HTTP_POST_VARS)) {
  // BOF MaxiDVD: Modified For Ultimate Images Pack!

  $product_query = tep_db_query("select p.products_mpn, p.products_upc, p.products_ean, pd.products_name, pd.products_faq, pd.products_features, pd.products_description, pd.products_ebay_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.sort_order, p.products_image, p.products_image_med, p.products_seo_page_name, p.products_image_lrg, p.products_image_sm_1, p.products_image_xl_1, p.products_image_sm_2, p.products_image_xl_2, p.products_image_sm_3, p.products_image_xl_3, p.products_image_sm_4, p.products_image_xl_4, p.products_image_sm_5, p.products_image_xl_5, p.products_image_sm_6, p.products_image_xl_6, p.products_image_alt_1, p.products_image_alt_2, p.products_image_alt_3, p.products_image_alt_4, p.products_image_alt_5, p.products_image_alt_6, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_file, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id, products_price_discount, p.vendor_id, p.products_sets_discount, p2c.categories_id, p.vat_exempt_flag, pd.direct_url from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p2c.products_id = p.products_id and p.products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.affiliate_id = 0 " . (tep_session_is_registered('login_vendor')?" and vendor_id='".$login_id."'":''));

  // EOF MaxiDVD: Modified For Ultimate Images Pack!
  $product = tep_db_fetch_array($product_query);

  $pInfo->objectInfo($product);
} elseif (tep_not_null($HTTP_POST_VARS)) {
  $pInfo->objectInfo($HTTP_POST_VARS);
  $products_name = $HTTP_POST_VARS['products_name'];
  $direct_url = $HTTP_POST_VARS['direct_url'];
  $products_description = $HTTP_POST_VARS['products_description'];
  $products_ebay_description  = $HTTP_POST_VARS['products_ebay_description'];
  $products_url = $HTTP_POST_VARS['products_url'];
  // [[
  $products_price = $HTTP_POST_VARS['products_price'];
  // ]]

}

$manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
$manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
  $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
  'text' => $manufacturers['manufacturers_name']);
}

$tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
$tax_class_query = tep_db_query("select tax_class_id, tax_class_title from " . TABLE_TAX_CLASS . " order by tax_class_title");
while ($tax_class = tep_db_fetch_array($tax_class_query)) {
  $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
  'text' => $tax_class['tax_class_title']);
}

$languages = tep_get_languages();

if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
switch ($pInfo->products_status) {
  case '0': $in_status = false; $out_status = true; break;
  case '1':
  default: $in_status = true; $out_status = false;
}

require('includes/classes/directory_listing.php');
$osC_Dir_Images = new osC_DirectoryListing('../images');
$osC_Dir_Images->setExcludeEntries(array('CVS', '.svn'));
$osC_Dir_Images->setIncludeFiles(false);
$osC_Dir_Images->setRecursive(true);
$osC_Dir_Images->setAddDirectoryToFilename(true);
$files = $osC_Dir_Images->getFiles();

$image_directories = array(array('id' => '', 'text' => 'images/'));
foreach ($files as $file) {
  $image_directories[] = array('id' => $file['name'], 'text' => 'images/' . $file['name']);
}

?>
<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>
<script language="JavaScript" src="utils.js"></script>
<script language="javascript"><!--
var tax_rates = new Array();
<?php
for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
  if ($tax_class_array[$i]['id'] > 0) {
    echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
  }
}
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}
<?php
if (USE_MARKET_PRICES == 'True'){
?>
  function updateGross() {
    var taxRate = getTaxRate();
    <?php
    foreach ($currencies->currencies as $key => $value)
    {
      echo "var grossValue = document.getElementById('products_price[".$currencies->currencies[$key]['id']."]').value;\n";
      echo "if (taxRate > 0) {\n";
      echo "grossValue = grossValue * ((taxRate / 100) + 1);\n";
      echo "}\n";
      echo "document.getElementById('products_price_gross[".$currencies->currencies[$key]['id']."]').value = doRound(grossValue, 4);\n";
      if (CUSTOMERS_GROUPS_ENABLE == 'True'){
      $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
      while ($data = tep_db_fetch_array($data_query))
      {
        echo "var grossValue = document.getElementById('products_groups_prices_" . $data['groups_id'] . "[".$currencies->currencies[$key]['id']."]').value;\n";
        echo "if (taxRate > 0) {\n";
        echo "grossValue = grossValue * ((taxRate / 100) + 1);\n";
        echo "}\n";
        echo "document.getElementById('products_groups_prices_gross_" . $data['groups_id'] . "[".$currencies->currencies[$key]['id']."]').value = doRound(grossValue, 4);\n";
      }
      }
    }
    ?>
  }
  function updateNet() {
    var taxRate = getTaxRate();

    <?php
    foreach ($currencies->currencies as $key => $value)
    {
      echo "var netValue = document.getElementById('products_price_gross[".$currencies->currencies[$key]['id']."]').value;\n";
      echo "if (taxRate > 0) {\n";
      echo "netValue = netValue / ((taxRate / 100) + 1);\n";
      echo "}\n";
      echo "document.getElementById('products_price[".$currencies->currencies[$key]['id']."]').value = doRound(netValue, 4);\n";
      if (CUSTOMERS_GROUPS_ENABLE == 'True'){
      $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
      while ($data = tep_db_fetch_array($data_query))
      {
        echo "var netValue = document.getElementById('products_groups_prices_gross_" . $data['groups_id'] . "[".$currencies->currencies[$key]['id']."]').value;\n";

        echo "if (taxRate > 0) {\n";
        echo "  netValue = netValue / ((taxRate / 100) + 1);\n";
        echo "}\n";
        echo "document.getElementById('products_groups_prices_" . $data['groups_id'] . "[".$currencies->currencies[$key]['id']."]').value = doRound(netValue, 4);\n";
      }
      }
    }
    ?>
  }
  <?php
} else {
  ?>
  function updateGross() {
    var taxRate = getTaxRate();
    var grossValue = document.forms["new_product"].products_price.value;

    if (taxRate > 0) {
      grossValue = grossValue * ((taxRate / 100) + 1);
    }
    document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);

    <?php
    if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
    while ($data = tep_db_fetch_array($data_query))
    {
      echo 'var fieldValue = document.getElementById("products_groups_prices_' . $data['groups_id'] . '").value' . "\n";
      echo 'if (fieldValue == -1){' . "\n";
      echo 'document.getElementById("products_groups_prices_gross_' . $data['groups_id'] . '").value = fieldValue;' . "\n";
      echo '}else{' . "\n";
      if ($data['groups_is_tax_applicable']){
        echo 'if (taxRate > 0){' . "\n";
        echo 'fieldValue = fieldValue * ((taxRate / 100) + 1);' . "\n";
        echo '}' . "\n";
      }
      echo 'document.getElementById("products_groups_prices_gross_' . $data['groups_id'] . '").value = fieldValue;' . "\n";
      echo '}' . "\n";
    }
    }
    ?>
  }

  function updateNet() {
    var taxRate = getTaxRate();
    var netValue = document.forms["new_product"].products_price_gross.value;

    if (taxRate > 0) {
      netValue = netValue / ((taxRate / 100) + 1);
    }

    document.forms["new_product"].products_price.value = doRound(netValue, 4);
    <?php
    if (CUSTOMERS_GROUPS_ENABLE == 'True'){
    $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
    while ($data = tep_db_fetch_array($data_query))
    {
      echo 'var fieldValue = document.getElementById("products_groups_prices_gross_' . $data['groups_id'] . '").value' . "\n";
      echo 'if (fieldValue == -1){' . "\n";
      echo 'document.getElementById("products_groups_prices_' . $data['groups_id'] . '").value = fieldValue;' . "\n";
      echo '}else{' . "\n";
      if ($data['groups_is_tax_applicable']){
        echo 'if (taxRate > 0){' . "\n";
        echo 'fieldValue = fieldValue / ((taxRate / 100) + 1);' . "\n";
        echo '}' . "\n";
      }
      echo 'document.getElementById("products_groups_prices_' . $data['groups_id'] . '").value = fieldValue;' . "\n";
      echo '}' . "\n";
    }
    }
    ?>
  }
  <?php
}
?>

var counter = 0;
<?php
if (PRODUCTS_INVENTORY == 'True') {
?>

  var options_ids = new Array();
  var options_values_ids = new Array();

  function arraySort(a, b){
    if ((1*a['id']) > (1*b['id'])){
      return 1;
    }else{
      return -1;
    }
  }

  function add_options(options_id, options_values_id, options_name, options_values_name){
    var found_flag = false;
    for (i=0;i<options_ids.length;i++){
      if (options_ids[i]['id'] == options_id){
        found_flag = true;
        break;
      }
    }
    if (!found_flag){
      options_ids[options_ids.length] = new Array();
      options_ids[options_ids.length - 1]['id'] = options_id;
      options_ids[options_ids.length - 1]['value'] = options_name;
      options_values_ids[options_id] = new Array();
      options_values_ids[options_id][options_values_ids[options_id].length] = new Array();
      options_values_ids[options_id][options_values_ids[options_id].length - 1]['id'] = options_values_id;
      options_values_ids[options_id][options_values_ids[options_id].length - 1]['value'] = options_values_name;
    }else{
      found_flag = false;
      for (i=0;i<options_values_ids[options_id].length;i++){
        if (options_values_ids[options_id][i]['id'] == options_values_id){
          found_flag = true;
          break;
        }
      }
      if (!found_flag){
        options_values_ids[options_id][options_values_ids[options_id].length] = new Array();
        options_values_ids[options_id][options_values_ids[options_id].length - 1]['id'] = options_values_id;
        options_values_ids[options_id][options_values_ids[options_id].length - 1]['value'] = options_values_name;
      }
    }

  }
<?php
}
?>
function htmlspecialchars_js(html_string) {
  html_string = html_string.replace(/&/g, "&amp;");
  html_string = html_string.replace(/</g, "&lt;");
  html_string = html_string.replace(/>/g, "&gt;");
  html_string = html_string.replace(/"/g, "&quot;"); //"
  return html_string;
}

function moreFields() {
  if (document.new_product.attributes.options.selectedIndex < 0) return;
  var existingFields = document.new_product.getElementsByTagName('select');
  var attributeExists = false;

  for (i=0; i<existingFields.length; i++) {
    if (existingFields[i].name == 'price_prefix[' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '][' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value + ']') {
      attributeExists = true;
      break;
    }
  }

  if (attributeExists == false) {
    counter++;
    var newFields = document.getElementById('readroot').cloneNode(true);
    newFields.id = '';
    var tableFields = newFields.getElementsByTagName('table');
    tableFields[0].id = 'attribute-' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value;
    newFields.style.display = 'block';

    var spanFields = newFields.getElementsByTagName('span');
    var inputFields = newFields.getElementsByTagName('input');
    var selectFields = newFields.getElementsByTagName('select');
<?php
    if (USE_MARKET_PRICES == 'True') {
?>
      var divFields = newFields.getElementsByTagName('div');
      for (var y=0; y<divFields.length; y++){
        if (divFields[y].id == 'new_pricesTabPane'){
          divFields[y].id = divFields[y].id.substr(4) + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value;

        }
<?php
      foreach ($currencies->currencies as $key => $value)
      {
?>
          if (divFields[y].id == 'new_tabCurrency_<?php echo $currencies->currencies[$key]['id']; ?>'){
            divFields[y].className = 'tab-page';
            divFields[y].id = divFields[y].id.substr(4) + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value;
          }
<?php
      }
?>
      }
<?php
    }
?>
    
    spanFields[0].innerHTML = htmlspecialchars_js(document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.label);
    spanFields[1].innerHTML = htmlspecialchars_js(document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].text);

    for (y=0; y<inputFields.length; y++) {
      if (inputFields[y].type == 'file') {
        inputFields[y].name = inputFields[y].name.substr(4) + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value;
      }else if (inputFields[y].type != 'button') {
        if (inputFields[y].name == 'new_products_options_name'){
          inputFields[y].value = document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.label;
        }
        if (inputFields[y].name == 'new_products_options_values_name'){
          inputFields[y].value = document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].text;
        }
        if (inputFields[y].name.indexOf('[') != -1){
          inputFields[y].name = inputFields[y].name.substr(4, inputFields[y].name.indexOf('[') - 4) + '[' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '][' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value + ']' + inputFields[y].name.substr(inputFields[y].name.indexOf('['));
        }else{
          inputFields[y].name = inputFields[y].name.substr(4) + '[' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '][' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value + ']';
        }
        inputFields[y].disabled = false;
      }
    }

    for (y=0; y<selectFields.length; y++) {
      selectFields[y].name = selectFields[y].name.substr(4) + '[' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '][' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value + ']';
      selectFields[y].disabled = false;
    }

    var insertHere = document.getElementById('writeroot');
    insertHere.parentNode.insertBefore(newFields,insertHere);

<?php
    if (USE_MARKET_PRICES == 'True') {
?>
      var pricesTabPane = new WebFXTabPane( document.getElementById( "pricesTabPane" + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id + '_' + document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value), false,  mainTabPane );
      updateDiv();
<?php
    }
?>
  }
<?php
  if (PRODUCTS_INVENTORY == 'True') {
?>
    add_options(document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.id, document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].value, document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].parentNode.label, document.new_product.attributes.options[document.new_product.attributes.options.selectedIndex].text);
    update_inventory();
<?php
  }
?>
}

function updateDiv()
{
  if (document.all && mainTabPane != 'undefined' && mainTabPane != null){
    var safe = mainTabPane.pages[mainTabPane.getSelectedIndex()].element.style.display;
    mainTabPane.pages[mainTabPane.getSelectedIndex()].element.style.display = "none";
    mainTabPane.pages[mainTabPane.getSelectedIndex()].element.style.display = safe;
  }  
}

function remove_options(cStr){
  var c = cStr.substring(cStr.indexOf('-') + 1, cStr.length);
  var ar = c.split('_');
  var idx;
  var options_ids_tmp = new Array();
  var options_values_ids_tmp = new Array();

  for (i=0;i<options_ids.length;i++){
    if (options_ids[i]['id'] != ar[0]){
      options_ids_tmp[options_ids_tmp.length] = new Array();
      options_ids_tmp[options_ids_tmp.length - 1]['id'] =options_ids[i]['id'];
      options_ids_tmp[options_ids_tmp.length - 1]['value'] =options_ids[i]['value'];
      options_values_ids_tmp[options_ids[i]['id']] = new Array()
      for (j=0;j<options_values_ids[options_ids[i]['id']].length;j++){
        options_values_ids_tmp[options_ids[i]['id']][options_values_ids_tmp[options_ids[i]['id']].length] = new Array();
        options_values_ids_tmp[options_ids[i]['id']][options_values_ids_tmp[options_ids[i]['id']].length - 1]['id'] = options_values_ids[options_ids[i]['id']][j]['id'];
        options_values_ids_tmp[options_ids[i]['id']][options_values_ids_tmp[options_ids[i]['id']].length - 1]['value'] = options_values_ids[options_ids[i]['id']][j]['value'];
      }
    }else{
      if (options_values_ids[ar[0]].length == 1){
        if (options_values_ids[ar[0]][0]['id'] != ar[1]){
          options_ids_tmp[options_ids_tmp.length] = new Array();
          options_ids_tmp[options_ids_tmp.length - 1]['id'] =options_ids[i]['id'];
          options_ids_tmp[options_ids_tmp.length - 1]['value'] =options_ids[i]['value'];
          options_values_ids_tmp[ar[0]][options_values_ids_tmp[ar[0]].length] = new Array();
          options_values_ids_tmp[ar[0]][options_values_ids_tmp[ar[0]].length - 1]['id'] = options_values_ids[ar[0]][j]['id'];
          options_values_ids_tmp[ar[0]][options_values_ids_tmp[ar[0]].length - 1]['value'] = options_values_ids[ar[0]][j]['value'];
        }
      }else{
        options_ids_tmp[options_ids_tmp.length] = new Array();
        options_ids_tmp[options_ids_tmp.length - 1]['id'] =options_ids[i]['id'];
        options_ids_tmp[options_ids_tmp.length - 1]['value'] =options_ids[i]['value'];
        options_values_ids_tmp[ar[0]] = new Array()
        for (j=0;j<options_values_ids[ar[0]].length;j++){
          if (options_values_ids[ar[0]][j]['id'] != ar[1]){
            options_values_ids_tmp[ar[0]][options_values_ids_tmp[ar[0]].length] = new Array();
            options_values_ids_tmp[ar[0]][options_values_ids_tmp[ar[0]].length - 1]['id'] = options_values_ids[ar[0]][j]['id'];
            options_values_ids_tmp[ar[0]][options_values_ids_tmp[ar[0]].length - 1]['value'] = options_values_ids[ar[0]][j]['value'];

          }

        }
      }
    }
  }
  options_ids = options_ids_tmp;
  options_values_ids = options_values_ids_tmp;

  update_inventory();
}

function toggleAttributeStatus(cBtn, attributeID) {
  cBtn.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild(cBtn.parentNode.parentNode.parentNode.parentNode);
  updateDiv();
<?php
  if (PRODUCTS_INVENTORY == 'True') {
?>
    remove_options(attributeID);
<?php
  }
?>
  /*
  var row = document.getElementById(attributeID);
  var rowButton = document.getElementById(attributeID + '-button');
  var inputFields = row.getElementsByTagName('input');
  var selectFields = row.getElementsByTagName('select');

  if (rowButton.value == '-') {
  for (rF=0; rF<inputFields.length; rF++) {
  if (inputFields[rF].type != 'button') {
  inputFields[rF].disabled = true;
  }
  }

  for (rF=0; rF<selectFields.length; rF++) {
  selectFields[rF].disabled = true;
  }

  row.className = 'attributeRemove';
  rowButton.value = '+';
  } else {
  for (rF=0; rF<inputFields.length; rF++) {
  if (inputFields[rF].type != 'button') {
  inputFields[rF].disabled = false;
  }
  }

  for (rF=0; rF<selectFields.length; rF++) {
  selectFields[rF].disabled = false;
  }

  row.className = '';
  rowButton.value = '-';
  }
  */
}


function reloadImage(sSource, sDest) {
  var image = document.new_product[sSource].value;
  var preview = document.getElementById(sDest);

  preview.src = '../images/' + image;
}
//-->
</script>

    <?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '') . (isset($HTTP_GET_VARS['mID']) ? '&mID=' . $HTTP_GET_VARS['mID'] : '') . (isset($HTTP_GET_VARS['bm']) ? '&bm=' . $HTTP_GET_VARS['bm'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
<?php if ($bm == 1) { ?>
              <td class="pageHeading">New products in</td><td><?php echo tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $pInfo->categories_id); ?></td>
<?php } else { ?>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
<?php } ?>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="20" cellpadding="5">
        <tr>
          <td>
<div class="tab-pane" id="mainTabPane">
  <script type="text/javascript"><!--
  var mainTabPane = new WebFXTabPane( document.getElementById( "mainTabPane" ) );
  //-->
  </script>
  
  <div class="tab-page" id="tabDescription">
    <h2 class="tab"><?php echo TAB_GENERAL; ?></h2>
    

    <script type="text/javascript"><!--
    mainTabPane.addTabPage( document.getElementById( "tabDescription" ) );
    //-->
    </script>

    <div class="tab-pane" id="descriptionTabPane">
      <script type="text/javascript"><!--
      var descriptionTabPane = new WebFXTabPane( document.getElementById( "descriptionTabPane" ) );
      //-->
      </script>
<?php
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>

<?php
$affiliates = tep_get_affiliates();
if (count($affiliates) > 0) {
  
?>
      <div class="tab-page" id="tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        
        <script type="text/javascript"><!--
    descriptionTabPane.addTabPage( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
    //-->
    </script>
    
        <script type="text/javascript"><!--
        var descriptionTabPane_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>
        
        

      <div class="tab-page" id="affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
        
<?php  
} else {
?>
      <div class="tab-page" id="tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane.addTabPage( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
  <?php
}
?>        
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? $products_name[$languages[$i]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION_SHORT; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_description_short[' . $languages[$i]['id'] . ']',  (isset($products_description_short[$languages[$i]['id']]) ? $products_description_short[$languages[$i]['id']] : tep_get_products_description_short($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText">
				<?php echo TEXT_DIRECT_URL; ?>
			</td>
            <td class="smallText">
				<?php echo tep_draw_input_field('direct_url[' . $languages[$i]['id'] . ']', (isset($direct_url[$languages[$i]['id']]) ? $direct_url[$languages[$i]['id']] : tep_get_direct_url($pInfo->products_id, $languages[$i]['id'])), 'size="50"'); ?>
				<input type="button" value="generate" class="generate-direct-url" />
				<? include ("java/core/jquery.phtml"); ?>
				<script>
				$(document).ready(function() {
					$(".generate-direct-url").click(function() {
						var product_name = $("input[name='products_name[1]']").val();
						var url = product_name
							.toLowerCase() // change everything to lowercase
							.replace(/^\s+|\s+$/g, "") // trim leading and trailing spaces		
							.replace(/[_|\s]+/g, "-") // change all spaces and underscores to a hyphen
							.replace(/[^a-z0-9-]+/g, "") // remove all non-alphanumeric characters except the hyphen
							.replace(/[-]+/g, "-") // replace multiple instances of the hyphen with a single instance
							.replace(/^-+|-+$/g, "") // trim leading and trailing hyphens						
						if (confirm("Suggested URL: '"+url+"'")) {
							$("input[name='direct_url[1]']").val(url);
						}
					});
				});				
				</script>
			</td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_description[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']))); ?></fieldset></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_FEATURES; ?></td>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_features[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_features[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_features[$languages[$i]['id']]) ? $products_features[$languages[$i]['id']] : tep_get_products_features($pInfo->products_id, $languages[$i]['id']))); ?></fieldset></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_FAQ; ?></td>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_faq[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_faq[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($products_faq[$languages[$i]['id']]) ? $products_faq[$languages[$i]['id']] : tep_get_products_faq($pInfo->products_id, $languages[$i]['id']))); ?></fieldset></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_EBAY_DESCRIPTION.'<br>';
              $show_ebay_desc = (isset($products_ebay_description[$languages[$i]['id']]) ? $products_ebay_description[$languages[$i]['id']] : tep_get_products_ebay_description($pInfo->products_id, $languages[$i]['id']));
              $have_custom_desc = true;
              $idx = 0;
              $_show_ebay_desc = (isset($products_description[$languages[$i]['id']]) ? $products_description[$languages[$i]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id']));
              $_show_ebay_desc = ebay_item_prepeare_description( $_show_ebay_desc );
              echo tep_draw_hidden_field('hide_ebay_clean['. $languages[$i]['id'] .']',$_show_ebay_desc);
              if ( empty($show_ebay_desc) ) {
                $have_custom_desc = false;
                $show_ebay_desc = $_show_ebay_desc;
              }
              echo '<label><input type="checkbox" '.($have_custom_desc?'checked':'').' onclick="'.
              'if (this.checked) {'.
              ' this.form.elements[\'products_ebay_description[' . $languages[$i]['id'] . ']\'].disabled=false;'.
              '}else{'.
              ' this.form.elements[\'products_ebay_description[' . $languages[$i]['id'] . ']\'].value=this.form.elements[\'hide_ebay_clean[' . $languages[$i]['id'] . ']\'].value;'.
              ' this.form.elements[\'products_ebay_description[' . $languages[$i]['id'] . ']\'].disabled=true;'.
              '} '.
              '">'.LABEL_OVERRIDE_EBAY_DESCRIPTION.'</label>';
              echo '</td>';
            ?>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_ebay_description[' . $languages[$i]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_ebay_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', $show_ebay_desc, $have_custom_desc?'':'disabled'); ?></fieldset></td>
          </tr>
<?php
if (SEARCH_ENGINE_UNHIDE == 'True'){
?>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
           <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
           <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_KEYWORDS; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
}
?>
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>

        </table>
<?PHP
if (count($affiliates) > 0) {
?>
   </div>   
<?php

  for($j=0;$j<sizeof($affiliates);$j++) {
?>
        <div class="tab-page" id="affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        descriptionTabPane_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_name_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', (isset($products_name_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_name_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_name($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION_SHORT; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_description_short_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']',  (isset($products_description_short_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_description_short_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_description_short($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_DIRECT_URL; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('direct_url_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']',  (isset($direct_url_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $direct_url_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_direct_url($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_description_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_description_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '15', (isset($products_description_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_description_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_description($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></fieldset></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_FEATURES; ?></td>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_features_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_features_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '15', (isset($products_features_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_features_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_features($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></fieldset></td>
          </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_FAQ; ?></td>
            <td class="smallText"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'new_product\',\'products_faq_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('products_faq_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '15', (isset($products_faq_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_faq_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_faq($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></fieldset></td>
          </tr>
<?php
if (SEARCH_ENGINE_UNHIDE == 'True'){
?>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_PAGE_TITLE; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('products_head_title_tag_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '5', (isset($products_head_title_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_head_title_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_head_title_tag($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
           <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('products_head_desc_tag_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '5', (isset($products_head_desc_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_head_desc_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_head_desc_tag($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
           <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_KEYWORDS; ?></td>
            <td class="smallText"><?php echo tep_draw_textarea_field('products_head_keywords_tag_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', 'soft', '70', '5', (isset($products_head_keywords_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_head_keywords_tag_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_head_keywords_tag($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>
<?php
}
?>
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_url_affiliate[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', (isset($products_url_affiliate[$languages[$i]['id']][$affiliates[$j]['id']]) ? $products_url_affiliate[$languages[$i]['id']][$affiliates[$j]['id']] : tep_get_products_url($pInfo->products_id, $languages[$i]['id'], $affiliates[$j]['id']))); ?></td>
          </tr>

        </table>
      </div>
<?    
  }
?>
<?php
  
}
?>
      </div>
<?php
}
?>

    </div>
    
 </div>
 
   <div class="tab-page" id="tabData">
    <h2 class="tab" id="tab_data"><?php echo TAB_DATA; ?></h2>

    <script type="text/javascript"><!--
    mainTabPane.addTabPage( document.getElementById( "tabData" ) );
    //-->
    </script>
    
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="50%" height="100%" valign="top">
          <fieldset style="height: 100%;">
            <legend><?php echo TEXT_LEGEND_PRICE; ?></legend>

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                 <td class="smallText"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
                 <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
              </tr>
              <tr>
                 <td class="smallText"><?php echo TEXT_PRODUCTS_VAT_EXEMPT_FLAG; ?></td>
                 <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('vat_exempt_flag', array( array('id'=>0,'text'=>TEXT_VE_NO), array('id'=>1,'text'=>TEXT_VE_YES), ), $pInfo->vat_exempt_flag); ?></td>
              </tr>

<?php
if (USE_MARKET_PRICES == 'True'){
?>  
  <tr><td colspan="2" style="margin:0px;width:100%;height:100%;">
    <div class="tab-pane" id="pricesTabPane">
      <script type="text/javascript"><!--
      var pricesTabPane = new WebFXTabPane( document.getElementById( "pricesTabPane" ) );
      //-->
      </script>
  
<?php
foreach ($currencies->currencies as $key => $value)
{
?>
        <div class="tab-page" id="tabCurrency_<?php echo $currencies->currencies[$key]['id']; ?>">
        <h2 class="tab" id="currencies_tab<?php echo $currencies->currencies[$key]['id']; ?>"><?php echo $currencies->currencies[$key]['title']; ?></h2>

        <script type="text/javascript"><!--
        pricesTabPane.addTabPage( document.getElementById( "tabCurrency_<?php echo $currencies->currencies[$key]['id']; ?>" ) );
        //-->
        </script>
          <table border="0" cellpadding="1" cellspacing="0">
            <tr>
            <td class="smallText" nowrap><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price[' . $currencies->currencies[$key]['id'] . ']', (($products_price[$currencies->currencies[$key]['id']]) ? stripslashes($products_price[$currencies->currencies[$key]['id']]) : tep_get_products_price($pInfo->products_id, $currencies->currencies[$key]['id'])), 'onKeyUp="updateGross()" id="products_price[' . $currencies->currencies[$key]['id'] . ']"'); ?></td>
          </tr>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross[' . $currencies->currencies[$key]['id'] . ']', '', 'OnKeyUp="updateNet()" id="products_price_gross[' . $currencies->currencies[$key]['id'] . ']"'); ?></td>
          </tr>
<?php
if (DISCOUNT_TABLE_ENABLE == 'True'){
?>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_DISCOUNT_PRICE; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_discount[' . $currencies->currencies[$key]['id'] . ']', (!empty($_POST)?$_POST['products_price_discount'][$currencies->currencies[$key]['id']]:tep_get_products_discount_price($pInfo->products_id, $currencies->currencies[$key]['id'], 0)) , ''); ?></td>
          </tr>
<?
}
?>          
<?php
if (CUSTOMERS_GROUPS_ENABLE == 'True'){
$data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
while ($data = tep_db_fetch_array($data_query))
{
?>
          <tr><td class="smallText" colspan="2"><b><?php echo $data['groups_name'];?></b></td></tr>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_groups_prices_' . $data['groups_id'] . '[' . $currencies->currencies[$key]['id'] . ']', (!empty($_POST)?$_POST['products_groups_prices_' . $data['groups_id']][$currencies->currencies[$key]['id']]:tep_get_products_price($pInfo->products_id, $currencies->currencies[$key]['id'], $data['groups_id'], '-2')), 'onKeyUp="updateGross()" id="products_groups_prices_' . $data['groups_id'] . '[' . $currencies->currencies[$key]['id'] . ']"'); ?></td>
          </tr>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_groups_prices_gross_' . $data['groups_id'] . '[' . $currencies->currencies[$key]['id'] . ']', '', 'OnKeyUp="updateNet()"  id="products_groups_prices_gross_' . $data['groups_id'] . '[' . $currencies->currencies[$key]['id'] . ']"'); ?></td>
          </tr>
<?php
if (DISCOUNT_TABLE_ENABLE == 'True'){
?>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_DISCOUNT_PRICE; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_discount_' . $data['groups_id'] . '[' . $currencies->currencies[$key]['id'] . ']', (!empty($_POST)?$_POST['products_price_discount_' . $data['groups_id']][$currencies->currencies[$key]['id']]:tep_get_products_discount_price($pInfo->products_id, $currencies->currencies[$key]['id'], $data['groups_id'])), ''); ?></td>
          </tr>
<?
}
}
?>
<?
}
?> 
          </table>
        
        </div>
<?php
}
echo '</div></td></tr>';
}else{
?>
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', tep_get_products_price($pInfo->products_id), 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', '', 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
<?php
if (DISCOUNT_TABLE_ENABLE == 'True'){
?>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_DISCOUNT_PRICE; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_discount', $pInfo->products_price_discount, ''); ?></td>
          </tr>
<?
}
?>
<?php
if (CUSTOMERS_GROUPS_ENABLE == 'True'){
$data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
while ($data = tep_db_fetch_array($data_query))
{
?>
          <tr><td class="smallText" colspan="2"><b><?php echo $data['groups_name'];?></b></td></tr>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_groups_prices_' . $data['groups_id'], tep_get_products_price($pInfo->products_id, 0, $data['groups_id'], '-2'), 'onKeyUp="updateGross()" id="products_groups_prices_' . $data['groups_id'] . '"'); ?></td>
          </tr>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_groups_prices_gross_' . $data['groups_id'], '', 'OnKeyUp="updateNet()"  id="products_groups_prices_gross_' . $data['groups_id'] . '"'); ?></td>
          </tr>
<?php
if (DISCOUNT_TABLE_ENABLE == 'True'){
?>
          <tr >
            <td class="smallText"><?php echo TEXT_PRODUCTS_DISCOUNT_PRICE; ?></td>
            <td class="smallText"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_group_discount_price_' . $data['groups_id'], tep_get_products_discount_price($pInfo->products_id, 0, $data['groups_id']), ''); ?></td>
          </tr>
<?
}
?>
<?
}
}
?>
<?php
}
?>

           </table>
            <script language="javascript"><!--
            updateGross();
            //-->
            </script>
          </fieldset>
       </td>
      <td class="smallText" width="50%" height="100%" valign="top">
           <fieldset style="height: 100%;">
            <legend><?php echo TEXT_LEGEND_DATA; ?></legend>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
<?php if ($bm == 1) { ?>
                <td class="main"><?php echo tep_draw_hidden_field('manufacturers_id', $mID) . tep_get_manufacturers_name($mID); ?></td>
<?php } else { ?>
                <td class="main"><?php echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
<?php } ?>
              </tr>
<?php
if (VENDOR_ENABLED == 'true' && !tep_session_is_registered('login_vendor')){
?>
              <tr>
                <td class="smallText"><?php echo TEXT_PRODUCTS_VENDOR; ?></td>
                <td class="main"><?php echo tep_draw_pull_down_menu('vendor_id', $vendors_array, $pInfo->vendor_id); ?></td>
              </tr>

<?php
}
?>
              <tr>
                <td class="smallText"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
                <td class="smallText"><?php echo tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
              </tr>
             <?php if (!(defined('PRODUCTS_INVENTORY') && PRODUCTS_INVENTORY == 'True')) { ?>
              <tr>
                <td class="smallText"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
                <td class="smallText"><?php
                if ( defined('PRODUCTS_INVENTORY') && PRODUCTS_INVENTORY == 'True') {
                  echo (int)$pInfo->products_quantity.'&nbsp;+/-&nbsp;' . tep_draw_input_field('products_quantity');
                }else{ 
                  echo tep_draw_input_field('products_quantity', $pInfo->products_quantity);
                } 
                ?></td>
              </tr>
              <?php } ?>
              <tr>
                <td class="smallText"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
                <td class="smallText"><?php echo tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
              </tr>
          <tr>
            <td class="smallText" valign="top"><?php echo TEXT_PRODUCTS_FILE; ?></td>
            <td class="smallText"><?php echo tep_draw_file_field('products_file'); ?><br>
          <?php
          if ($pInfo->products_file != ''){
            echo '<a href="' . tep_href_link(FILENAME_DOWNLOAD, 'filename='.$pInfo->products_file)  . '">' .$pInfo->products_file. '</a><br>';
            echo tep_draw_hidden_field('products_previous_file', $pInfo->products_file) . '<input type="checkbox" name="delete_products_file" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT ;
          }
          ?>
          </td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_PRODUCTS_SEO_PAGE_NAME; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_seo_page_name', $pInfo->products_seo_page_name); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_SORT_ORDER; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('sort_order', $pInfo->sort_order); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_MPN; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_mpn', $pInfo->products_mpn); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_EAN; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_ean', $pInfo->products_ean); ?></td>
          </tr>
          <tr>
            <td class="smallText"><?php echo TEXT_UPC; ?></td>
            <td class="smallText"><?php echo tep_draw_input_field('products_upc', $pInfo->products_upc); ?></td>
          </tr>
            </table>
           </fieldset>
      </td>
     </tr>
     <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
          <fieldset style="height: 100%;">
            <legend><?php echo TEXT_PRODUCTS_STATUS; ?></legend>
            <?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?>
           </fieldset>
      </td>
        <td class="smallText" width="50%" height="100%" valign="top">
          <fieldset style="height: 100%;">
            <legend><?php echo TEXT_LEGEND_INFORMATION; ?></legend>

            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?></td>
                <td class="smallText"><?php echo tep_draw_calendar('new_product','products_date_available', $pInfo->products_date_available ); ?><small>(<?php echo strtolower(DATE_FORMAT_SPIFFYCAL); ?>)</small></td>
              </tr>
            </table>
          </fieldset>
        </td>      
     </tr>
     </table>

   </div>
<?php
$affiliates = tep_get_affiliates(false);
if (count($affiliates)){
?>
   <div class="tab-page" id="tabAffiliates">
    <h2 class="tab"><?php echo TAB_AFFILIATES; ?></h2>

    <script type="text/javascript"><!--
    mainTabPane.addTabPage( document.getElementById( "tabAffiliates" ) );
    //-->
    </script>
   <table border="0" width="100%" cellspacing="0" cellpadding="2" style="height:100%">
<?PHP
  for ($j=0;$j<count($affiliates);$j++){
    $check = tep_db_query("select * from " . TABLE_PRODUCTS_TO_AFFILIATES . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and affiliate_id = '" . $affiliates[$j]['id'] . "'");
?>
    <tr>
      <td class="main" width="25"><?php echo tep_draw_checkbox_field('products_to_affiliates[]', $affiliates[$j]['id'], tep_db_num_rows($check), '', 'id="id_aff' . $j . '"'); ?></td>
      <td class="main"><label for="id_aff<?php echo $j; ?>"><?php echo $affiliates[$j]['name']; ?></label></td>
    </tr>
<?php
  }
?>
   </table>
   </div>
<?php
}
?>

   <div class="tab-page" id="tabImages">
    <h2 class="tab"><?php echo TAB_IMAGES; ?></h2>

    <script type="text/javascript"><!--
    mainTabPane.addTabPage( document.getElementById( "tabImages" ) );
    //-->
    </script>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td class="smallText" width="50%" style="height:100%" valign="top">
       <fieldset>
         <legend><?php echo TEXT_LEGEND_SMALL_IMAGE; ?></legend>
    <table border="0" width="100%" cellspacing="0" cellpadding="2" style="height:100%">
      <tr style="height:100%">
        <td class="smallText" width="50%" style="height:100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php if(IMAGE_RESIZE == 'GD' || IMAGE_RESIZE == 'ImageMagick') { ?>
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend>
<?php
    $checkbox_disabled = '';
    if(IMAGE_RESIZE == 'GD' && !function_exists("gd_info")) {
      $checkbox_disabled = 'disabled="disabled"';
    }
    if(IMAGE_RESIZE == 'ImageMagick' && !is_executable(CONVERT_UTILITY)) {
      $checkbox_disabled = 'disabled="disabled"';
    }
    $checkbox_check = true;
    if ($checkbox_disabled == 'disabled="disabled"' || $pInfo->products_image != '') {
      $checkbox_check = false;
    }
    echo tep_draw_checkbox_field('resize_sm', 'yes', $checkbox_check, '' , ($checkbox_disabled != '' ? $checkbox_disabled : '') . 'id="resize_small_image" OnClick="ChangeNewImageStyle(this, \'id_small_image\');"');
    echo '<label for="resize_small_image">' . TEXT_PRODUCTS_IMAGE_SM_RESIZE . '</label>';
?>
                  </legend>
                </fieldset>
              </td>
            </tr>
<?php } ?>
            <tr>
              <td class="smallText" width="50%" style="height:100%" valign="top">
                <fieldset id="id_small_image_chooser">
                  <legend><?php echo TEXT_IMAGE_LOCATION; ?></legend>
                  <p><?php echo DIR_WS_CATALOG . 'images/' . tep_draw_input_field('products_image', (isset($pInfo) ? $pInfo->products_image : '')) . tep_draw_hidden_field('products_previous_image', (isset($pInfo) ? $pInfo->products_image : '')) . '&nbsp;<input type="button" value="Preview" onClick="reloadImage(\'products_image\', \'previewImage\');" class="infoBoxButton">'; ?></p>
                  <p><?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' ;?></p>
                  
                </fieldset>
              </td>
            </tr>
            <tr>
              <td class="smallText" width="50%" style="height:100%" valign="top">
                <fieldset id="id_small_image">
                  <legend><?php echo TEXT_UPLOAD_NEW_IMAGE; ?></legend>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                      <td class="smallText"><?php echo tep_draw_file_field('products_image_new'); ?></td>
                    </tr>
                    <tr>
                      <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
                      <td class="smallText"><?php echo tep_draw_pull_down_menu('products_image_location', $image_directories, dirname($pInfo->products_image)); ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>
<?php if(isset($checkbox_check) && $checkbox_check) { ?>
          <script language="JavaScript" type="text/javascript"><!--
            document.getElementById("id_small_image").style.display = 'none';
            document.getElementById("id_small_image_chooser").style.display = 'none';
          //-->
          </script>
<?php } ?>
        </td>
        <td class="smallText" width="50%" style="height:100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend><?php echo TEXT_PREVIEW; ?></legend>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td align="center"><?php 
                      if (!is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $pInfo->products_image) || $pInfo->products_image == ''){
                        echo tep_image('../images/spacer.gif', '', '', '', 'id="previewImage"');
                      }else{
                        echo tep_image('../images/' . $pInfo->products_image, '', '', '', 'id="previewImage"');
                      }
                      ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>    
    </fieldset>
    </td>
    </tr>
    <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
       <fieldset>
         <legend><?php echo TEXT_LEGEND_MEDIUM_IMAGE; ?></legend>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="50%" height="100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php if(IMAGE_RESIZE == 'GD' || IMAGE_RESIZE == 'ImageMagick') { ?>
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend>
<?php
    $checkbox_disabled = '';
    if(IMAGE_RESIZE == 'GD' && !function_exists("gd_info")) {
      $checkbox_disabled = 'disabled=""';
    }
    if(IMAGE_RESIZE == 'ImageMagick' && !is_executable(CONVERT_UTILITY)) {
      $checkbox_disabled = 'disabled=""';
    }
    $checkbox_check = true;
    if ($checkbox_disabled == 'disabled=""' || $pInfo->products_image_med != '') {
      $checkbox_check = false;
    }
    echo tep_draw_checkbox_field('resize_med', 'yes', $checkbox_check, '' , ($checkbox_disabled != '' ? $checkbox_disabled : '') . 'id="resize_medium_image" OnClick="ChangeNewImageStyle(this, \'id_medium_image\');"');
    echo '<label for="resize_medium_image">' . TEXT_PRODUCTS_IMAGE_MED_RESIZE . '</label>';
?>
                  </legend>
                </fieldset>
              </td>
            </tr>
<?php } ?>
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset id="id_medium_image_chooser">
                  <legend><?php echo TEXT_IMAGE_LOCATION; ?></legend>
                  <p><?php echo DIR_WS_CATALOG . 'images/' . tep_draw_input_field('products_image_med', (isset($pInfo) ? $pInfo->products_image_med : '')) . tep_draw_hidden_field('products_previous_image_med', (isset($pInfo) ? $pInfo->products_image_med : '')) . '&nbsp;<input type="button" value="Preview" onClick="reloadImage(\'products_image_med\', \'previewImageMedium\');" class="infoBoxButton">'; ?></p>
                  <p><?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_med" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' ;?></p>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset id="id_medium_image">
                  <legend><?php echo TEXT_UPLOAD_NEW_IMAGE; ?></legend>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                      <td class="smallText"><?php echo tep_draw_file_field('products_image_med_new'); ?></td>
                    </tr>
                    <tr>
                      <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
                      <td class="smallText"><?php echo tep_draw_pull_down_menu('products_image_med_location', $image_directories, dirname($pInfo->products_image_med)); ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>
<?php if(isset($checkbox_check) && $checkbox_check) { ?>
          <script language="JavaScript" type="text/javascript"><!--
            document.getElementById("id_medium_image").style.display = 'none';
            document.getElementById("id_medium_image_chooser").style.display = 'none';
          //-->
          </script>
<?php } ?>
        </td>
        <td class="smallText" width="50%" style="height:100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend><?php echo TEXT_PREVIEW; ?></legend>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td align="center"><?php 
                      if (!is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $pInfo->products_image_med) || $pInfo->products_image_med == ''){
                        echo tep_image('../images/spacer.gif', '', '', '', 'id="previewImageMedium"');
                      }else{
                        echo tep_image('../images/' . $pInfo->products_image_med, '', '', '', 'id="previewImageMedium"');
                      }
                      ?>
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>  
        </td>
      </tr>
    </table>
    </fieldset>
    </td>
    </tr>
      <tr>
      <td class="smallText" width="50%" height="100%" valign="top">
       <fieldset>
         <legend><?php echo TEXT_LEGEND_LARGE_IMAGE; ?></legend>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText" width="50%" height="100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend><?php echo TEXT_IMAGE_LOCATION; ?></legend>
                  <p><?php echo DIR_WS_CATALOG . 'images/' . tep_draw_input_field('products_image_lrg', (isset($pInfo) ? $pInfo->products_image_lrg : '')) . tep_draw_hidden_field('products_previous_image_lrg', (isset($pInfo) ? $pInfo->products_image_lrg : '')) . '&nbsp;<input type="button" value="Preview" onClick="reloadImage(\'products_image_lrg\', \'previewImageLarge\');" class="infoBoxButton">'; ?></p>
                  <p><?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_lrg" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' ;?></p>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend><?php echo TEXT_UPLOAD_NEW_IMAGE; ?></legend>

                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                      <td class="smallText"><?php echo tep_draw_file_field('products_image_lrg_new'); ?></td>
                    </tr>
                    <tr>
                      <td class="smallText"><?php echo TEXT_DESTINATION; ?></td>
                      <td class="smallText"><?php echo tep_draw_pull_down_menu('products_image_lrg_location', $image_directories, dirname($pInfo->products_image_lrg)); ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>
        </td>
        <td class="smallText" width="50%" height="100%" valign="top">
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="smallText" width="50%" height="100%" valign="top">
                <fieldset>
                  <legend><?php echo TEXT_PREVIEW; ?></legend>

                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td align="center"><?php 
                      if (!is_file(DIR_FS_CATALOG . DIR_WS_IMAGES . $pInfo->products_image_lrg) || $pInfo->products_image_lrg == ''){
                        echo tep_image('../images/spacer.gif', '', '', '', 'id="previewImageLarge"');
                      }else{
                        echo tep_image('../images/' . $pInfo->products_image_lrg, '', '', '', 'id="previewImageLarge"');
                      }
                      ?></td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>    
    </fieldset>
    </td>
    </tr>
<?php
  if (ULTIMATE_ADDITIONAL_IMAGES == 'enable') {
?>

    <tr>
      <td class="smallText" width=100% height=100% valign="top">
        <fieldset style="height:100%">
          <legend><?php echo TEXT_PRODUCTS_IMAGE_ADDITIONAL;?></legend>
            <table border="0" cellpadding="2" cellspacing="0" width="100%">
              <tr>
                <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_TH_NOTICE; ?></td>
                <td class="smalltext" colspan="2" valign="top"><?php echo TEXT_PRODUCTS_IMAGE_XL_NOTICE; ?></td>
              </tr>
<?php
for ($i=1;$i<7;$i++) {
  $name_small = 'products_image_sm_' . $i;
  $name_large = 'products_image_xl_' . $i;
  $name_alt = 'products_image_alt_' . $i;
  if(IMAGE_RESIZE == 'GD' || IMAGE_RESIZE == 'ImageMagick') {
    $file_field_disabled = false;
    if(IMAGE_RESIZE == 'GD' && function_exists("gd_info") && $pInfo->$name_small == '') {
      $file_field_disabled = true;
    } elseif(IMAGE_RESIZE == 'ImageMagick' && is_executable(CONVERT_UTILITY) && $pInfo->$name_small == '') {
      $file_field_disabled = true;
    }
  }
    if(IMAGE_RESIZE == 'GD' || IMAGE_RESIZE == 'ImageMagick') {
?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top">
                  <table border="0" cellpadding="2" cellspacing="0" width="100%">
                    <tr>
                      <td class="smallText" width="50%" height="100%" valign="top">
<?php
      $checkbox_disabled = '';
      if(IMAGE_RESIZE == 'GD' && !function_exists("gd_info")) {
        $checkbox_disabled = 'disabled=""';
      }
      if(IMAGE_RESIZE == 'ImageMagick' && !is_executable(CONVERT_UTILITY)) {
        $checkbox_disabled = 'disabled=""';
      }
      $checkbox_check = true;
      if ($checkbox_disabled == 'disabled=""' || $pInfo->$name_small != '') {
        $checkbox_check = false;
      }
      echo tep_draw_checkbox_field('resize_xl' . $i, 'yes', $checkbox_check, '' , 'id="resize_image_sm_' . $i . '" OnClick="checkbox_addition_image_resize_click(this.checked, \'id_image_sm_' . $i . '\');"' . ($checkbox_disabled != '' ? $checkbox_disabled : ''));
?>
                        <label for="resize_image_sm_<?php echo $i; ?>"><?php echo TEXT_PRODUCTS_IMAGE_SM_RESIZE; ?></label>
                      </td>
                    </tr>
                  </table>
                </td>
                <td class="dataTableRow" colspan="2" valign="top">&nbsp;</td>
              </tr>
<?php
    }
?>

              <tr>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo constant("TEXT_PRODUCTS_IMAGE_SM_" . $i); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_sm_' . $i, '', 'id="id_image_sm_' . $i . '"') . tep_draw_hidden_field('products_previous_image_sm_' . $i, $pInfo->$name_small); ?></span></td>
<?php
  // Set "disabled" attribyte to file field for small additional image
    if($file_field_disabled) {
?>
                    <script language="JavaScript" type="text/javascript"><!--
                      document.getElementById("id_image_sm_<?php echo $i; ?>").disabled = true;
                    //-->
                    </script>
  
<?php
    }
?>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo constant("TEXT_PRODUCTS_IMAGE_XL_" . $i); ?></span></td>
                <td class="dataTableRow" valign="top"><span class="smallText"><?php echo tep_draw_file_field('products_image_xl_' . $i) . tep_draw_hidden_field('products_previous_image_xl_' . $i, $pInfo->$name_large); ?></span></td>
              </tr>
<?php
  if (($HTTP_GET_VARS['pID']) && ($pInfo->$name_small) != '' or ($pInfo->$name_large) != '') {
?>
              <tr>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->$name_small)) { ?><span class="smallText"><?php echo $pInfo->$name_small . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->$name_small, $pInfo->$name_small, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_sm_' . $i . '" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_sm_' . $i . '" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
                <td class="dataTableRow" colspan="2" valign="top"><?php if (tep_not_null($pInfo->$name_large)) { ?><span class="smallText"><?php echo $pInfo->$name_large . '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $pInfo->$name_large, $pInfo->$name_large, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="left" hspace="0" vspace="5"') . '<br>'. tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="unlink_image_xl_' . $i . '" value="yes">' . TEXT_PRODUCTS_IMAGE_REMOVE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;<input type="checkbox" name="delete_image_xl_' . $i . '" value="yes">' . TEXT_PRODUCTS_IMAGE_DELETE_SHORT . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '42'); ?></span><?php } ?></td>
              </tr>
<?php
    }
?>
              <tr>
                <td class="smallText"><?php echo constant("TEXT_PRODUCTS_IMAGE_ALT_" . $i); ?></td>
                <td class="smallText"><?php echo tep_draw_input_field('products_image_alt_' . $i, $pInfo->$name_alt); ?></td>
              </tr> 
<?php
}
?>
            </table>
        </fieldset>
      </td>
    </tr>
<?php
  }else{
    echo tep_draw_hidden_field('products_previous_image_sm_1', $pInfo->products_image_sm_1) .
    tep_draw_hidden_field('products_previous_image_xl_1', $pInfo->products_image_xl_1) .
    tep_draw_hidden_field('products_previous_image_sm_2', $pInfo->products_image_sm_2) .
    tep_draw_hidden_field('products_previous_image_xl_2', $pInfo->products_image_xl_2) .
    tep_draw_hidden_field('products_previous_image_sm_3', $pInfo->products_image_sm_3) .
    tep_draw_hidden_field('products_previous_image_xl_3', $pInfo->products_image_xl_3) .
    tep_draw_hidden_field('products_previous_image_sm_4', $pInfo->products_image_sm_4) .
    tep_draw_hidden_field('products_previous_image_xl_4', $pInfo->products_image_xl_4) .
    tep_draw_hidden_field('products_previous_image_sm_5', $pInfo->products_image_sm_5) .
    tep_draw_hidden_field('products_previous_image_xl_5', $pInfo->products_image_xl_5) .
    tep_draw_hidden_field('products_previous_image_sm_6', $pInfo->products_image_sm_6) .
    tep_draw_hidden_field('products_previous_file', $pInfo->products_file) .
    tep_draw_hidden_field('products_previous_image_xl_6', $pInfo->products_image_xl_6);
  }
?>    
    </table>
    </div>
<?php
$check = tep_db_fetch_array(tep_db_query("select count(sets_id) is_in_bundle from " . TABLE_SETS_PRODUCTS . " where product_id = '" . (int)$HTTP_GET_VARS['pID'] . "'"));
if (PRODUCTS_BUNDLE_SETS == 'True' && !$check['is_in_bundle']) {
?>
    <div class="tab-page" id="tabBundles">
    <h2 class="tab"><?php echo TAB_BUNDLES; ?></h2>

<script type="text/javascript">mainTabPane.addTabPage( document.getElementById( "tabBundles" ) );</script>

<script language="JavaScript" type="text/javascript">
<!--
  var counter = 0;

  function moreSet() {   
    var existingFields = document.new_product.getElementsByTagName('input');   
    var attributeExists = false;

    if (document.new_product.sets_select.options.length <= 0) return;
    if (document.new_product.sets_select.options.selectedIndex < 0) return;

    var val = document.new_product.sets_select.options[document.new_product.sets_select.options.selectedIndex].value;
    if (val.indexOf('prod_') != -1){
      val = val.replace('prod_', '');
      for (i=0; i<existingFields.length; i++) {
        if (existingFields[i].value == val && existingFields[i].name == 'sets_product_id[]') {
          attributeExists = true;
          break;
        }
      }
      if (attributeExists == false) {
        counter++;
        var newFields = document.getElementById('readsets').cloneNode(true);
        newFields.id = '';
        newFields.style.display = 'block';
  
        var inputFields = newFields.getElementsByTagName('input');
  
        for (y=0; y<inputFields.length; y++) {          
          if (inputFields[y].type != 'button') {
            inputFields[y].name = inputFields[y].name.substr(4);
            if (inputFields[y].type != 'hidden') {
              if (inputFields[y].value != '1') {
                inputFields[y].value = document.new_product.sets_select.options[document.new_product.sets_select.options.selectedIndex].text;
                
                var str = new String(inputFields[y].value);
                while (str.charCodeAt(0) == 160){
                  str = str.slice(1, str.length);
                }
                inputFields[y].value = str;
              }
            } else {
              inputFields[y].value = val;
            }
            inputFields[y].disabled = false;
          }
        }

        var insertHere = document.getElementById('writesets');
        insertHere.parentNode.insertBefore(newFields,insertHere);  
      } 
    }
  }

  function toggleSetsStatus(setsID) {
    var row = document.getElementById(setsID);
    var rowButton = document.getElementById(setsID + '-button');
    var inputFields = row.getElementsByTagName('input');

    if (rowButton.value == '-') {
      for (rF=0; rF<inputFields.length; rF++) {
        if (inputFields[rF].type != 'button') {
          inputFields[rF].disabled = true;
        }
      }

      row.className = 'attributeRemove';
      rowButton.value = '+';
    } else {
      for (rF=0; rF<inputFields.length; rF++) {
        if (inputFields[rF].type != 'button') {
          inputFields[rF].disabled = false;
        }
      }

      row.className = '';
      rowButton.value = '-';
    }
  }
  
  function remove(child) {
    child.parentNode.parentNode.parentNode.parentNode.removeChild(child.parentNode.parentNode.parentNode);
  }
// -->
</script>
<script language="JavaScript">
<!--
function DoFSCommand(bundle_search_query) {
  if (bundle_search_query.length > 0) {
    get_bundle_search_result(bundle_search_query);
  }
}
if (navigator.appName.indexOf("Microsoft") != -1) { // Hook for Internet Explorer.
  document.write('<script language=\"VBScript\"\>\n');
  document.write('On Error Resume Next\n');
  document.write('Sub FSCommand(ByVal command, ByVal args)\n');
  document.write('        Call DoFSCommand(command, args)\n');
  document.write('End Sub\n');
  document.write('</script\>\n');
}

function get_bundle_search_result(query) {
  document.getElementById('bundle_search_results').innerHTML = 'Searching ...<select name="sets_select" size="20" style="width:100%;display:none"></select>';
  var bundle_search_result = new Subsys_JsHttpRequest_Js();
  bundle_search_result.onreadystatechange = function() {
    if (bundle_search_result.readyState == 4) {
      if (bundle_search_result.responseJS) {
        if (bundle_search_result.responseJS.tf) {
          document.getElementById('bundle_search_results').innerHTML = bundle_search_result.responseJS.tf;
        }
      }
    }
  }
  bundle_search_result.caching = false;
  bundle_search_result.open('POST', 'bundle_search_results.php', true);
  bundle_search_result.send({ q: query, prid: <?php echo ($HTTP_GET_VARS['pID'] ? $HTTP_GET_VARS['pID'] : '0'); ?> });
}

function on_enter(e, t)
{
  if (e.keyCode == 13)
  {
    DoFSCommand(t.form.bundle_search_query.value); 
    return false;
  }
  return true;
}
//-->
</script>
     <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td width="10%" class="smallText"><?php echo tep_draw_input_field('bundle_search_query', '', 'onkeydown="return on_enter(event, this)"'); ?></td>
            <td width="90%" class="smallText"><input type="button" value="Search" onClick="DoFSCommand(this.form.bundle_search_query.value);" class="infoBoxButton"></td>
          </tr>
          <tr>
            <td id="bundle_search_results" class="main" valign="top" colspan="2"><i>Enter search terms above to find products.</i><select name="sets_select" size="16" style="width:100%;display:none"><?php
/*
  $categories_array = tep_get_full_category_tree();
  for ($i=0,$n=sizeof($categories_array);$i<$n;$i++) {
    if ($categories_array[$i]['category'] == 1) {
      echo '<option id="' .$categories_array[$i]['id']. '" value="cat_' .$categories_array[$i]['id']. '" style="COLOR:#0046D5" disabled>' . $categories_array[$i]['text'] . '</option>';
    } else {
      echo '<option id="' .$categories_array[$i]['id']. '" value="prod_' .$categories_array[$i]['id']. '" style="COLOR:#555555">' . $categories_array[$i]['text'] . '</option>';
    }
  }
*/
?></select></td>
          </tr>
        </table></td>
        <td align="center" width="10%" class="smallText">
          <input type="button" value=">>" onClick="moreSet()" class="infoBoxButton">
        </td>
        <td width="60%" valign="top" class="smallText">
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_PRODUCTS; ?></legend>
           
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" colspan="3">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS))
{
  $query = tep_db_query("select sp.sort_order, sp.product_id, sp.num_product, pd.products_name from  "  . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SETS_PRODUCTS . " sp where sp.product_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and sp.sets_id = '" . (int)$pInfo->products_id . "' and pd.affiliate_id = 0 order by sp.sort_order");
  while ($data = tep_db_fetch_array($query))
  {
    echo '      <tr id="sets-' . $data['product_id'] . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('sets_product_name[]', $data['products_name'], 'size="32" readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_NUMBER . '</nobr>&nbsp;' . tep_draw_input_field('sets_sets_number[]', $data['num_product'], 'size="3"') . tep_draw_hidden_field('sets_product_id[]', $data['product_id'], '') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('sets_sort_order[]', $data['sort_order'], 'size="2"') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="sets-' . $data['product_id']  . '-button" onClick="toggleSetsStatus(\'sets-' . $data['product_id'] . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
else
{
  if ( isset($HTTP_POST_VARS['sets_product_id']) && is_array($HTTP_POST_VARS['sets_product_id'])) foreach($HTTP_POST_VARS['sets_product_id'] as $key => $value)
  {
    echo '      <tr  id="sets-' . $value . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('sets_product_name[]', $HTTP_POST_VARS['sets_product_name'][$key], 'size="32" readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_NUMBER . '</nobr>&nbsp;' . tep_draw_input_field('sets_sets_number[]', $HTTP_POST_VARS['sets_sets_number'][$key], 'size="3"') . tep_draw_hidden_field('sets_product_id[]', $HTTP_POST_VARS['sets_product_id'][$key], '') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('sets_sort_order[]', $HTTP_POST_VARS['sets_sort_order'][$key], 'size="2"') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="sets-' . $value  . '-button" onClick="toggleSetsStatus(\'sets-' . $value . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
?>      </table>
            <span id="writesets"></span>
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"></td>
                  <td class="smallText">&nbsp;</td>                
                  <td class="smallText"><?php echo '<nobr>' . TEXT_PRODUCTS_SETS_DISCOUNT . '</nobr>&nbsp;' . tep_draw_input_field('products_sets_discount', $pInfo->products_sets_discount, 'size="10"') ; ?></td>
                  <td class="smallText" align="right"></td>
                </tr>                
              </table>
            <div id="readsets" style="display:none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><?php echo tep_draw_input_field('new_sets_product_name[]', '', 'size="32" disabled readonly'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_NUMBER . '</nobr>&nbsp;' . tep_draw_input_field('new_sets_sets_number[]', '1', 'disabled size="3"') . tep_draw_hidden_field('new_sets_product_id[]', '', 'disabled'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('new_sets_sort_order[]', '1', 'disabled size="2"') ; ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="remove(this)";" class="infoBoxButton"></td>
                </tr>                
              </table>              
            </div>
            
          </fieldset>                  
          </td>
        </tr>        
     </table>
    
    </div>
<?php
} // end if (PRODUCTS_BUNDLE_SETS == 'True')
?>

<?php
$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
if (tep_db_num_rows($options_query)){
?>
      <div class="tab-page" id="tabAttributes">
    <h2 class="tab"><?php echo TAB_ATTRIBUTES; ?></h2>

<script type="text/javascript">mainTabPane.addTabPage( document.getElementById( "tabAttributes" ) );</script>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><select name="attributes" size="20" style="width: 100%;">
<?php
$options_query = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
while ($options = tep_db_fetch_array($options_query)) {
  echo '          <optgroup label="' . htmlspecialchars($options['products_options_name']) . '" id="' . $options['products_options_id'] . '">' . "\n";
  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $options['products_options_id'] . "' and pov.language_id = '" . $languages_id . "'");
  while ($values = tep_db_fetch_array($values_query)) {
    echo '            <option value="' . $values['products_options_values_id'] . '">' . htmlspecialchars($values['products_options_values_name']) . '</option>' . "\n";
  }
  echo '</optgroup>';
}
?>
          </select>
        </td>
        <td align="center" width="10%" class="smallText" valign="top">
          <input type="button" value=">>" onClick="moreFields()" class="infoBoxButton">
        </td>
        <td width="60%" valign="top" class="smallText">
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_ATTRIBUTES; ?></legend>
             <table border="0" width="100%" cellspacing="0" cellpadding="2" height="100%">
              <tr>
                <td class="main" colspan="10">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS)) {
  $query = tep_db_query("select pa.products_attributes_id, po.products_options_id, po.products_options_name, pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_discount_price, pa.products_options_sort_order, pa.product_attributes_one_time, pa.products_attributes_weight, pa.products_attributes_weight_prefix, pa.products_attributes_units, pa.products_attributes_units_price, pa.products_attributes_filename, pa.products_attributes_maxdays, pa.products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $pInfo->products_id . "' and pa.options_id = po.products_options_id and po.language_id = '" . $languages_id . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "' order by po.products_options_name, pov.products_options_values_name");
  $current_attribute_group = '';
  while ($data = tep_db_fetch_array($query)){
    if ($data['products_options_name'] != $current_attribute_group) {
      $current_attribute_group = $data['products_options_name'];
    }else{
      echo tep_draw_hidden_field('products_options_name[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_options_name']);
    }
    echo '              <tr id="attribute-' . $data['products_options_id'] . '_' . $data['products_options_values_id'] . '">' . "\n" .
    '                <td><table border="0" cellpadding="2" cellspacing="0" width="100%">';
    echo '              <tr>' . "\n" .
    '                <td class="smallText" ><b>' . htmlspecialchars($data['products_options_name']) . tep_draw_hidden_field('products_options_name[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_options_name']) . '</b></td>' . "\n" .
    '                <td class="smallText" align="center" colspan="2">' . TEXT_PRICE . '</td>' . "\n" .
    (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?'<td class="smallText" align="center">' . TEXT_DISCOUNT_PRICE . '</td>' ."\n":'');
      ?>
                <td class="smallText"  align="center"><?php echo 'Sort Order'; ?></td>
                <td class="smallText"  align="center"><?php echo 'Weight'; ?></td>
           <?php                           
           echo '                <td class="smallText" ><b>&nbsp;&nbsp;</b></td>' . "\n" .
           '              </tr>' . "\n";
           echo '    <tr><td class="smallText" width="50%">' . htmlspecialchars($data['products_options_values_name']) . tep_draw_hidden_field('products_options_values_name[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', addslashes($data['products_options_values_name'])) . '</td>' . "\n" .
           '                <td class="smallText">' . tep_draw_pull_down_menu('price_prefix[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', array(array('id' => '+', 'text' => '+'), array('id' => '-', 'text' => '-')), $data['price_prefix']) . '</td>';

           if (USE_MARKET_PRICES == 'True'){
             echo '<td class="smallText">';
             echo '<div class="tab-pane" id="pricesTabPane_' . $data['products_options_id'] . '_' . $data['products_options_values_id'] . '">';
            ?>
            <script type="text/javascript"><!--
            var pricesTabPane<?php echo '_' . $data['products_options_id'] . '_' . $data['products_options_values_id'];?> = new WebFXTabPane( document.getElementById( "pricesTabPane<?php echo '_' . $data['products_options_id'] . '_' . $data['products_options_values_id'];?>"), false,  mainTabPane );
            //-->
            </script>

             <?php
             foreach ($currencies->currencies as $key => $value)
             {
              ?>
                  <div class="tab-page" id="tabCurrency_<?php echo $currencies->currencies[$key]['id'] . '_' . $data['products_options_id'] . '_' . $data['products_options_values_id']; ?>">
                  <h2 class="tab" id="H2_tabCurrency_<?php echo $currencies->currencies[$key]['id'] . '_' . $data['products_options_id'] . '_' . $data['products_options_values_id']; ?>"><?php echo $currencies->currencies[$key]['title']; ?></h2>
                  <script type="text/javascript"><!--
                  pricesTabPane<?php echo '_' . $data['products_options_id'] . '_' . $data['products_options_values_id'];?>.addTabPage( document.getElementById( "tabCurrency_<?php echo $currencies->currencies[$key]['id'] . '_' . $data['products_options_id'] . '_' . $data['products_options_values_id']; ?>" ) );
                  //-->
                  </script>             

                  <table border="0" cellpadding="2" cellspacing="0">
                    <tr>
                      <td class="main">&nbsp;</td>
                      <td class="main"><?php echo TEXT_PRICE;?></td>
                      <?php
                      if (DISCOUNT_TABLE_ENABLE == 'True'){
                      ?>              
                      <td class="main"><?php echo TEXT_DISCOUNT_PRICE;?></td>  
                      <?php
                      }
                      ?>
                    </tr>
                    <tr>
                      <td class="main">&nbsp;</td>
                      <td class="main"><?php echo tep_draw_input_field('products_attributes_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][0][' . $currencies->currencies[$key]['id'] . ']', tep_get_attributes_price($data['products_attributes_id'], $currencies->currencies[$key]['id'], 0), 'size="7"'); ?></td>
                      <?php
                      if (DISCOUNT_TABLE_ENABLE == 'True'){
                      ?>                            
                      <td class="main"><?php echo tep_draw_input_field('products_attributes_discount_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][0][' . $currencies->currencies[$key]['id'] . ']', tep_get_attributes_discount_price($data['products_attributes_id'], $currencies->currencies[$key]['id'], 0), 'size="7"'); ?></td>
                      <?php
                      }
                      ?>
                    </tr>
                    
                  <?php
                  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
                  $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                  while ($data_array = tep_db_fetch_array($data_query))
                  {
                    echo "<tr>";
                    echo '<td class="main">' . $data_array['groups_name'] . "</td>";
                    echo '<td class="main">' . tep_draw_input_field('products_attributes_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][' . $data_array['groups_id'] . '][' . $currencies->currencies[$key]['id'] . ']', tep_get_attributes_price($data['products_attributes_id'], $currencies->currencies[$key]['id'], $data_array['groups_id'], '-2'), 'size="7"') . '</td>';
                    if (DISCOUNT_TABLE_ENABLE == 'True'){
                      ?>
                        <td class="smallText" align="center"><?php echo tep_draw_input_field('products_attributes_discount_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][' . $data_array['groups_id'] . '][' . $currencies->currencies[$key]['id'] . ']', tep_get_attributes_discount_price($data['products_attributes_id'], $currencies->currencies[$key]['id'], $data_array['groups_id']), 'size="7"'); ?></td>
                      <?php
                    }
                    echo '</tr>';
                  }
                  }
                    ?>
                  </table>
                  </div>
                  
                <?php
               
             }
             echo '</div>';
             echo '</td>';
           }else{
             echo '<td class="smalltext">' . tep_draw_input_field('products_attributes_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][0]', tep_get_attributes_price($data['products_attributes_id'], 0, 0), 'size="7"') . "\n";
             if (CUSTOMERS_GROUPS_ENABLE == 'True'){
             $data_query_groups = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
             while ($data_groups = tep_db_fetch_array($data_query_groups))
             {
               echo "<br>" . $data_groups['groups_name'] . "<br>";
               echo tep_draw_input_field('products_attributes_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][' . $data_groups['groups_id'] . ']', tep_get_attributes_price($data["products_attributes_id"], 0, $data_groups['groups_id'], '-2'), 'size="7"');
             }
             }
             echo '</td>' . "\n";
             if (DISCOUNT_TABLE_ENABLE == 'True'){
              ?>                
              <td class="smallText" align="center"><?php echo tep_draw_input_field('products_attributes_discount_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][0]', tep_get_attributes_discount_price($data["products_attributes_id"], 0, 0), 'size="7"'); ?>
              <?php
              if (CUSTOMERS_GROUPS_ENABLE == 'True'){
              $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
              while ($data_array = tep_db_fetch_array($data_query))
              {
                echo "<br>" . $data_array['groups_name'] . "<br>";
                echo tep_draw_input_field('products_attributes_discount_price[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . '][' . $data_array['groups_id'] . ']', tep_get_attributes_discount_price($data["products_attributes_id"], 0, $data_array['groups_id']), 'size="7"');
              }
              }
              ?>
              </td>
              <?php
             }

           }
           ?>
                  <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_options_sort_order'], 'size="7"'); ?></td>
                <td class="dataTableContent" align="center" nowrap><?php echo tep_draw_pull_down_menu('products_attributes_weight_prefix[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', array(array('id' => '+', 'text' => '+'), array('id' => '-', 'text' => '-')), $data['products_attributes_weight_prefix']); ?>&nbsp;<?php echo tep_draw_input_field('products_attributes_weight[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_attributes_weight'], 'size="7"'); ?></td>
<?php
echo      '                <td class="smallText" align="right"><input type="button" value="-" id="attribute-' . $data['products_options_id'] . '_' . $data['products_options_values_id'] . '-button" onClick="toggleAttributeStatus(this, \'attribute-' . $data['products_options_id'] . '_' . $data['products_options_values_id'] . '\');" class="infoBoxButton"></td>' . "\n" .
'             </tr>' . "\n";
if (DOWNLOAD_ENABLED == 'true'){
?>
        <tr>  
          <td class="dataTableContent" colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?7:6); ?>" ><?php echo TEXT_DOWNLOADABLE_PRODUCTS;?></td>
        </tr>
        <tr>
          <td colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?4:3); ?>" class="dataTableContent"><?php echo TEXT_FILENAME;?></td>
          <td class="dataTableContent"><?php echo TEXT_EXPIRY_DAYS;?></td>
          <td class="dataTableContent" colspan="2"><?php echo TEXT_MAX_DOWNOAD_COUNT;?></td>
        </tr>
        <tr>
          <td colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?4:3); ?>" class="dataTableContent"><?php echo tep_draw_file_field('products_attributes_filename_' . $data['products_options_id'] . '_' . $data['products_options_values_id']) . tep_draw_hidden_field('products_attributes_filename_name[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_attributes_filename']) . '<br>' . $data['products_attributes_filename'];?></td>
          <td class="dataTableContent"><?php echo tep_draw_input_field('products_attributes_maxdays[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_attributes_maxdays'], 'size="7" ');?></td>
          <td class="dataTableContent" colspan="2"><?php echo tep_draw_input_field('products_attributes_maxcount[' . $data['products_options_id'] . '][' . $data['products_options_values_id'] . ']', $data['products_attributes_maxcount'], 'size="7" ');?></td>
        </tr>

<?php
}
echo      '</table></td></tr>' . "\n";
  }
}else{
  if (sizeof($HTTP_POST_VARS['price_prefix'])){
    foreach ($HTTP_POST_VARS['price_prefix'] as $groups => $attributes) {
      foreach ($HTTP_POST_VARS['price_prefix'][$groups] as $key => $value) {

        echo '              <tr><td><table border="0" cellpadding="2" cellspacing="0" width="100%"><tr>' . "\n" .
        '                <td class="smallText" ><b>' . $HTTP_POST_VARS['products_options_name'][$groups][$key] . tep_draw_hidden_field('products_options_name[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_options_name'][$groups][$key]) . '</b></td>' . "\n" .
        '                <td class="smallText" align="center" colspan="2">' . TEXT_PRICE . '</td>' . "\n" .
        (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?'<td class="smallText" align="center">' . TEXT_DISCOUNT_PRICE . '</td>' ."\n":'');
      ?>
                <td class="smallText"  align="center"><?php echo 'Sort Order'; ?></td>
                <td class="smallText"  align="center"><?php echo 'Weight'; ?></td>
           <?php                           
           echo '                <td class="smallText" ><b>&nbsp;&nbsp;</b></td>' . "\n" .
           '              </tr>' . "\n";
           echo '              <tr id="attribute-' . $groups . '_' . $key . '">' . "\n" .
           '                <td class="smallText" width="50%">' . $HTTP_POST_VARS['products_options_values_name'][$groups][$key] . tep_draw_hidden_field('products_options_values_name[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_options_values_name'][$groups][$key]) . '</td>' . "\n" .
           '                <td class="smallText">' . tep_draw_pull_down_menu('price_prefix[' . $groups . '][' . $key . ']', array(array('id' => '+', 'text' => '+'), array('id' => '-', 'text' => '-')), $HTTP_POST_VARS['price_prefix'][$groups][$key]) . '</td>';


           if (USE_MARKET_PRICES == 'True'){
             echo '<td class="smallText">';
             echo '<div class="tab-pane" id="pricesTabPane_' . $groups . '_' . $key . '">';
             ?>
            <script type="text/javascript"><!--
            var pricesTabPane<?php echo '_' . $groups . '_' . $key;?> = new WebFXTabPane( document.getElementById( "pricesTabPane<?php echo '_' . $groups . '_' . $key;?>" ), false,  mainTabPane );
            //-->
            </script>             
             <?PHP             
             foreach ($currencies->currencies as $cur => $value)
             {
                ?>
                  <div class="tab-page" id="tabCurrency_<?php echo $currencies->currencies[$cur]['id'] . '_' . $groups . '_' . $key; ?>">
                  <h2 class="tab"><?php echo $currencies->currencies[$cur]['title']; ?></h2>
                  <script type="text/javascript"><!--
                  pricesTabPane<?php echo '_' . $groups . '_' . $key;?>.addTabPage( document.getElementById( "tabCurrency_<?php echo $currencies->currencies[$cur]['id'] . '_' . $groups . '_' . $key; ?>" ) );
                  //-->
                  </script>
                  <table border="0" cellpadding="2" cellspacing="0">
                    <tr>
                      <td class="main">&nbsp;</td>
                      <td class="main"><?php echo TEXT_PRICE;?></td>
                      <?php
                      if (DISCOUNT_TABLE_ENABLE == 'True'){
                      ?>              
                      <td class="main"><?php echo TEXT_DISCOUNT_PRICE;?></td>  
                      <?php
                      }
                      ?>
                    </tr>
                    <tr>
                      <td class="main">&nbsp;</td>
                      <td class="main"><?php echo tep_draw_input_field('products_attributes_price[' . $groups . '][' . $key . '][0][' . $currencies->currencies[$cur]['id'] . ']', $_POST['products_attributes_price'][$groups][$key][0][$currencies->currencies[$cur]['id']], 'size="7"'); ?></td>
                      <?php
                      if (DISCOUNT_TABLE_ENABLE == 'True'){
                      ?>                            
                      <td class="main"><?php echo tep_draw_input_field('products_attributes_discount_price[' . $groups . '][' . $key . '][0][' . $currencies->currencies[$cur]['id'] . ']', $_POST['products_attributes_discount_price'][$groups][$key][0][$currencies->currencies[$cur]['id']], 'size="7"'); ?></td>
                      <?php
                      }
                      ?>
                    </tr>
                  <?php
                  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
                  $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                  while ($data_array = tep_db_fetch_array($data_query))
                  {
                    echo "<tr>";
                    echo '<td class="main">' . $data_array['groups_name'] . "</td>";
                    echo '<td class="main">' . tep_draw_input_field('products_attributes_price[' . $groups . '][' . $key . '][' . $data_array['groups_id'] . '][' . $currencies->currencies[$cur]['id'] . ']', $_POST['products_attributes_price'][$groups][$key][$data_array['groups_id']][$currencies->currencies[$cur]['id']], 'size="7"') . '</td>';
                    if (DISCOUNT_TABLE_ENABLE == 'True'){
                      ?>                
                        <td class="smallText" align="center"><?php echo tep_draw_input_field('products_attributes_discount_price[' . $groups . '][' . $key . '][' . $data_array['groups_id'] . '][' . $currencies->currencies[$cur]['id'] . ']', $_POST['products_attributes_discount_price'][$groups][$key][$data_array['groups_id']][$currencies->currencies[$cur]['id']], 'size="7"'); ?></td>
                      <?php
                    }
                    echo '</tr>';
                  }
                  }
                    ?>
                  </TABLE>
                  </div>
                  
                <?php
             }
             echo '</div>';
             echo '</td>';
           }else{
             echo '<td class="smalltext">' . tep_draw_input_field('products_attributes_price[' . $groups . '][' . $key . '][0]', $HTTP_POST_VARS['products_attributes_price'][$groups][$key][0], 'size="7"') . "\n";

             if (CUSTOMERS_GROUPS_ENABLE == 'True'){
             $data_query_groups = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
             while ($data_groups = tep_db_fetch_array($data_query_groups))
             {
               echo "<br>" . $data_groups['groups_name'] . "<br>";
               echo tep_draw_input_field('products_attributes_price[' . $groups . '][' . $key . '][' . $data_groups['groups_id'] . ']', $HTTP_POST_VARS['products_attributes_price'][$groups][$key][$data_groups['groups_id']], 'size="7"');
             }
             }
             echo '</td>' . "\n";
           if (DISCOUNT_TABLE_ENABLE == 'True'){
            ?>                
            <td class="smallText" align="center"><?php echo tep_draw_input_field('products_attributes_discount_price[' . $groups . '][' . $key . '][0]', $HTTP_POST_VARS['products_attributes_discount_price'][$groups][$key][0], 'size="7"'); ?>
        
            <?php
            if (CUSTOMERS_GROUPS_ENABLE == 'True'){
            $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
            while ($data_array = tep_db_fetch_array($data_query))
            {
              echo "<br>" . $data_array['groups_name'] . "<br>";
              echo tep_draw_input_field('products_attributes_discount_price[' . $groups . '][' . $key . '][' . $data_array['groups_id'] . ']', $HTTP_POST_VARS['products_attributes_price'][$groups][$key][$data_groups['groups_id']], 'size="7"');
            }
            }
            ?>
            </td>
            <?php
           }

           }
    ?>
                  <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('products_options_sort_order[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_options_sort_order'][$groups][$key], 'size="7"'); ?></td>
                <td class="dataTableContent" align="center" nowrap><?php echo tep_draw_pull_down_menu('products_attributes_weight_prefix[' . $groups . '][' . $key . ']', array(array('id' => '+', 'text' => '+'), array('id' => '-', 'text' => '-')), $HTTP_POST_VARS['products_attributes_weight_prefix'][$groups][$key]); ?>&nbsp;<?php echo tep_draw_input_field('products_attributes_weight[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_attributes_weight'][$groups][$key], 'size="7"'); ?></td>
<?php
echo      '                <td class="smallText" align="right"><input type="button" value="-" id="attribute-' . $groups . '_' . $key . '-button" onClick="toggleAttributeStatus(this, \'attribute-' . $groups . '_' . $key . '\');" class="infoBoxButton"></td>' . "\n" .
'              </tr>' . "\n";
if (DOWNLOAD_ENABLED == 'true'){
?>
        <tr>
          <td class="dataTableContent" colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?7:6); ?>"><?php echo TEXT_DOWNLOADABLE_PRODUCTS;?></td>
        </tr>
        <tr>
          <td colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?4:3); ?>" class="dataTableContent"><?php echo TEXT_FILENAME;?></td>
          <td class="dataTableContent" ><?php echo TEXT_EXPIRY_DAYS;?></td>
          <td class="dataTableContent" colspan="2"><?php echo TEXT_MAX_DOWNOAD_COUNT;?></td>
        </tr>
        <tr>
          <td colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?4:3); ?>" class="dataTableContent"><?php echo tep_draw_file_field('products_attributes_filename_' . $groups . '_' . $key) . tep_draw_hidden_field('products_attributes_filename_name[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_attributes_filename_name'][$groups][$key]) . '<br>' . $HTTP_POST_VARS['products_attributes_filename_name'][$groups][$key];?></td>
          <td class="dataTableContent"><?php echo tep_draw_input_field('products_attributes_maxdays[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_attributes_maxdays'][$groups][$key], 'size="7"');?></td>
          <td class="dataTableContent" colspan="2"><?php echo tep_draw_input_field('products_attributes_maxcount[' . $groups . '][' . $key . ']', $HTTP_POST_VARS['products_attributes_maxcount'][$groups][$key], 'size="7" ');?></td>
        </tr>

<?php
}
echo  '</table></td></tr>' . "\n";
      }
    }

  }
}
?>
              <tr>
                <td class="main" colspan="10">&nbsp;</td>
              </tr>
             </table>
          

<span id="writeroot"></span>   

            <div id="readroot" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2" id="new_table">
                <tr>
                  <td class="smallText"><b><span id="attribteGroupName">&nbsp;</span></b><input type=hidden name="new_products_options_name"></td>
                  <?php echo '<td class="smallText" align="center" colspan="2">' . TEXT_PRICE . '</td>';?>
                  <?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?'<td class="smallText" align="center">' . TEXT_DISCOUNT_PRICE . '</td>' ."\n":''); ?>
                
                <td class="smallText"  align="center"><?php echo 'Sort Order'; ?></td>
                <td class="smallText"  align="center" colspan="2"><?php echo 'Weight'; ?></td>
            
                </tr>
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><span id="attributeKey">&nbsp;</span><input type=hidden name="new_products_options_values_name"></td>
                  <td class="smallText"><?php echo tep_draw_pull_down_menu('new_price_prefix', array(array('id' => '+', 'text' => '+'), array('id' => '-', 'text' => '-')), '+', 'disabled') . '</td>';


                  if (USE_MARKET_PRICES == 'True'){
                  ?>
                
                  <td class="smallText">
                    <div class="tab-pane" id="new_pricesTabPane">
                      <?php
                      foreach ($currencies->currencies as $key => $value)
                      {
                      ?>
                              <div id="new_tabCurrency_<?php echo $currencies->currencies[$key]['id']; ?>">
                              <h2 class="tab"><?php echo $currencies->currencies[$key]['title']; ?></h2>
                              <table border="0" cellpadding="2" cellspacing="0">
                                <tr>
                                  <td class="main">&nbsp;</td>
                                  <td class="main"><?php echo TEXT_PRICE;?></td>
                                  <?php
                                  if (DISCOUNT_TABLE_ENABLE == 'True'){
                                  ?>
                                  <td class="main"><?php echo TEXT_DISCOUNT_PRICE;?></td>  
                                  <?php
                                  }
                                  ?>
                                </tr>
                                <tr>
                                  <td class="main">&nbsp;</td>
                                  <td class="main"><?php echo tep_draw_input_field('new_products_attributes_price[0][' . $currencies->currencies[$key]['id'] . ']', '', 'size="7" disabled'); ?></td>
                                  <?php
                                  if (DISCOUNT_TABLE_ENABLE == 'True'){
                                  ?>                            
                                  <td class="main"><?php echo tep_draw_input_field('new_products_attributes_discount_price[0][' . $currencies->currencies[$key]['id'] . ']', '', 'size="7" disabled'); ?></td>
                                  <?php
                                  }
                                  ?>
                                </tr>
                                
                              <?php
                              if (CUSTOMERS_GROUPS_ENABLE == 'True'){
                              $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                              while ($data_array = tep_db_fetch_array($data_query))
                              {
                                echo "<tr>";
                                echo '<td class="main">' . $data_array['groups_name'] . "</td>";
                                echo '<td class="main">' . tep_draw_input_field('new_products_attributes_price[' . $data_array['groups_id'] . '][' . $currencies->currencies[$key]['id'] . ']', '-2', 'size="7"') . '</td>';
                                if (DISCOUNT_TABLE_ENABLE == 'True'){
                                  ?>
                                    <td class="smallText" align="center"><?php echo tep_draw_input_field('new_products_attributes_discount_price[' . $data_array['groups_id'] . '][' . $currencies->currencies[$key]['id'] . ']', '', 'size="7" disabled'); ?></td>
                                  <?php
                                }
                                echo '</tr>';
                              }
                              }
                                ?>
                              </table>
                              </div>
                        <?php
                      }
                      echo '</div>';
                      echo '</td>';
                  }else{
                    echo '<td class="smalltext">' . tep_draw_input_field('new_products_attributes_price[0]', '', 'disabled size="7"') . "\n";

                    if (CUSTOMERS_GROUPS_ENABLE == 'True'){
                    $data_query_groups = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                    while ($data_groups = tep_db_fetch_array($data_query_groups))
                    {
                      echo "<br>" . $data_groups['groups_name'] . "<br>";
                      echo tep_draw_input_field('new_products_attributes_price[' . $data_groups['groups_id'] . ']', '-2', 'size="7"');
                    }
                    }
                    echo '</td>' . "\n";
                    if (DISCOUNT_TABLE_ENABLE == 'True'){
                  ?>                
                  <td class="smallText" align="center"><?php echo tep_draw_input_field('new_products_attributes_discount_price[0]', '', 'size="7" disabled'); ?>
                  <?php
                  if (CUSTOMERS_GROUPS_ENABLE == 'True'){
                  $data_query = tep_db_query("select * from " . TABLE_GROUPS . " order by groups_id");
                  while ($data_array = tep_db_fetch_array($data_query))
                  {
                    echo "<br>" . $data_array['groups_name'] . "<br>";
                    echo tep_draw_input_field('new_products_attributes_discount_price[' . $data_array['groups_id'] . ']', '', 'size="7" disabled');
                  }
                  }
                  ?>
                  </td>
                  <?php
                    }

                  }
                ?>

                  <td class="dataTableContent" width="70" align="center"><?php echo tep_draw_input_field('new_products_options_sort_order', '', 'size="7" disabled'); ?></td>
                <td class="dataTableContent" align="center" nowrap><?php echo tep_draw_pull_down_menu('new_products_attributes_weight_prefix', array(array('id' => '+', 'text' => '+'), array('id' => '-', 'text' => '-')), '+', 'disabled'); ?>&nbsp;<?php echo tep_draw_input_field('new_products_attributes_weight', '', 'size="7" disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="toggleAttributeStatus(this, this.parentNode.parentNode.parentNode.parentNode.id);" class="infoBoxButton"></td>
                </tr>
<?php 
if (DOWNLOAD_ENABLED == 'true'){
?>
                <tr class="attributeAdd">
                  <td class="dataTableContent" colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?7:6); ?>"><?php echo TEXT_DOWNLOADABLE_PRODUCTS;?></td>
                </tr>
                <tr class="attributeAdd">
                  <td colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?4:3); ?>" class="dataTableContent"><?php echo TEXT_FILENAME;?></td>
                  <td class="dataTableContent"><?php echo TEXT_EXPIRY_DAYS;?></td>
                  <td class="dataTableContent" colspan="2"><?php echo TEXT_MAX_DOWNOAD_COUNT;?></td>
                </tr>
                <tr class="attributeAdd">
                  <td colspan="<?php echo (DISCOUNT_TABLE_ENABLE == 'True' && USE_MARKET_PRICES != 'True'?4:3); ?>" class="dataTableContent"><?php echo tep_draw_file_field('new_products_attributes_filename');?></td>
                  <td class="dataTableContent"><?php echo tep_draw_input_field('new_products_attributes_maxdays', '', 'size="7" disabled');?></td>
                  <td class="dataTableContent" colspan="2"><?php echo tep_draw_input_field('new_products_attributes_maxcount', '', 'size="7" disabled');?></td>
                </tr>
<?php
}
?>
              </table>
              
            </div> 
           </fieldset>
        </td>
      </tr>
    </table>
    </div>
<?php
}
?>

<?php
// [[ Properties
if (PRODUCTS_PROPERTIES == 'True'){
?>
   <div class="tab-page" id="tabProperties">
    <h2 class="tab"><?php echo TAB_PROPERTIES; ?></h2>

    <script type="text/javascript"><!--

    mainTabPane.addTabPage( document.getElementById( "tabProperties" ) );
    //-->
    </script>

    <div class="tab-pane" id="propertiesTabPane">
      <script type="text/javascript"><!--
      var propertiesTabPane = new WebFXTabPane( document.getElementById( "propertiesTabPane" ) );
      //-->
      </script>

<?php
$properties_yes_no_array = array(array('id' => '', 'text' => OPTION_NONE), array('id' => 'true', 'text' => OPTION_TRUE), array('id' => 'false', 'text' => OPTION_FALSE));
$properties_query = tep_db_query("select pc.categories_id, pcd.categories_name, pr.properties_id, pr.properties_type, prd.properties_name, pr.additional_info, prd.properties_description, prd.possible_values from " . TABLE_PROPERTIES . " pr left join " . TABLE_PROPERTIES_TO_PROPERTIES_CATEGORIES . " p2pc on p2pc.properties_id = pr.properties_id left join " . TABLE_PROPERTIES_CATEGORIES . " pc on p2pc.categories_id = pc.categories_id left join " . TABLE_PROPERTIES_CATEGORIES_DESCRIPTION . " pcd on pc.categories_id = pcd.categories_id and pcd.language_id = '" . (int)$languages_id . "', " . TABLE_PROPERTIES_DESCRIPTION . " prd  where pr.properties_id = prd.properties_id and prd.language_id = '" . (int)$languages_id . "' order by pc.sort_order, pcd.categories_name, pr.sort_order, prd.properties_name");
$script_init_string = '';
if (tep_db_num_rows($properties_query) > 0)
{

  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $$i = '<table border="0" width="100%" cellspacing="0" cellpadding="2">';
  }
?>
<?php
while ($properties_array = tep_db_fetch_array($properties_query))
{
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $$i .= '<tr>
            <td colspan="2">'.tep_draw_separator('pixel_trans.gif', '1', '10') .'</td>
          </tr>
          <tr>
            <td colspan="2" class="smallText"><b>' . $properties_array['categories_name'] . '</b></td>
          </tr>
          <tr>
            <td colspan="2">' . tep_draw_separator('pixel_trans.gif', '1', '5') . '</td>
          </tr>
          <tr>
            <td width="160" class="smallText" valign="top" nowrap>' . $properties_array['properties_name'] . ':</td>
            <td class="main">';
  }

?>
<?php
switch ($properties_array['properties_type'])
{
  case '2'://yes/no choice
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".(int)$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $$i .=  tep_draw_pull_down_menu('set_value[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', $properties_yes_no_array, is_array($HTTP_POST_VARS) && count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['set_value'][$properties_array['properties_id']] : $data['set_value']);
    if ($properties_array['additional_info'] == '1'){
      $$i .= '<br>' . TEXT_ADDITIONAL_INFO . ':';
      $$i .= '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']);
    }
  }
  break;
  case '1': //textarea
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $$i .= tep_draw_textarea_field('set_value[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 70, 15, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']] : $data['set_value']) .'<br> ' ;
    $script_init_string .= "editor_generate('set_value[" . $properties_array['properties_id'] . "]["
    . $languages[$i]['id']."]',config);" . "\n";
    if ($properties_array['additional_info'] == '1'){
      $$i .= TEXT_ADDITIONAL_INFO . ':&nbsp;' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']) . '<br>';
    }
  }
  break;
  case '3': //multiply choice
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".(int)$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $str = tep_get_properties_possible_values($properties_array['properties_id'], $languages[$i]['id']);
    if ($str != ''){
      $properties_values = explode("\n", $str);
      for ($j=0,$m=sizeof($properties_values);$j<$m;$j++){
        $$i .= tep_draw_checkbox_field('set_value[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']['.$j.']', $properties_values[$j], count($HTTP_POST_VARS)>0?$HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']][$j]:(strpos($data['set_value'], trim($properties_values[$j])) !== false)) . '&nbsp;' . $properties_values[$j] . '<br>';
      }
      if ($properties_array['additional_info'] == '1'){
        $$i .= TEXT_ADDITIONAL_INFO . ':&nbsp;' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']) . '<br>';
      }
    }
  }
  break;
  case '4'://limited choice
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".(int)$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $$i .= tep_draw_radio_field('set_value[' . $properties_array['properties_id'] . ']', '0') . '&nbsp;' . OPTION_NONE . '<br>';
    $properties_values = explode("\n", tep_get_properties_possible_values($properties_array['properties_id'], $languages[$i]['id']));
    for ($j=0,$m=sizeof($properties_values);$j<$m;$j++){
      $$i .= tep_draw_radio_field('set_value[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', $properties_values[$j], sizeof($HTTP_POST_VARS)>0?(trim($HTTP_POST_VARS['set_value'][$properties_array['properties_id']]) == trim($properties_values[$j])):(trim($properties_values[$j]) == trim($data['set_value']))) . '&nbsp;' . $properties_values[$j] . '<br>';
    }
    if ($properties_array['additional_info'] == '1'){
      $$i .= TEXT_ADDITIONAL_INFO . ':&nbsp;' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']) . '<br>';
    }
  }
  break;
  case '5'://file with description
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $$i .= tep_draw_hidden_field('set_value_previous['.$properties_array['properties_id'].']['.$languages[$i]['id'].']', $data['set_value']);
    $$i .= tep_draw_file_field('set_value_' . $properties_array['properties_id'] . '_'.$languages[$i]['id']) . '<br>' . (count($HTTP_POST_VARS)>0?$HTTP_POST_VARS['properties_data'][$properties_array['properties_id']][$languages[$i]['id']]:$data['set_value']) .'<br> ' . '<input type="checkbox" name="unlink['.$properties_array['properties_id'].']['.$languages[$i]['id'].']" value="yes">' . TEXT_UNLINK_PROPERTY . tep_draw_hidden_field('set_value_previous[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', $data['set_value']) . '<br>';
    if ($properties_array['additional_info'] == '1'){
      $$i .= TEXT_ADDITIONAL_INFO . ':&nbsp;' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']) . '<br>';
    }
  }
  break;
  case '6'://image with description
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $$i .= tep_draw_hidden_field('set_value_previous['.$properties_array['properties_id'].']['.$languages[$i]['id'].']', $data['set_value']);
    $$i .= tep_draw_file_field('set_value_' . $properties_array['properties_id'] . '_'.$languages[$i]['id']) . '<br>' . (count($HTTP_POST_VARS)>0?$HTTP_POST_VARS['properties_data'][$properties_array['properties_id']][$languages[$i]['id']]:$data['set_value']) .'<br> ' . '<input type="checkbox" name="unlink['.$properties_array['properties_id'].']['.$languages[$i]['id'].']" value="yes">' . TEXT_UNLINK_PROPERTY . tep_draw_hidden_field('set_value_previous[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', $data['set_value']) . '<br>' ;
    if ($properties_array['additional_info'] == '1'){
      $$i .= TEXT_ADDITIONAL_INFO . ':&nbsp;' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']) . '<br>';
    }
  }
  break;
  case '0': default://input field
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    $data_query = tep_db_query("select set_value, additional_info from " . TABLE_PROPERTIES_TO_PRODUCTS . " where products_id = '" . (int)$HTTP_GET_VARS['pID'] . "' and properties_id = '" . (int)$properties_array['properties_id'] . "' and language_id=".$languages[$i]['id']);
    $data = tep_db_fetch_array($data_query);
    $$i .= tep_draw_input_field('set_value[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['set_value'][$properties_array['properties_id']][$languages[$i]['id']] : $data['set_value']) .'<br> ' ;
    if ($properties_array['additional_info'] == '1'){
      $$i .= TEXT_ADDITIONAL_INFO . ':&nbsp;' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_textarea_field('additional_info[' . $properties_array['properties_id'] . ']['.$languages[$i]['id'].']', 'soft', 30, 10, count($HTTP_POST_VARS) > 0 ? $HTTP_POST_VARS['additional_info'][$properties_array['properties_id']][$languages[$i]['id']] : $data['additional_info']) . '<br>';
    }
  }
?>
<?php
}
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
  $$i .= '</td></tr>';
}

?>
<?php
}
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
  $$i .= '</table>';
}
?>

<?php
}

for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
      <div class="tab-page" id="tabPropertiesLanguages_<?php echo $languages[$i]['id']; ?>">
        <h2 class="tab"><?php echo tep_image('../includes/languages/' . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        propertiesTabPane.addTabPage( document.getElementById( "tabPropertiesLanguages_<?php echo $languages[$i]['id']; ?>" ) );
        //-->
        </script>
<?php
echo $$i;
echo '</div>';
}
?>

      </div>
    </div>
<?php
}
// ]] Properties
?>
<?php
if (SUPPLEMENT_STATUS == 'True'){
?>  
   <div class="tab-page" id="tabxSell">
    <h2 class="tab"><?php echo TEXT_XSELL; ?></h2>

    <script type="text/javascript"><!--

    mainTabPane.addTabPage( document.getElementById( "tabxSell" ) );
    //-->
    </script>
      
<script language="JavaScript">
var counter = 0;

function morexSell() {
  if (document.new_product.xsell_select.options.selectedIndex < 0) return;
  var existingFields = document.new_product.getElementsByTagName('input');
  var attributeExists = false;

  var val = document.new_product.xsell_select.options[document.new_product.xsell_select.options.selectedIndex].value;
  if (val.indexOf('prod_') != -1){
    val = val.replace('prod_', '');
    for (i=0; i<existingFields.length; i++) {
      if (existingFields[i].value == val && existingFields[i].name == 'xsell_product_id[]') {
        attributeExists = true;
        break;
      }
    }
    if (attributeExists == false) {
      counter++;
      var newFields = document.getElementById('readproducts').cloneNode(true);
      newFields.id = '';
      newFields.style.display = 'block';

      var inputFields = newFields.getElementsByTagName('input');

      for (y=0; y<inputFields.length; y++) {
        if (inputFields[y].type != 'button') {
          inputFields[y].name = inputFields[y].name.substr(4);
          if (inputFields[y].type != 'hidden'){
            if (inputFields[y].value != '0'){
              inputFields[y].value = document.new_product.xsell_select.options[document.new_product.xsell_select.options.selectedIndex].text;
            }
          }else{
            inputFields[y].value = val;
          }
          inputFields[y].disabled = false;
        }
      }

      var insertHere = document.getElementById('writeproducts');
      insertHere.parentNode.insertBefore(newFields,insertHere);
    }

  }

}

function moreupSell() {
  if (document.new_product.upsell_select.options.selectedIndex < 0) return;
  var existingFields = document.new_product.getElementsByTagName('input');
  var attributeExists = false;

  var val = document.new_product.upsell_select.options[document.new_product.upsell_select.options.selectedIndex].value;
  if (val.indexOf('prod_') != -1){
    val = val.replace('prod_', '');
    for (i=0; i<existingFields.length; i++) {
      if (existingFields[i].value == val && existingFields[i].name == 'upsell_product_id[]') {
        attributeExists = true;
        break;
      }
    }
    if (attributeExists == false) {
      counter++;
      var newFields = document.getElementById('readproductsupsell').cloneNode(true);
      newFields.id = '';
      newFields.style.display = 'block';

      var inputFields = newFields.getElementsByTagName('input');

      for (y=0; y<inputFields.length; y++) {
        if (inputFields[y].type != 'button') {
          inputFields[y].name = inputFields[y].name.substr(4);
          if (inputFields[y].type != 'hidden'){
            if (inputFields[y].value != '0'){
              inputFields[y].value = document.new_product.upsell_select.options[document.new_product.upsell_select.options.selectedIndex].text;
            }
          }else{
            inputFields[y].value = val;
          }
          inputFields[y].disabled = false;
        }
      }


      var insertHere = document.getElementById('writeproductsupsell');
      insertHere.parentNode.insertBefore(newFields,insertHere);
    }

  }

}


function toggleXSellStatus(xsellID) {
  var row = document.getElementById(xsellID);
  var rowButton = document.getElementById(xsellID + '-button');
  var inputFields = row.getElementsByTagName('input');

  if (rowButton.value == '-') {
    for (rF=0; rF<inputFields.length; rF++) {
      if (inputFields[rF].type != 'button') {
        inputFields[rF].disabled = true;
      }
    }

    row.className = 'attributeRemove';
    rowButton.value = '+';
  } else {
    for (rF=0; rF<inputFields.length; rF++) {
      if (inputFields[rF].type != 'button') {
        inputFields[rF].disabled = false;
      }
    }


    row.className = '';
    rowButton.value = '-';
  }
}

function toggleUpSellStatus(upsellID) {
  var row = document.getElementById(upsellID);
  var rowButton = document.getElementById(upsellID + '-button');
  var inputFields = row.getElementsByTagName('input');

  if (rowButton.value == '-') {
    for (rF=0; rF<inputFields.length; rF++) {
      if (inputFields[rF].type != 'button') {
        inputFields[rF].disabled = true;
      }
    }

    row.className = 'attributeRemove';
    rowButton.value = '+';
  } else {
    for (rF=0; rF<inputFields.length; rF++) {
      if (inputFields[rF].type != 'button') {
        inputFields[rF].disabled = false;
      }
    }
    row.className = '';
    rowButton.value = '-';
  }
}

</script>
     <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><select name="xsell_select" size="20" style="width: 100%;">
<?php
$categories_array = tep_get_full_category_tree();
for ($i=0,$n=sizeof($categories_array);$i<$n;$i++){
  if ($categories_array[$i]['category'] == 1){
    echo '<option id="' .$categories_array[$i]['id']. '" value="cat_' .$categories_array[$i]['id']. '" style="COLOR:#0046D5" disabled>' . $categories_array[$i]['text'] . '</option>';
  }else{
    echo '<option id="' .$categories_array[$i]['id']. '" value="prod_' .$categories_array[$i]['id']. '" style="COLOR:#555555">' . $categories_array[$i]['text'] . '</option>';
  }
}
?>        
        </select>
        </td>
        <td align="center" width="10%" class="smallText" valign="top">
          <input type="button" value=">>" onClick="morexSell()" class="infoBoxButton">
        </td>
        <td width="60%" valign="top" class="smallText">
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_XSELL_PRODUCTS; ?></legend>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" colspan="3">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS) || !is_array($HTTP_POST_VARS['xsell_product_id'])){
  $query = tep_db_query("select cpxs.xsell_id, cpxs.sort_order, pd.products_name from  " . TABLE_PRODUCTS_XSELL . " cpxs, " . TABLE_PRODUCTS_DESCRIPTION . " pd where cpxs.xsell_id = pd.products_id and pd.language_id = '" . $languages_id . "' and pd.affiliate_id = 0 and cpxs.products_id = '" . $pInfo->products_id . "'");
  while ($data = tep_db_fetch_array($query)){
    echo '      <tr  id="xsell-' . $data['xsell_id'] . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('xsell_product_name[]', $data['products_name'], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('xsell_products_sort_order[]', $data['sort_order']) . tep_draw_hidden_field('xsell_product_id[]', $data['xsell_id'], '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="xsell-' . $data['xsell_id']  . '-button" onClick="toggleXSellStatus(\'xsell-' . $data['xsell_id'] . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}else{

  foreach($HTTP_POST_VARS['xsell_product_id'] as $key => $value){
    echo '      <tr  id="xsell-' . $value . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('xsell_product_name[]', $HTTP_POST_VARS['xsell_product_name'][$key], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('xsell_products_sort_order[]', $HTTP_POST_VARS['xsell_products_sort_order'][$key])  . tep_draw_hidden_field('xsell_product_id[]', $value, '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="xsell-' . $value  . '-button" onClick="toggleXSellStatus(\'xsell-' . $value . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
?>
            </table>
            <span id="writeproducts"></span>
             <div id="readproducts" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><?php echo tep_draw_input_field('new_xsell_product_name[]', '', 'disabled readonly'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('new_xsell_products_sort_order[]', '0', 'disabled') . tep_draw_hidden_field('new_xsell_product_id[]', '', 'disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="this.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode);" class="infoBoxButton"></td>
                </tr>
              </table>
            </div>
          </fieldset>
          </td>
        </tr>
        
     </table>

  </div>
   <div class="tab-page" id="tabupSell">
    <h2 class="tab"><?php echo TEXT_UPSELL; ?></h2>

    <script type="text/javascript"><!--

    mainTabPane.addTabPage( document.getElementById( "tabupSell" ) );
    //-->
    </script>

     <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="30%" valign="top"><select name="upsell_select" size="20" style="width: 100%;">
<?php
//  $categories_array = tep_get_full_category_tree();
for ($i=0,$n=sizeof($categories_array);$i<$n;$i++){
  if ($categories_array[$i]['category'] == 1){
    echo '<option id="' .$categories_array[$i]['id']. '" value="cat_' .$categories_array[$i]['id']. '" style="COLOR:#0046D5" disabled>' . $categories_array[$i]['text'] . '</option>';
  }else{
    echo '<option id="' .$categories_array[$i]['id']. '" value="prod_' .$categories_array[$i]['id']. '" style="COLOR:##FF8A00">' . $categories_array[$i]['text'] . '</option>';
  }
}
?>
        </select>
        </td>
        <td align="center" width="10%" class="smallText" valign="top">
          <input type="button" value=">>" onClick="moreupSell()" class="infoBoxButton">
        </td>
        <td width="60%" valign="top" class="smallText">
 
          <fieldset>
            <legend><?php echo FIELDSET_ASSIGNED_UPSELL_PRODUCTS; ?></legend>


            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" colspan="3">&nbsp;</td>
              </tr>
<?php
if (empty($HTTP_POST_VARS) || !is_array($HTTP_POST_VARS['upsell_product_id'])){
  $query = tep_db_query("select cpus.upsell_id, pd.products_name, cpus.sort_order from  " . TABLE_PRODUCTS_UPSELL . " cpus, " . TABLE_PRODUCTS_DESCRIPTION . " pd where cpus.upsell_id = pd.products_id and pd.language_id = '" . $languages_id . "' and pd.affiliate_id = 0 and cpus.products_id = '" . $pInfo->products_id . "'");
  while ($data = tep_db_fetch_array($query)){
    echo '      <tr id="upsell-' . $data['upsell_id'] . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('upsell_product_name[]', $data['products_name'], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('upsell_products_sort_order[]', $data['sort_order'])  .  tep_draw_hidden_field('upsell_product_id[]', $data['upsell_id'], '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="upsell-' . $data['upsell_id']  . '-button" onClick="toggleUpSellStatus(\'upsell-' . $data['upsell_id'] . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}else{

  foreach($HTTP_POST_VARS['upsell_product_id'] as $key => $value){
    echo '      <tr id="upsell-' . $value . '">
                  <td class="smallText" width="50%">' . tep_draw_input_field('upsell_product_name[]', $HTTP_POST_VARS['upsell_product_name'][$key], ' readonly') . '</td>
                  <td class="smallText"><nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('upsell_products_sort_order[]', $HTTP_POST_VARS['upsell_products_sort_order'][$key])  . tep_draw_hidden_field('upsell_product_id[]', $value, '') . '</td>
                  <td class="smallText" align="right"><input type="button" value="-" id="upsell-' . $value  . '-button" onClick="toggleUpSellStatus(\'upsell-' . $value . '\');" class="infoBoxButton"></td>
                </tr>';
  }
}
?>
            </table>
            <span id="writeproductsupsell"></span>
             <div id="readproductsupsell" style="display: none">
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr class="attributeAdd">
                  <td class="smallText" width="50%"><?php echo tep_draw_input_field('new_upsell_product_name[]', '', 'disabled readonly'); ?></td>
                  <td class="smallText"><?php echo '<nobr>' . TEXT_SORT_ORDER . '</nobr>&nbsp;' . tep_draw_input_field('new_upsell_products_sort_order[]', '0', 'disabled')  . tep_draw_hidden_field('new_upsell_product_id[]', '', 'disabled'); ?></td>
                  <td class="smallText" align="right"><input type="button" value="-" onClick="this.parentNode.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode.parentNode);" class="infoBoxButton"></td>
                </tr>
              </table>
            </div>
          </fieldset>
          </td>
        </tr>
        
     </table>

  </div>
<?php
}
if (PRODUCTS_INVENTORY == 'True') {
?>
   <div class="tab-page" id="tabInventory">
    <h2 class="tab"><?php echo TEXT_INVENTORY; ?></h2>

    <script type="text/javascript" language="JavaScript1.2">
    <!--

    mainTabPane.addTabPage( document.getElementById( "tabInventory" ) );

    var products_id = '<?php echo (int)$HTTP_GET_VARS['pID'];?>';
    var prids = new Array();
    function get_uprids(idx){
      var tmp_prids = new Array();
      var tmp_prids_1 = new Array();
      //alert(idx);
      if (idx < options_ids.length){
        if (idx < options_ids.length - 1){
          tmp_prids = get_uprids(idx + 1);
        }
        for(i=0;i<options_values_ids[options_ids[idx]['id']].length;i++){
          var str = ''
          if (idx == 0){
            str = products_id + '{' + options_ids[idx]['id'] + '}' + options_values_ids[options_ids[idx]['id']][i]['id'];
          }else{
            str = '{' + options_ids[idx]['id'] + '}' + options_values_ids[options_ids[idx]['id']][i]['id'];
          }
          if (tmp_prids.length > 0){
            for (j=0;j<tmp_prids.length;j++){
              tmp_prids_1[tmp_prids_1.length] = str + tmp_prids[j];
            }
          }else{
            tmp_prids_1[tmp_prids_1.length] = str;
          }
        }
      } else {
        tmp_prids_1[tmp_prids_1.length] = products_id;
      }
      return tmp_prids_1;
    }

    function update_inventory(){
      var options_ids_tmp = options_ids;
      var options_values_ids_tmp = options_values_ids;
      options_ids.sort(arraySort);
      for (i=0;i<options_ids.length;i++){
        options_values_ids[options_ids[i]['id']].sort(arraySort);
      }
      var prids = new Array();
      var prid = '';

      prids = get_uprids(0);
      options_ids_tmp = options_ids_tmp;
      options_values_ids = options_values_ids_tmp;

      for (var k=0;k<prids.length;k++){
// -------
    var aLen = 0;
// -------
        var cObj = document.getElementById('inventoryinsert_' + prids[k]);
        if (cObj == null || cObj == 'underfined'){
          var newInventory = document.getElementById('inventoryinsert').cloneNode(true);
          newInventory.id = 'inventoryinsert_' + prids[k];
          newInventory.name = 'inventoryinsert_' + prids[k];
          var legendFields = newInventory.getElementsByTagName('legend');
          var inputFields = newInventory.getElementsByTagName('input');
// -------
        aLen += inputFields.length;
        var selectFields = newInventory.getElementsByTagName('select');
        if ( selectFields!=null && selectFields.length>0 ) {
          for (y=0; y<selectFields.length; y++) {
            inputFields[ aLen+y ] = selectFields[y];
          }
          aLen += selectFields.length;
        }
// -------
          var rowFields = newInventory.getElementsByTagName('tr');
        //for (y=0;y<inputFields.length;y++){
        for (y=0;y<aLen;y++){
            inputFields[y].name = inputFields[y].name.substr(4) + '_' + prids[k];
          }
          for (y=0;y<rowFields.length;y++){
            rowFields[y].id = rowFields[y].id.substr(4) + '_' + prids[k];
          }
          var str = getLegend(prids[k]);
          for (y=0;y<legendFields.length;y++){
            legendFields[y].id = rowFields[y].id.substr(4) + '_' + prids[k];
            legendFields[y].innerHTML = str;
          break;
          }
        var show_qty = newInventory.getElementsByTagName('span');
        if ( current_inventory[prids[k]]!=undefined && show_qty.length>0 ) {
          show_qty[0].innerHTML = current_inventory[prids[k]]; 
        }
        
          newInventory.style.display = 'block';
          var insertHere = document.getElementById('inventorymain');
          insertHere.parentNode.insertBefore(newInventory,insertHere);
        }
      }
      remove_inventory(prids);
    }

    var cnt =0;
    function remove_inventory(prids){
      var cDivs = document.getElementsByTagName('div');
      var toRemove = new Array();

      cnt++;
      for (var i=0;i<cDivs.length;i++){

        var str = cDivs[i].id;
        if (str.indexOf('inventoryinsert_') != -1){
          var ar = str.split("_");
          var prid = ar[1];
          var found = false;
          for (var j=0;j<prids.length;j++){
            if (prids[j] == prid){
              found = true;
              break;
            }
          }
          if (!found){
            toRemove[toRemove.length] =  cDivs[i].id;
          }
        }
      }
      for (var i=0;i<toRemove.length;i++){
        ///alert(toRemove[i]);
        var cDiv = document.getElementById(toRemove[i]);
        //alert(cDiv.parentNode.tagName + ' ' + cDiv.parentNode.id );
        //alert(cDiv.outerHTML)
        //cDiv.outerHTML = '';
        cDiv.parentNode.removeChild(cDiv);
      }
    }
    function getElementValue(ar, idx){
      var str = ''
      for (i=0;i<ar.length;i++){
        if (ar[i]['id'] == idx){
          str = ar[i]['value'];
          break;
        }
      }
      return str;
    }

    function getLegend(sPrid){
      re = /\{|\}/
      var ar = sPrid.split(re);
      var str = '';
      var i=0
      for (i=1;i<ar.length-1;i=i+2){
        if (str == ''){
          str = getElementValue(options_ids, ar[i]) + ' : ' + getElementValue(options_values_ids[ar[i]], ar[i+1]);
        }else{
          str += ', ' + getElementValue(options_ids, ar[i]) + ' : ' + getElementValue(options_values_ids[ar[i]], ar[i+1]);
        }
      }
      return str;
    }
  var current_inventory = {};
    //-->
    </script>
    
    <div id="inventorymain" name="inventorymain"></div>
<?php 
if (empty($HTTP_POST_VARS)){
  $inventory_query = tep_db_query("select * from " . TABLE_INVENTORY . " where prid = '" . (int)$HTTP_GET_VARS['pID']  . "'");
  $current_stock_a = array();
  while ($inventory_data = tep_db_fetch_array($inventory_query)){
    $current_stock_a[ $inventory_data['products_id'] ] = $inventory_data['products_quantity'];
    $arr = split("[{}]", $inventory_data['products_id']);
    $label = '';
    for ($i=1,$n=sizeof($arr);$i<$n;$i=$i+2){
      $options_name_data = tep_db_fetch_array(tep_db_query("select products_options_name as name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $arr[$i]. "' and language_id  = '" . (int)$languages_id . "'"));
      $options_values_name_data = tep_db_fetch_array(tep_db_query("select products_options_values_name as name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id  = '" . $arr[$i+1]. "' and language_id  = '" . (int)$languages_id . "'"));
      if ($label == ''){
        $label = $options_name_data['name'] . ' : ' . $options_values_name_data['name'];
      }else{
        $label .= ', ' . $options_name_data['name'] . ' : ' . $options_values_name_data['name'];
      }
    }

?>
    <div id="inventoryinsert_<?php echo $inventory_data['products_id']?>" name="inventoryinsert_<?php echo $inventory_data['products_id'];?>" style="display:block">
      <fieldset>
        <legend id="inventorylegend_<?php echo $inventory_data['products_id'];?>"><?php echo $label;?></legend>
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
          <tr id="inventoryrow_<?php echo $inventory_data['prid']?>">
            <td class="main"><?php echo TABLE_HEADING_PRODUCTS_MODEL;?></td>
            <td class="main"><?php echo tep_draw_input_field('inventorymodel_' . $inventory_data['products_id'], $inventory_data['products_model']);?></td>
            <td class="main"><?php echo TEXT_HEADING_QUANTITY;?></td>
            <td class="main"><span><?php echo $current_stock_a[ $inventory_data['products_id'] ]; ?></span> +/- <?php echo tep_draw_input_field('inventoryqty_' . $inventory_data['products_id'], '');?></td>
          </tr>
        </table>
      </fieldset>
    </div>

<?php
  }
  if (isset($HTTP_GET_VARS['pID'])) {
    $query_string = '';
    $query = tep_db_query("select po.products_options_id, pov.products_options_values_id, po.products_options_name, pov.products_options_values_name from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . $pInfo->products_id . "' and pa.options_id = po.products_options_id and po.language_id = '" . $languages_id . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . $languages_id . "'");
    while ($data = tep_db_fetch_array($query)){
      $query_string .= 'add_options("' . $data['products_options_id'] . '", "' . $data['products_options_values_id'] . '", "' . htmlspecialchars($data['products_options_name']) . '", "' . htmlspecialchars($data['products_options_values_name']) . '")'. "\n";
    }
  }

}else{
  $inventory_query = tep_db_query("select * from " . TABLE_INVENTORY . " where prid = '" . (int)$HTTP_GET_VARS['pID']  . "'");
  $current_stock_a = array();
  while ($inventory_data = tep_db_fetch_array($inventory_query)){
    $current_stock_a[ $inventory_data['products_id'] ] = $inventory_data['products_quantity'];
  }
  if (sizeof($HTTP_POST_VARS['price_prefix'])){
    $options = array();
    foreach ($HTTP_POST_VARS['price_prefix'] as $groups => $attributes) {
      foreach ($HTTP_POST_VARS['price_prefix'][$groups] as $key => $value) {
        $options_name_data = tep_db_fetch_array(tep_db_query("select products_options_name as name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $groups . "' and language_id  = '" . (int)$languages_id . "'"));
        $options_values_name_data = tep_db_fetch_array(tep_db_query("select products_options_values_name as name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id  = '" . $key. "' and language_id  = '" . (int)$languages_id . "'"));
        $query_string .= 'add_options("' . $groups . '", "' . $key . '", "' . $options_name_data['name']. '", "' . $options_values_name_data['name']. '")'. "\n";
        if (isset($options[$groups])){
          $options[$groups][] = $key;
        }else{
          $options[$groups] = array();
          $options[$groups][] = $key;
        }
      }
    }

    ksort($options);
    reset($options);
    $i=0;
    $idx = 0;
    foreach ($options as $key => $value){
      if ($i==0){
        $idx=$key;
        $i=1;
      }
      asort($options[$key]);
    }

    $inventory_options = get_inventory_uprid($options, $idx);
  } else{
    $inventory_options = array('');
  }

    for ($i=0,$n=sizeof($inventory_options);$i<$n;$i++){
      $arr = split("[{}]", '0' . $inventory_options[$i]);
      $label = '';
      for ($j=1,$m=sizeof($arr);$j<$m;$j=$j+2){
        $options_name_data = tep_db_fetch_array(tep_db_query("select products_options_name as name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $arr[$j]. "' and language_id  = '" . (int)$languages_id . "'"));
        $options_values_name_data = tep_db_fetch_array(tep_db_query("select products_options_values_name as name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id  = '" . $arr[$j+1]. "' and language_id  = '" . (int)$languages_id . "'"));
        if ($label == ''){
          $label = $options_name_data['name'] . ' : ' . $options_values_name_data['name'];
        }else{
          $label .= ', ' . $options_name_data['name'] . ' : ' . $options_values_name_data['name'];
        }
      }
      if (isset($HTTP_GET_VARS['pID'])) {
        $inventory_products_id = (int)$HTTP_GET_VARS['pID'];
      }else{
        $inventory_products_id = 0;
      }
  ?>
      <div id="inventoryinsert_<?php echo $inventory_products_id . $inventory_options[$i]?>" style="display:block">
        <fieldset>
          <legend id="inventorylegend_<?php echo $inventory_products_id . $inventory_options[$i];?>"><?php echo $label;?></legend>
          <table border="0" cellpadding="2" cellspacing="2" width="100%">
            <tr id="inventoryrow_<?php echo $inventory_products_id . $inventory_options[$i]?>">
              <td class="main"><?php echo TABLE_HEADING_PRODUCTS_MODEL;?></td>
              <td class="main"><?php echo tep_draw_input_field('inventorymodel_' . $inventory_products_id . $inventory_options[$i], $HTTP_POST_VARS['inventorymodel_'. $inventory_products_id . $inventory_options[$i]]);?></td>
              <td class="main"><?php echo TEXT_HEADING_QUANTITY;?></td>
              <td class="main" width="220" align="right" valign="top" ><span><?php echo ( !empty($current_stock_a[ $inventory_products_id . $inventory_options[$i] ])?$current_stock_a[ $inventory_products_id . $inventory_options[$i] ]:'0'); ?></span> +/- <?php echo tep_draw_input_field('inventoryqty_' . $inventory_products_id . $inventory_options[$i], $HTTP_POST_VARS['inventoryqty_'. $inventory_products_id . $inventory_options[$i]]);?></td>
            </tr>
          </table>
        </fieldset>
      </div>
<?    
    }
  
}
?>    

    <div id="inventoryinsert" style="display:none">
      <fieldset>
        <legend id="new_inventorylegend"></legend>
        <table border="0" cellpadding="2" cellspacing="2" width="100%">
          <tr id="new_inventoryrow">
            <td class="main"><?php echo TABLE_HEADING_PRODUCTS_MODEL;?></td>
            <td class="main"><?php echo tep_draw_input_field('new_inventorymodel');?></td>
            <td class="main"><?php echo TEXT_HEADING_QUANTITY;?></td>
            <td class="main"><span></span> +/- <?php echo tep_draw_input_field('new_inventoryqty');?></td>
          </tr>
        </table>
      </fieldset>
    </div>
    <script language="Javascript">
    <!--
    current_inventory = {<?php
    $first = true;
    foreach ( $current_stock_a as $u=>$q ) {
      if ( !$first ) echo ',';
      echo "'".$u."':'".$q."'";
      $first = false;
    }
     
    ?>};
    <?php echo $query_string;?>
    update_inventory();
    //-->
    </script>    
    </div>
<?php
}
?>
</div>
          </td>
        </tr>
        </table>

        </td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;';
          if ($bm == 1) {
            echo '<a href="' . tep_href_link(FILENAME_BRAND_MANAGER, 'mID=' . $mID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
          } else {
            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($HTTP_GET_VARS['pID']) ? '&pID=' . $HTTP_GET_VARS['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
          }
?></td>
      </tr>
    </table></form>
