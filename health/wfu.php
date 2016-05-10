<?php

set_time_limit(0);
$starting_dir=dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/';
$for_upload_sub_dir="for_upload";
$resulting_dir=$starting_dir."/".$for_upload_sub_dir."/";

$exclude_extension_for_show=array();
$exclude_extension_for_show[]="tgz";
$exclude_extension_for_show[]="sql";
$exclude_extension_for_show[]="gz";
$exclude_extension_for_show[]="zip";

$exclude_for_show=array();
$exclude_for_show[]="configure.php";
$exclude_for_show[]="for_upload.txt";
$exclude_for_show[]="wfu.php";

$exclude_catalog_for_show=array();
$exclude_catalog_for_show[]="_private";
$exclude_catalog_for_show[]="_vti_bin";
$exclude_catalog_for_show[]="_vti_cnf";
$exclude_catalog_for_show[]="_vti_log";
$exclude_catalog_for_show[]="_vti_pvt";
$exclude_catalog_for_show[]="_vti_txt";
$exclude_catalog_for_show[]="a1";
$exclude_catalog_for_show[]="bda";
$exclude_catalog_for_show[]="cache";
$exclude_catalog_for_show[]="cardiff";
$exclude_catalog_for_show[]="catalogue";
$exclude_catalog_for_show[]="cgi-bin";
$exclude_catalog_for_show[]="download";
$exclude_catalog_for_show[]="For Rob";
$exclude_catalog_for_show[]="forums";
$exclude_catalog_for_show[]="household";
$exclude_catalog_for_show[]="internal";
$exclude_catalog_for_show[]="kelkoo";
$exclude_catalog_for_show[]="neg";
$exclude_catalog_for_show[]="nev";
$exclude_catalog_for_show[]="nickkis";
$exclude_catalog_for_show[]="orders";
$exclude_catalog_for_show[]="pub";
$exclude_catalog_for_show[]="solus";
$exclude_catalog_for_show[]="solutions";
$exclude_catalog_for_show[]="test";
$exclude_catalog_for_show[]="warehouse";
$exclude_catalog_for_show[]="phpMyAdmin";

$action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');
if($action=="")
  $action = (isset($HTTP_POST_VARS['action']) ? $HTTP_POST_VARS['action'] : '');

if(($action != '') && ($action != 'NULL') && (strlen(trim($action)) > 0)) {
    switch ($action) {
       case 'create_file':
          $filename = 'for_upload.txt';
          if (!$handle = fopen($filename, 'w')) {
               echo "Cannot open file ($filename)";
               exit;
          }
          $somecontent="";
          LF1();
          if (fwrite($handle, $somecontent) === FALSE) {
              echo "Cannot write to file ($filename)";
              exit;
          }
          else
            echo "Success, wrote to file ($filename)";
          fclose($handle);
          break;
      case 'copy_files':
        $check_file_size=$HTTP_POST_VARS['check_file_size'];
        $exclude_array=array();
        $exclude=$HTTP_POST_VARS['exclude'];
        $exclude_array1=explode("\n",$exclude);
        foreach($exclude_array1 as $key =>$val)
          if(trim($val)!="")
          $exclude_array[]=trim($val);

        $exclude_catalog_array=array();
        $exclude_catalog=$HTTP_POST_VARS['exclude_catalog'];
        $exclude_catalog_array1=explode("\n",$exclude_catalog);
        foreach($exclude_catalog_array1 as $key =>$val)
          if(trim($val)!="")
          $exclude_catalog_array[]=trim($val);

        $exclude_extension_array=array();
        $exclude_extension_catalog=$HTTP_POST_VARS['exclude_extension'];
        $exclude_extension_array1=explode("\n",$exclude_extension);
        foreach($exclude_extension_array1 as $key =>$val)
          if(trim($val)!="")
          $exclude_extension_array[]=trim($val);

        $year_from=$HTTP_POST_VARS['year_from'];
        $month_from=$HTTP_POST_VARS['month_from'];
        $day_from=$HTTP_POST_VARS['day_from'];
        $hour_from=$HTTP_POST_VARS['hour_from'];
        $minutes_from=$HTTP_POST_VARS['minutes_from'];
        $second_from=$HTTP_POST_VARS['second_from'];

        $year_to=$HTTP_POST_VARS['year_to'];
        $month_to=$HTTP_POST_VARS['month_to'];
        $day_to=$HTTP_POST_VARS['day_to'];
        $hour_to=$HTTP_POST_VARS['hour_to'];
        $minutes_to=$HTTP_POST_VARS['minutes_to'];
        $second_to=$HTTP_POST_VARS['second_to'];

        $date_time_from=mktime($hour_from,$minutes_from,$second_from,$month_from,$day_from,$year_from);
        echo "from: ".date("Y-m-d H:i:s",$date_time_from);
        echo "<br>";
        $date_time_to=mktime($hour_to,$minutes_to,$second_to,$month_to,$day_to,$year_to);
        echo "To: ".date("Y-m-d H:i:s",$date_time_to);
        echo "<br>";
        if (!file_exists($resulting_dir)) {
          if(!mkdir($resulting_dir,0777))
          {
            echo "Error";
            die();
          }
          chmod($resulting_dir,0777);
        }
        else
        {
          LF2();//удаляем содержимок for_upload
        }
        $d=0;
        if(file_exists($starting_dir."for_upload.txt") && filesize($starting_dir."for_upload.txt")>0)
        {
          $d=1;
        }
        LF();
        break;
        }
 }
?>
<table>
<form action="<?=$PHP_SELF?>?action=create_file" method=get>
<input type=hidden name=action value=create_file>
 <input type=submit>
</form>

<table>
<form action="<?=$PHP_SELF?>?action=copy_files" method=post>
  <tr>
    <td>check file size<input type="checkbox" name="check_file_size" value=1></td>
  </tr>
  <tr>
    <td>Exclude</td>
<td colspan=3>
<textarea name="exclude" cols=20 rows=10>
<?
for($i=0;$i<count($exclude_for_show);$i++)
{
echo $exclude_for_show[$i]."\n";
}
?>
</textarea></td>
  </tr>
  <tr>
    <td>Exclude catalog</td>
<td colspan=3>
<textarea name="exclude_catalog" cols=20 rows=10>
<?
for($i=0;$i<count($exclude_catalog_for_show);$i++)
{
echo $exclude_catalog_for_show[$i]."\n";
}
?>
</textarea></td>
  </tr>
  <tr>
    <td>Exclude extension</td>
<td colspan=3>
<textarea name="exclude_extension" cols=20 rows=10>
<?
for($i=0;$i<count($exclude_extension_for_show);$i++)
{
echo $exclude_extension_for_show[$i]."\n";
}
?>
</textarea></td>
  </tr>
  <tr>
    <td>Year from<input type=text name=year_from value="<?=date("Y")?>"></td>
    <td>Month from<input type=text name=month_from value="<?=date("m")?>"></td>
    <td>Day from<input type=text name=day_from value="<?=date("d")?>"></td>
    <td>Hours from<input type=text name=hour_from value="00"></td>
    <td>Minutes from<input type=text name=minutes_from value="00"></td>
    <td>Second from<input type=text name=second_from value="00"></td>
  </tr>
    <tr>
    <td>Year to<input type=text name=year_to value="<?=date("Y")?>"></td>
    <td>Month to<input type=text name=month_to value="<?=date("m")?>"></td>
    <td>Day to<input type=text name=day_to value="<?=date("d")?>"></td>
    <td>Hours to<input type=text name=hour_to value="23"></td>
    <td>Minutes to<input type=text name=minutes_to value="59"></td>
    <td>Second to<input type=text name=second_to value="59"></td>
  </tr>
  <tr>
    <td>
      <input type=submit>
    </td>
  </tr>
</form>
</table>
<?
function LF($sub_dir="")
{
  global $starting_dir,$resulting_dir,$for_upload_sub_dir,$date_time_from,$date_time_to;
  global $exclude_array,$check_file_size, $exclude_catalog_array,$exclude_extension_array;
  global $d;
  if ($handle = opendir($starting_dir.$sub_dir))
  {
    while (false !== ($file = readdir($handle)))
    {
      if($file != "." && $file != ".." && $file !=$for_upload_sub_dir && !in_array($file,$exclude_catalog_array))
      {
        clearstatcache();
        if (is_dir($starting_dir.$sub_dir.$file))
        {
          LF($sub_dir.$file.'/');
        }
        clearstatcache();
        if (is_file($starting_dir.$sub_dir.$file))
        {
          $path_parts = pathinfo($file);
          if(!in_array($file,$exclude_array) && !in_array($path_parts["extension"],$exclude_extension_array))
          {
            $c=$check_file_size;
            $a=-4;
            if($c==1 && $d>0)
                $a=tep_get_file_size_if_file_exists($sub_dir.$file);//-1 искомого файла нет   //0 и больше его размер
            $b=$date_time_from<filemtime($starting_dir.$sub_dir.$file) && filemtime($starting_dir.$sub_dir.$file)<$date_time_to;
           // (если файл новый) или (если дата в нужных пределах и проверять рамер и известен его старый размер и изменился его размер) или (если дата в нужных пределах и непроверять рамер)
            if($a==-1 || ($b && $c==1 && $a>=0 && filesize($starting_dir.$sub_dir.$file)!=$a) || ($c=="" && $b))
            {
              if (!file_exists($resulting_dir.$sub_dir))//если конечной директории не существует
              {
                //создаем список каталогов если их еще нет
                $sub_dir_array=explode("/",$sub_dir);
                if(count($sub_dir_array)>20)
                {
                  echo "Error1";
                  die();
                }
                $old_create_dir="";
                for($i=0;$i<count($sub_dir_array);$i++)
                {
                  $old_create_dir.="/".$sub_dir_array[$i];
                  if (!file_exists($resulting_dir.$old_create_dir))
                  {
                    mkdir($resulting_dir.$old_create_dir,0777);
                    chmod($resulting_dir.$old_create_dir,0777);
                  }
                }
              }
              echo $starting_dir.$sub_dir.$file.'---------------------------------'.date("Y-m-d H:i:s",filemtime($starting_dir.$sub_dir.$file));
              echo "<br>";
              copy($starting_dir.$sub_dir.$file, $resulting_dir.$sub_dir.$file);
              chmod($resulting_dir.$sub_dir.$file,0666);
            }
            /*
            else
            {
              echo $starting_dir.$sub_dir.$file.'---------------------------------'.date("Y-m-d H:i:s",filemtime($starting_dir.$sub_dir.$file));
              echo "  a=".$a;
              echo "<br>";
            }
            */
          }
        }
      }
    }
    closedir($handle);
  }
}
function LF1($sub_dir="")//
{
  global $somecontent;
  global $starting_dir,$for_upload_sub_dir;
  if ($handle = opendir($starting_dir.$sub_dir))
  {
    while (false !== ($file = readdir($handle)))
    {
      if($file != "." && $file != ".." && $file !=$for_upload_sub_dir)
      {
        clearstatcache();
        if (is_dir($starting_dir.$sub_dir.$file))
        {
          LF1($sub_dir.$file.'/');
        }
        clearstatcache();
        if (is_file($starting_dir.$sub_dir.$file))
        {
//        $somecontent.=$starting_dir.$sub_dir.$file."\n";
          $somecontent.=$sub_dir.$file."	".filesize($starting_dir.$sub_dir.$file)."\n";
        }
      }
    }
    closedir($handle);
  }
}
function LF2($sub_dir="")
{
  global $resulting_dir,$for_upload_sub_dir;
  if ($handle = opendir($resulting_dir.$sub_dir))
  {
    while (false !== ($file = readdir($handle)))
    {
      if($file != "." && $file != "..")
      {
        clearstatcache();
        if (is_dir($resulting_dir.$sub_dir.$file))
        {
          LF2($sub_dir.$file.'/');
        }
        clearstatcache();
        if (is_file($resulting_dir.$sub_dir.$file))
        {
//        $somecontent.=$starting_dir.$sub_dir.$file."\n";
          chmod($resulting_dir.$sub_dir.$file,0777);
          unlink($resulting_dir.$sub_dir.$file);
        }
      }
    }
    closedir($handle);
    if($sub_dir!="")
    {
     chmod($resulting_dir.$sub_dir,0777);
      rmdir($resulting_dir.$sub_dir);
    }
  }
}
function tep_get_file_size_if_file_exists($str)
{
  //-1 файла нет
  //0 и больше его размер
  global $starting_dir;
  $handle = fopen ($starting_dir."for_upload.txt", "r");
  while (!feof ($handle))
  {
    $buffer = fgets($handle, 4096);
    $array=explode("	",$buffer);
    if(trim($array[0])==trim($str))
    {
      return trim($array[1]);
    }
  }
  fclose ($handle);
  return -1;
}
?>