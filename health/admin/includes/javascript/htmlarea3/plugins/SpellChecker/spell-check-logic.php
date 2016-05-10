<?php
  $text = stripslashes($_POST['content']);
  $aspell = $aspell_args = $temptext = '';
  // Convert UTF-8 multi-bytes into decimal character entities.  This is because
  // aspell isn't fully utf8-aware - ticket:120 raises the possibility 
  // that this is not required (any more) and so you can turn it off
  // with editor.config.SpellChecker.utf8_to_entities = false 
  if(!isset($_REQUEST['utf8_to_entitis']) || $_REQUEST['utf8_to_entities'])
  {
    $text = preg_replace('/([\xC0-\xDF][\x80-\xBF])/e', "'&#' . utf8_ord('\$1') . ';'", $text);
    $text = preg_replace('/([\xE0-\xEF][\x80-\xBF][\x80-\xBF])/e',             "'&#' . utf8_ord('\$1') . ';'",  $text);
    $text = preg_replace('/([\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF])/e', "'&#' . utf8_ord('\$1') . ';'",   $text);
  }
  
  function utf8_ord($chr)
  {
    switch(strlen($chr))
    {
      case 1 :
        return ord($chr);

      case 2 :
        $ord = ord($chr{1}) & 63;
        $ord = $ord | ((ord($chr{0}) & 31) << 6);
        return $ord;

      case 3 :
        $ord = ord($chr{2}) & 63;
        $ord = $ord | ((ord($chr{1}) & 63) << 6);
        $ord = $ord | ((ord($chr{0}) & 15) << 12);
        return $ord;

      case 4 :
        $ord = ord($chr{3}) & 63;
        $ord = $ord | ((ord($chr{2}) & 63) << 6);
        $ord = $ord | ((ord($chr{1}) & 63) << 12);
        $ord = $ord | ((ord($chr{0}) & 7)  << 18);
        return $ord;

      default :
        trigger_error('Character not utf-8', E_USER_ERROR);
    }
  }

  $temptext = tempnam('/tmp', 'spell_');
  $lang = '';
  if ((isset($_POST['dictionary'])) && (strlen(trim($_POST['dictionary'])) > 0)) { $lang = trim($_POST['dictionary']); }  

  $aspell      = 'aspell';   
  $dictionaries = str_replace(chr(10),",", shell_exec("$aspell dump dicts"));
  $dictionaries = preg_replace('/,$/','',$dictionaries);  
  $dictionaries_array = explode(',',$dictionaries);
  $new_lang = array();
  foreach ( $dictionaries_array as $ki=>$dlang ) { if ( preg_match('/^en_GB/',$dlang) ) { $new_lang[] = $dlang; unset($dictionaries_array[$ki]); } }
  foreach ( $dictionaries_array as $dlang ) { $new_lang[] = $dlang; }
  $dictionaries_array = $new_lang;
  if ( count( $dictionaries_array ) && !in_array($lang, $dictionaries_array ) ) {
    $lang = $dictionaries_array[0];
  }else{
    //$lang = 'en_GB';
  }
  $lang = preg_replace('/[^a-z0-9_]/i', '', $lang);
  
  $aspell_args = '-a --lang=' . $lang;
  // Old aspell doesn't know about encoding, which means that unicode will be broke, but
  // we should at least let it try.
  preg_match('/really aspell ([0-9]+)\.([0-9]+)(?:\.([0-9]+))?/i', `$aspell version`, $aVer);
  $aVer = array('major' => (int)$aVer[1], 'minor' => (int)$aVer[2], 'release' => (int)@$aVer[3]);
  if($aVer['major'] >= 0 && $aVer['minor'] >= 60) {
    $aspell_args   .= ' -H --encoding=utf-8';
  }elseif(preg_match('/--encoding/', shell_exec('aspell 2>&1'))){
    $aspell_args   .= ' --mode=none --add-filter=sgml --encoding=utf-8';
  }else{
    $aspell_args   .= ' --mode=none --add-filter=sgml';
  }

// as an additional precaution check the aspell_args for illegal 
// characters
  $aspell_args = preg_replace( "/[|><;\$]+/", '', $aspell_args );
  $aspellcommand      = "$aspell $aspell_args < $temptext";

##############################################################################
header('Content-Type: text/html; charset=utf-8');
  echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="spell-check-style.css" />';

// Lets define some values outside the condition below, in case we have an empty 
// document.                                                                     
$textarray = array();
$varlines = '<script type="text/javascript">var suggested_words = { ';
$infolines = 'var spellcheck_info = {';
$counter = 0;
$suggest_count = 0;

if (trim($text) != "")
{
    if ($fd = fopen($temptext, 'w'))
    {
        $textarray = explode("\n", $text);
        fwrite ($fd, "!\n");
        foreach ($textarray as $key=>$value)
        {
            // adding the carat to each line prevents the use of aspell commands within the text...
            fwrite($fd, "^$value\n");
        }
        fclose($fd);
        chmod($temptext, 0777);
        // next run aspell
        $return = shell_exec($aspellcommand . ' 2>&1');
        // echo $return;
        unlink($temptext);
        $returnarray = explode("\n", $return);
        $returnlines = count($returnarray);
//print_r(htmlentities($return));
        $textlines = count($textarray);

        $lineindex = -1;
        $poscorrect = 0;
        foreach ($returnarray as $key=>$value)
        {
            // if there is a correction here, processes it, else move the $textarray pointer to the next line
            if (substr($value, 0, 1) == '&')
            {
               $counter=$counter+1;
                $correction = explode(' ', $value);
                $word = $correction[1];
                $suggest_count += $correction[2];
                $absposition = substr($correction[3], 0, -1) - 1;
                $position = $absposition + $poscorrect;
                $niceposition = $lineindex.','.$absposition;
                $suggstart = strpos($value, ':') + 2;
                $suggestions = substr($value, $suggstart);
                $suggestionarray = explode(', ', $suggestions);

                $beforeword = substr($textarray[$lineindex], 0, $position);
                $afterword = substr($textarray[$lineindex], $position + strlen($word));
                $textarray[$lineindex] = $beforeword.'<span class="HA-spellcheck-error">'.$word.'</span>'.$afterword;

             $suggestion_list = '';
                foreach ($suggestionarray as $key=>$value)
                {
                    $suggestion_list .= $value.',';
                }
                $suggestion_list = substr($suggestion_list, 0, strlen($suggestion_list) - 1);
                $varlines .= '"'.$word.'":"'.$suggestion_list.'",';

                $poscorrect = $poscorrect + 41;
            }
            elseif (substr($value, 0, 1) == '#')
            {
                $correction = explode(' ', $value);
                $word = $correction[1];
                $absposition = $correction[2] - 1;
                $position = $absposition + $poscorrect;
                $niceposition = $lineindex.','.$absposition;
                $beforeword = substr($textarray[$lineindex], 0, $position);
                $afterword = substr($textarray[$lineindex], $position + strlen($word));
                $textarray[$lineindex] = $beforeword.$word.$afterword;
                $textarray[$lineindex] = $beforeword.'<span class="HA-spellcheck-error">'.$word.'</span><span class="HA-spellcheck-suggestions">'.$word.'</span>'.$afterword;
//                $poscorrect = $poscorrect;
                $poscorrect = $poscorrect + 88 + strlen($word);
            }
            else
            {
                //print "Done with line $lineindex, next line...<br><br>";
                $poscorrect = 0;
                $lineindex = $lineindex + 1;
            }
         }
     }
     else
     {
       // This one isnt used for anything at the moment!
       $return = 'failed to open!';
     }
} 
else 
{ 
  $returnlines=0; 
}
$infolines .= '"Language Used":"'.$lang.'",';
$infolines .= '"Mispelled words":"'.$counter.'",';
$infolines .= '"Total words suggested":"'.$suggest_count.'",';
$infolines .= '"Total Lines Checked":"'.$returnlines.'"';
$infolines .= '};';
$varlines = substr($varlines, 0, strlen($varlines) - 1);
echo $varlines.'};'.$infolines.'</script>';

echo '</head>
<body onload="window.parent.finishedSpellChecking();">';

foreach ($textarray as $key=>$value)
{
  echo $value;
}

//if ( isset($_POST['init']) && $_POST['init']=='1' ) 
echo '<div id="HA-spellcheck-dictionaries">'.(is_array($dictionaries_array)?implode(',',$dictionaries_array):'').'</div>';

echo '</body></html>';
?>
