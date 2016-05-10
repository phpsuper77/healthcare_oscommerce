<?php
/*
  $Id: whos_online.php, v 1.0 2001/12/05 by Max

  Copyright (c) 2002-2006 Holbi.co.uk

  Released under the GNU General Public License

*/
?>
<!-- whos_online //-->
          <tr>
            <td class="infoBoxCell">
<?php

  $whos_online_query = tep_db_query("select customer_id from " . TABLE_WHOS_ONLINE);
  $user_total = sprintf(tep_db_num_rows($whos_online_query));
  while ($whos_online = tep_db_fetch_array($whos_online_query)) {
                        if (!$whos_online['customer_id'] == 0) $n_members++;
                        if ($whos_online['customer_id'] == 0) $n_guests++;
  }

  if ($user_total == 1) {
    $there_is_are = BOX_WHOS_ONLINE_THEREIS . '&nbsp;';
  } else {
    $there_is_are = BOX_WHOS_ONLINE_THEREARE . '&nbsp;';
  }

  if ($n_guests == 1) {
    $word_guest = '&nbsp;' . BOX_WHOS_ONLINE_GUEST;
  }else{
    $word_guest = '&nbsp;' . BOX_WHOS_ONLINE_GUESTS;
  }

  if ($n_members == 1) {
    $word_member = '&nbsp;' . BOX_WHOS_ONLINE_MEMBER;
  }else{
    $word_member = '&nbsp;' . BOX_WHOS_ONLINE_MEMBERS;
  }


  if (($n_guests >= 1) && ($n_members >= 1)) $word_and = '&nbsp;' . BOX_WHOS_ONLINE_AND . '&nbsp;<br>';

      $textstring = $there_is_are;
        if ($n_guests >= 1) $textstring .= $n_guests . $word_guest;

      $textstring .= $word_and;
        if ($n_members >= 1) $textstring .= $n_members . $word_member;

      $textstring .= '&nbsp;' . TEXT_ONLINE . '.';

  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{

    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_WHOS_ONLINE );
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }
  }

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  =>  $textstring
                              );
  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
?>
            </td>
          </tr>
<!-- whos_online_eof //-->
