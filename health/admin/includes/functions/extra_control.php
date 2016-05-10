<?php

function tep_init_calendar(){
  return '<div id="spiffycalendar" class="text" style="z-index:100"></div>'."\n".
         '<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">'."\n".
         '<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>'."\n";
}

// $formname - form name where control placed, 
// $html_field_name - name html text input,           01234567
// $value='', - raw mysql date 0000-00-00 00:00:00 or yyyymmdd
// $extra_js ='' - extra params for control 
function tep_draw_calendar( $formname, $html_field_name, $value='', $display_top=false, $display_left=false){
  if (tep_not_null($value)){
    $value = substr(str_replace('-','',$value),0,8);
    if ( intval($value)!=0 ) {
      $chunks = split('[./-]',DATE_FORMAT_SPIFFYCAL);
      $ctl_value = DATE_FORMAT_SPIFFYCAL;
      foreach( $chunks as $idx=>$chunk ) {
        if ( $chunk[0]=='y' ) $ctl_value = str_replace($chunk, substr($value,4-strlen($chunk),strlen($chunk)), $ctl_value);
        if ( $chunk[0]=='d' ) $ctl_value = str_replace($chunk, (strlen($chunk)==1)?intval(substr($value,6,2)):substr($value,6,2), $ctl_value);
        if ( $chunk=='M' || $chunk=='MM' ) $ctl_value = str_replace($chunk, ((strlen($chunk)==1)?intval(substr($value,4,2)):substr($value,4,2)), $ctl_value);
        if ( $chunk=='MMM' ) {
          $cal_month = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
          $ctl_value = str_replace($chunk, $cal_month[intval(substr($value,4,2))], $ctl_value );
        }
      }
    }else{
      $ctl_value = '';
    }
  }else{
    $ctl_value = '';
  }
  return '<script language="javascript">
   var jsctl_'.$html_field_name.' = new ctlSpiffyCalendarBox("jsctl_'.$html_field_name.'", "'.$formname.'", "'.$html_field_name.'","btn'.$html_field_name.'","'.$ctl_value.'",scBTNMODE_CUSTOMBLUE);
   jsctl_'.$html_field_name.'.writeControl(); jsctl_'.$html_field_name.'.dateFormat="'.DATE_FORMAT_SPIFFYCAL.'";'."\n".
   (( $display_top )?'jsctl_'.$html_field_name.'.displayTop=true; ':'').(( $display_left )?'jsctl_'.$html_field_name.'.displayLeft=true; ':'').'</script><noscript>'.tep_draw_input_field( $html_field_name, $ctl_value,' size="12"').'</noscript>';   
}

function tep_calendar_rawdate( $formated ){
  if ( !tep_not_null($formated) ) return '';
  $chunks = split('[./-]',DATE_FORMAT_SPIFFYCAL);
  $formated = split('[./-]', $formated, count($chunks) );
  if ( count($chunks)!=count($formated) ) return '';
  $y=date('Y');$m=0;$d=0;
  foreach( $chunks as $idx=>$chunk ) {
    if ( $chunk=='yyyy' ) $y = (int)$formated[$idx];
    if ( $chunk=='yy' || $chunk=='y' ) $y = (int)$formated[$idx]+ ($formated[$idx]<20?2000:1900);
    if ( $chunk[0]=='d' ) $d = (int)$formated[$idx];
    if ( $chunk=='M' || $chunk=='MM' ) $m = (int)$formated[$idx];
    if ( $chunk=='MMM' ) $m = array_search( $formated[$idx], array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec') );
  }
  if ( $y==0 || $m==0 || $d==0 ) return '0000-00-00 00:00:00';
  return date("Y-m-d", mktime(0, 0, 0, $m, $d, $y));//." 00:00:00";
  //return sprintf('%4u-%02u-%02u 00:00:00',$y,$m,$d);
}
?>