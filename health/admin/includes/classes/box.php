<?php
/*
  $Id: box.php,v 1.1.1.1 2005/12/03 21:36:03 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

  class box extends tableBlock {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function infoBox($heading, $contents) {
      $this->heading='';
      $this->contents='';
        $this->table_parameters= '';
        $this->table_data_parameters = 'class="BoxContent1"';
        $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
        $this->heading = $this->tableBlockInfo_heading($heading);
        $this->table_data_parameters = 'class="boxlisting"';
        $this->contents = $this->tableBlock($contents);
        return $this->heading . $this->contents . $dhtml_contents;
    }

    function menuBox($heading, $contents) {

    global $menu_dhtml;              // add for dhtml_menu
    if ($menu_dhtml == false ) {     // add for dhtml_menu
      $this->heading='';
      $this->contents='';
      if($heading!="")
      {
        $this->table_parameters= '';
        $this->table_data_parameters = 'class="BoxContent"';
        if ($heading[0]['link']) {
          $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
          $heading[0]['text'] = '&nbsp;<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>&nbsp;';
        } else {
          $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
        }
        $this->heading = $this->tableBlock_heading($heading);
      }
      if($contents!="")
      {
//        $this->table_parameters= ' background="images/infobox/header_bg.gif" ';
        $this->table_data_parameters = 'class="boxlisting"';
        $this->contents = $this->tableBlock($contents);
      }
      return $this->heading . $this->contents . $dhtml_contents;
// ## add for dhtml_menu
    } else {
      $selected = substr(strrchr ($heading[0]['link'], '='), 1);
      $dhtml_contents = $contents[0]['text'];
      $change_style = array ('<br>'=>' ','<BR>'=>' ', 'a href='=> 'a class="menuItem" href=','class="menuBoxContentLink"'=>' ');
      $dhtml_contents = strtr($dhtml_contents,$change_style);
      $dhtml_contents = '<div id="'.$selected.'Menu" class="menu" onmouseover="menuMouseover(event)">'. $dhtml_contents . '</div>';
      return $dhtml_contents;
      }
// ## eof add for dhtml_menu
    }
    function menuBoxIndex($heading, $contents) {

    global $menu_dhtml;              // add for dhtml_menu
    if ($menu_dhtml == false ) {     // add for dhtml_menu
      $this->heading='';
      $this->contents='';
      $this->table_parameters= ' background="images/infobox/header_bg.gif" ';
      $this->table_data_parameters = 'class="menuBoxHeading"';
      if ($heading[0]['link']) {
        $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
        $heading[0]['text'] = '&nbsp;<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>&nbsp;';
      } else {
        $heading[0]['text'] = '&nbsp;' . $heading[0]['text'] . '&nbsp;';
      }
      $this->heading = $this->tableBlockIndex_heading($heading);
      if($contents!="")
      {
      $this->table_parameters= '';
      $this->table_data_parameters = 'class="BoxContent"';
      $this->contents = $this->tableBlockIndex($contents);
      }
      return $this->heading . $this->contents . $dhtml_contents;
// ## add for dhtml_menu
    } else {
      $selected = substr(strrchr ($heading[0]['link'], '='), 1);
      $dhtml_contents = $contents[0]['text'];
      $change_style = array ('<br>'=>' ','<BR>'=>' ', 'a href='=> 'a class="menuItem" href=','class="menuBoxContentLink"'=>' ');
      $dhtml_contents = strtr($dhtml_contents,$change_style);
      $dhtml_contents = '<div id="'.$selected.'Menu" class="menu" onmouseover="menuMouseover(event)">'. $dhtml_contents . '</div>';
      return $dhtml_contents;
      }
// ## eof add for dhtml_menu
    }
  }
?>
