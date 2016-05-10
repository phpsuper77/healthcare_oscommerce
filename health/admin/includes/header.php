<?php
/*
  $Id: header.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if ($messageStack->size > 0) {
    if ($HTTP_GET_VARS['read'] == 'only') {
    }else{
      echo $messageStack->output();
    }
  }
?>
          <table cellspacing=0 cellpadding=0 width=100% border=0 background="images/top_bg_l.gif" height="60">
            <tr>
              <td width="42"><img src="images/top_left.gif" width="28" height="60" alt="" border="0"></td>
              <td width="204"><img src="images/logo_l.gif" width="176" height="60" alt="" border="0"></td>
              <td width="96"><img src="images/icon/title_config.gif" width="64" height="60" alt="" border="0"></td>
              <td class="title">
              <?
              if($header_title_menu!="")
              {
              ?>
                <a class="title" href="<?=$header_title_menu_link?>"><?=$header_title_menu?></a> :: <font color="#8182BE"><?=$header_title_submenu?></font>
              <?
              }
              else
              {
                echo "&nbsp;";
              }
              if ($header_additional != ''){
                echo "<br>" . $header_additional;
              }
              ?>
              </td>
              <td width="288" class=headerbarcontent align="center">
                <table cellspacing=0 cellpadding=0 border=0>
                  <tr>
                    <td width="288" class=headerbarcontent align="right">
                      <a href="http://www.oscommerce.com" class="headerLink">osCommerce</a>&nbsp;|&nbsp; 
                      <a href="<?=tep_catalog_href_link()?>" class="headerLink">Catalog</a>&nbsp;|&nbsp;
                      <a href="<?=tep_href_link(FILENAME_ORDERS)?>" class="headerLink"><?=HEADER_LINK_ORDERS;?></a>&nbsp;|&nbsp;
                      <a href="<?=tep_href_link(FILENAME_DEFAULT, '', 'NONSSL')?>" class="headerLink"><?=HEADER_TITLE_ADMINISTRATION?></a>&nbsp;|&nbsp;
                      <a href="<?=tep_href_link(FILENAME_LOGOFF, '', 'NONSSL')?>" class="headerLink"><?=HEADER_TITLE_LOGOFF?></a>&nbsp;</td>
                  </tr>
                  <?
                  if($header_title_additional!="")
                  {
                  ?>
                  <tr>
                    <td width="288" class=headerbarcontent1 align="right"><?=$header_title_additional?></td>
                  </tr>
                  <?
                  }
                  ?>
                </table>
              </td>
            </tr>
          </table>