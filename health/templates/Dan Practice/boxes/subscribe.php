<!-- Subscribe //-->

<tr>
  <td class="infoBoxCell">
  <?php
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'))
  {
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }
  else
  {
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_SUBSCRIBE);
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading))
    {
      new $infoboox_class_heading($info_box_contents, false, false);
    }
    else
    {
      new infoBoxHeading($info_box_contents, false, false);
    }
  }
                               
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TEXT_NEWSLETTER_TEXT, 'params' => 'style="padding-bottom:5px;"');
  $info_box_contents[] = array('text' => tep_draw_form('subscribe', tep_href_link(FILENAME_SUBSCRIBERS, '', 'NONSSL', false), 'get').tep_hide_session_id().'
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="text" name="email_address" maxlength="40" size="15" class="inp" style="width:110px"></td>
		<td style="padding: 0 0 0px 2px;">' . tep_template_image_submit("button_go." . BUTTON_IMAGE_TYPE, TEXT_KEEP_UPTODATE_VIA_EMAIL, 'class="transpng"') .'</td>
  </tr>
</table></form>
  ');

  if (class_exists($infobox_class))
  {
    new $infobox_class($info_box_contents);
  }
  else
  {
    new infoBox($info_box_contents);
  }
  ?>
  </td>
</tr>
<!-- Subscribe_eof //-->