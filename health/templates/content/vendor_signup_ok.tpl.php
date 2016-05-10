<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
    <tr>
      <td>
<?php
  $infobox_contents = array();
  $infobox_contents[] = array(array('text' =>HEADING_TITLE, 'params' => 'align=center'), array('params'=> 'align=right', 'text' => ''));
  new contentPageHeading($infobox_contents);
?>     

      </td>
    </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><?php echo tep_image(DIR_WS_TEMPLATE_IMAGES . 'spacer.gif', HEADING_TITLE); ?></td>
            <td valign="top" class="main"><?php echo TEXT_ACCOUNT_CREATED; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="right">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')),
                               array('params' => 'align="right" class="main" width=100%', 'text' => '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.' . BUTTON_IMAGE_TYPE, IMAGE_BUTTON_CONTINUE, 'class="transpng"') . '</a>'),
                               array('text' => tep_draw_separator('pixel_trans.gif', '10', '1')));
  new buttonBox($info_box_contents);
?> 
      </td>
      </tr>
    </table>