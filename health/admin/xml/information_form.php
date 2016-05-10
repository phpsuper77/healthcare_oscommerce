<?PHP
  /*
  Module: Information Pages Unlimited
  		  File date: 2003/03/02
		  Based on the FAQ script of adgrafics
  		  Adjusted by Joeri Stegeman (joeri210 at yahoo.com), The Netherlands

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  */

  $dir_listing = array(array('id' => '', 'text' => TEXT_NONE));
  if ($dir = @dir(DIR_FS_CATALOG)) {
    while ($file = $dir->read()) {
      if (!is_dir($module_directory . $file)) {
        if (substr($file, strrpos($file, '.')) == '.php') {
          $dir_listing[] = array('id' => $file, 'text' => $file);
        }
      }
    }
    sort($dir_listing);
    $dir->close();
  }

?>
<link type="text/css" rel="stylesheet" href="external/tabpane/css/luna/tab.css" />
<script type="text/javascript" src="external/tabpane/js/tabpane.js"></script>
<tr>
  <td height=25 background="images/l_orange_bg.gif"><img src="images/spacer.gif" width="1"  height=1 alt="" border="0"></td>
</tr>
<tr class=pageHeading><td><?php echo $title ?></td></tr>
	<tr><td>

<div class="tab-pane" id="mainTabPane">
  <script type="text/javascript"><!--
    var mainTabPane = new WebFXTabPane( document.getElementById( "mainTabPane" ) );
  //--></script>
<?php
  $affiliates = tep_get_affiliates();
  $languages = tep_get_languages();
  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
    if ($adgrafics_information != 'Added') {
      $edit = read_data($information_id, $languages[$i]['id']);
    }
?>
      <div class="tab-page" id="tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
        <h2 class="tab"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], 18, 12) . '&nbsp;' . $languages[$i]['name']; ?></h2>

        <script type="text/javascript"><!--
        mainTabPane.addTabPage( document.getElementById( "tabDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
        //-->
        </script>  
        <table border="0" cellpadding="5" cellspacing="0">
<?php if ( !tep_session_is_registered('login_affiliate') ) { ?>
          <tr>
            <td class="main"><?php echo QUEUE_INFORMATION;?> </td>
            <td class="main"><?php if ($edit['v_order']) {$no=$edit['v_order'];}; echo tep_draw_input_field('v_order[' . $languages[$i]['id'] . ']', "$no", 'size=3 maxlength=4'); ?></td>
          </tr>
        <tr>
          <td class="main"><?php echo VISIBLE_INFORMATION;?></td>
          <td class="main">
            <?php
            if ($edit[visible]==1) {
              echo tep_image(DIR_WS_ICONS . 'icon_status_green.gif', INFORMATION_ID_ACTIVE); 
            }else{
              echo tep_image(DIR_WS_ICONS . 'icon_status_red.gif', INFORMATION_ID_DEACTIVE); 
            }
            ?>
            <?php $checked = '';if ($edit[visible]) {$checked= "checked";}; echo tep_draw_checkbox_field('visible[' . $languages[$i]['id'] . ']', '1', "$checked") . VISIBLE_INFORMATION_DO; ?>
          </td>
        </tr>
          <tr>
            <td class="main"><?php echo TITLE_PAGE_SCOPE;?></td>
            <td class="main">
              <table border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td class="main"><?php echo tep_draw_checkbox_field('scope[' . $languages[$i]['id'] . '][]', 'header', (strpos($edit['scope'], 'header') !== false)) . '&nbsp;' . TEXT_HEADER;?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo tep_draw_checkbox_field('scope[' . $languages[$i]['id'] . '][]', 'infobox', (strpos($edit['scope'], 'infobox') !== false)) . '&nbsp;' . TEXT_INFOBOX;?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo tep_draw_checkbox_field('scope[' . $languages[$i]['id'] . '][]', 'footer', (strpos($edit['scope'], 'footer') !== false)) . '&nbsp;' . TEXT_FOOTER;?></td>
                </tr>

              </table>
            </td>
          </tr>
          <tr>
            <td class="main"><?php echo TITLE_PAGE;?></td>
            <td class="main"><?php echo tep_draw_pull_down_menu('page[' . $languages[$i]['id'] . ']', $dir_listing, $edit['page']);?></td></td>
          </tr>
          <tr>
            <td class="main"><?php echo TITLE_PAGE_TYPE;?></td>
            <td class="main"><?php echo tep_draw_radio_field('page_type[' . $languages[$i]['id'] . ']', 'SSL', $edit['page_type'] == 'SSL') . '&nbsp;' . TEXT_SSL . '<br>' . tep_draw_radio_field('page_type[' . $languages[$i]['id'] . ']', 'NONSSL', ($edit['page_type'] == 'NONSSL' || $edit['page_type'] == '')) . '&nbsp;' . TEXT_NONSSL;?></td>
          </tr>
<?php }else{ ?>
        <?php if (!empty($edit['page'])) { ?>
          <tr>
            <td class="main"><?php echo TITLE_PAGE;?></td>
            <td class="main"><?php echo $edit['page'];?></td></td>
          </tr>
        <?php } ?>
<?php } ?>
          <tr>
            <td class="main" colspan="2">
          <?php
          if (count($affiliates) > 0) {
          ?>
          

    <div class="tab-pane" id="tabPaneDescriptionLanguages_<?php echo $languages[$i]['code']; ?>">
      <script type="text/javascript"><!--
      var tabPaneDescriptionLanguages_<?php echo $languages[$i]['code']; ?> = new WebFXTabPane( document.getElementById( "tabPaneDescriptionLanguages_<?php echo $languages[$i]['code']; ?>" ) );
      //-->
      </script>
      
      <?php if ( !tep_session_is_registered('login_affiliate') ) { ?> 
       <div class="tab-page" id="affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_0">
        <h2 class="tab"><?php echo TEXT_MAIN; ?></h2>

        <script type="text/javascript"><!--
        tabPaneDescriptionLanguages_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_0" ) );
        //-->
        </script>
          <?php
          }
          }
          if ( !tep_session_is_registered('login_affiliate') ) {          
          ?>
          <table border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="main"><?php echo TITLE_INFORMATION;?></td>
            <td class="main"><?php echo tep_draw_input_field('info_title[' . $languages[$i]['id'] . '][0]', "$edit[info_title]", 'maxlength=255'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TITLE_PAGE_TITLE;?></td>
            <td class="main"><?php echo tep_draw_input_field('page_title[' . $languages[$i]['id'] . '][0]', "$edit[page_title]", 'maxlength=255'); ?></td></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo DESCRIPTION_INFORMATION;?></td>
            <td class="main"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'edit_info\',\'description[' . $languages[$i]['id'] . '][0]\');"'); ?></legend><?php echo tep_draw_textarea_field('description[' . $languages[$i]['id'] . '][0]', '', '60', '10', "$edit[description]"); ?></fieldset></td>
          </tr>
          
          <tr>
            <td class="main" valign="top"><?php echo INFO_DESCRIPTION_META_TAG;?></td>
            <td class="main"><?php echo tep_draw_textarea_field('meta_description[' . $languages[$i]['id'] . ']', '', '60', '5', "$edit[meta_description]"); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo INFO_KEYWORDS;?></td>
            <td class="main"><?php echo tep_draw_textarea_field('meta_key[' . $languages[$i]['id'] . ']', '', '60', '5', "$edit[meta_key]"); ?></td>
          </tr>
          
          </table>
          <?php
          }
          if (count($affiliates) > 0) {

            if ( !tep_session_is_registered('login_affiliate') ) echo '</div>';

          for($j=0;$j<sizeof($affiliates);$j++) {
            if ( tep_session_is_registered('login_affiliate') && $affiliates[$j]['id']!=$login_id ) continue; 
            if ($information_id) {
              $edit = read_data($information_id, $languages[$i]['id'], $affiliates[$j]['id']);
            }
          ?>
        <div class="tab-page" id="affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>">
        <h2 class="tab"><?php echo $affiliates[$j]['name']; ?></h2>

        <script type="text/javascript"><!--
        tabPaneDescriptionLanguages_<?php echo $languages[$i]['code']; ?>.addTabPage( document.getElementById( "affiliateDescriptionTabPane_<?php echo $languages[$i]['code']; ?>_<?php echo $affiliates[$j]['id']?>" ) );
        //-->
        </script>
          <table border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td class="main"><?php echo TITLE_INFORMATION;?></td>
            <td class="main"><?php echo tep_draw_input_field('info_title[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', "$edit[info_title]", 'maxlength=255'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TITLE_PAGE_TITLE;?></td>
            <td class="main"><?php echo tep_draw_input_field('page_title[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', "$edit[page_title]", 'maxlength=255'); ?></td></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo DESCRIPTION_INFORMATION;?></td>
            <td class="main"><fieldset><legend><?php echo tep_image(DIR_WS_ICONS . 'icon_edit.gif', TEXT_OPEN_WYSIWYG_EDITOR, 16, 16, 'onclick="loadedHTMLAREA(\'edit_info\',\'description[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']\');"'); ?></legend><?php echo tep_draw_textarea_field('description[' . $languages[$i]['id'] . '][' . $affiliates[$j]['id'] . ']', '', '60', '10', "$edit[description]"); ?></fieldset></td>
          </tr>
          </table>
          </div>        
          
          <?php            
          }
          ?>            
</div>
          <?php
          }
          ?>
          </td>
          </tr>
                    
        </table>
       </div>
<?php
  }
?>       
</div>

</td>


<tr>
<td align=right>
<?php
echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
echo '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER, '', 'NONSSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
 ?>
</td>
</tr>
</table>
</form>
	</td></tr>