<?
require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
header("Content-type: text/x-csv");
header('Content-Disposition: attachment; filename="search_statistics.csv"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
header('Pragma: no-cache');

//echo '"Title","First Name","Last Name","Address","City","State","Zip","Country","Phone","Fax","Cell","Email","Experience","Education","Employment History","Additional Info","Location(s)","Category(es)","Relocation","Job Type","Job Hours","Posted Date"' . "\n";

echo '"'.TABLE_HEADING_KEYWORD.'","'.TABLE_HEADING_SEARCH_ENGINE.'","'.TABLE_HEADING_CLICK_COUNT.'","'.TABLE_HEADING_BUY_COUNT.'","'.TABLE_HEADING_BUY_TOTAL.'","'.TABLE_HEADING_AVG_TOTAL.'"'. "\n";

$specials_query_raw = "select se.search_engines_id, sw.search_words_id, sw.word, se.name, sw.click_count, count(o.orders_id) as buy_count, sum(ot.value*o.currency_value) as buy_total from " . TABLE_SEARCH_WORDS ." sw left join " . TABLE_ORDERS . " o on  (se.search_engines_id=o.search_engines_id) right join " . TABLE_SEARCH_ENGINES . " se on (sw.search_words_id=o.search_words_id) left join " . TABLE_ORDERS_TOTAL ." ot on (o.orders_id=ot.orders_id and ot.class='ot_subtotal') where se.search_engines_id=sw.search_engines_id ";
if ($HTTP_GET_VARS['spwd']!=123) $specials_query_raw .= " and show_flag=1 ";
if ($HTTP_GET_VARS['seID']>0){
  $specials_query_raw .= " and se.search_engines_id= '" . $HTTP_GET_VARS['seID'] . "' ";
}
$specials_query_raw .= "group by se.search_engines_id, sw.search_words_id, sw.word, se.name, sw.click_count order by buy_total desc, click_count desc, sw.word ";

//$query = "select distinct R.*, DATE_FORMAT(R.PostedDate, '%M %d, %Y') PostedDate, C.NameKey country, S.NameKey State, Ed.NameKey education, Ex.NameKey experience from js_Resumes R, js_Countries C, js_States S, js_Educations Ed, js_Experiences Ex, js_ResumeToJobCategory RJC, js_ResumeToLocation RL where R.Public = 1 and R.CountryID=C.ID and R.StateID=S.ID and R.EducationID=Ed.ID and R.ExperienceID=Ex.ID and R.ID = RJC.ResumeID and R.ID = RL.ResumeID ";
$specials_query = tep_db_query($specials_query_raw);
while ($specials = tep_db_fetch_array($specials_query)) 
{  $word=$specials['word'];
  $name=$specials['name'];
  $click_count=$specials['click_count'];
  $buy_count=$specials['buy_count'];
  $buy_total=$currencies->format($specials['buy_total']);
  $Avg_order_amount=$currencies->format($specials['buy_count']>0?$specials['buy_total']/$specials['buy_count']:0);

  echo '"' . str_replace('"', '""', $word) . '","' . str_replace('"', '""', $name) . '","' . str_replace('"', '""', $click_count) . '","' . str_replace('"', '""', $buy_count) . '","' . str_replace('"', '""', $buy_total) . '","' . str_replace('"', '""', $Avg_order_amount). '"'. "\n";
}
?>