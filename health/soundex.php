<?php
 set_time_limit(0);
 require("includes/application_top.php");

 $res = tep_db_query("select * from ".TABLE_PRODUCTS_DESCRIPTION);
 $counts = 0;
 echo tep_db_num_rows($res)."<br>";
 flush();
 while($row = tep_db_fetch_array($res)) {
 $counts++;
 echo $counts."<br>";
 flush();


            $products_name_soudnex = '';
            $products_name_soudnex = strip_tags($row['products_name']);
            $products_name_soudnex = str_replace(array(",",";",".","&","!",":"),array("","","","","",""),$products_name_soudnex);
            $products_name_keywords = preg_split ("/[\s,]+/", $products_name_soudnex,-1,PREG_SPLIT_NO_EMPTY);
            $pares = array();
            for ($j=0;$j<sizeof($products_name_keywords);$j++) {
             //  $pares[] =  soundex($products_name_keywords[$j]);
               for ($k=0;$k<sizeof($products_name_keywords);$k++) {
                   if ($j!=$k) {
                     $_pares = '';
                     $_pares = addslashes($products_name_keywords[$j]." ".$products_name_keywords[$k]);
                     $ress = tep_db_fetch_array(tep_db_query("select soundex('".$_pares."') as sx"));
                     $pares[] = $ress["sx"];
                   }
               }
            }
            $pares = array_unique($pares);
            $products_name_soudnex = addslashes(join(",",$pares));

            $products_description_soudnex = '';
            $products_description_soudnex = strip_tags($row['products_description']);
            $products_description_soudnex = str_replace(array(",",";",".","&","!",":"),array("","","","","",""),$products_description_soudnex);

            $products_description_keywords = preg_split ("/[\s,]+/", $products_description_soudnex,-1,PREG_SPLIT_NO_EMPTY);
            $pares = array();
            for ($j=0;$j<sizeof($products_description_keywords);$j++) {
               for ($k=0;$k<sizeof($products_description_keywords);$k++) {
                   if ($j!=$k) {
                     $_pares = '';
                     $_pares = addslashes($products_description_keywords[$j]." ".$products_description_keywords[$k]);
                     $ress = tep_db_fetch_array(tep_db_query("select soundex('".$_pares."') as sx"));
                     $pares[] = $ress["sx"];
                   }
               }
            }
            $pares = array_unique($pares);
            $products_description_soudnex = addslashes(join(",",$pares));

            tep_db_query("update ".TABLE_PRODUCTS_DESCRIPTION." set  products_name_soundex='".$products_name_soudnex."', products_description_soundex='".$products_description_soudnex."' where products_id=".$row["products_id"]." and language_id=".$row["language_id"] . " and affiliate_id = " . $row['affiliate_id'] . "");

 }



?>