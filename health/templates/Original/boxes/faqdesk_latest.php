<?php
/*

  osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
  Copyright (c) 2002 osCommerce
  Released under the GNU General Public License

  IMPORTANT NOTE:

  This script is not part of the official osC distribution but an add-on contributed to the osC community.
  Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

  script name:  FaqDesk
  version:    1.3.0
  date:      2003-09-01

*/
?>

<?php


if ( DISPLAY_LATEST_FAQS_BOX ) {
?>
<!-- faqdesk //-->
  <tr>
    <td class="infoBoxCell">
<?php

// set application wide parameters
// this query set is for FAQDesk

$latest_news_var_query = tep_db_query('select p.faqdesk_id, pd.language_id, pd.faqdesk_question, pd.faqdesk_answer_long, pd.faqdesk_answer_short, pd.faqdesk_extra_url, p.faqdesk_image, p.faqdesk_date_added, p.faqdesk_last_modified, p.faqdesk_date_available, p.faqdesk_status  from ' . TABLE_FAQDESK . ' p, ' . TABLE_FAQDESK_DESCRIPTION . ' pd WHERE pd.faqdesk_id = p.faqdesk_id and pd.language_id = "' . (int)$languages_id . '" and faqdesk_status = 1 ORDER BY faqdesk_date_added DESC LIMIT ' . LATEST_DISPLAY_FAQDESK_FAQS);


if (tep_db_num_rows($latest_news_var_query)) {
  if (is_file(DIR_FS_CATALOG . '/' . DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg')){
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => tep_image(DIR_WS_TEMPLATE_IMAGES . 'infoboxheading/' . $infobox_id . '_' . $languages_id . '.jpg'));
    new infoBoxImageHeading($info_box_contents);
  }else{
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => BOX_HEADING_FAQDESK_LATEST );
    $infoboox_class_heading = $infobox_class . 'Heading';
    if (class_exists($infoboox_class_heading)){
      new $infoboox_class_heading($info_box_contents, false, false);
    }else{
      new infoBoxHeading($info_box_contents, false, false);
    }    
  }

  $info_box_contents = array();
  while ($latest_news = tep_db_fetch_array($latest_news_var_query))  {
    $info_box_contents[] = array(
    'params' => 'valign="top"',
    'text' => '<a class="infoBoxLink" href="' . tep_href_link(FILENAME_FAQDESK_INFO, 'faqdesk_id=' . $latest_news['faqdesk_id']) . '">' . $latest_news['faqdesk_question'] . '</a>');
  }
  if (class_exists($infobox_class)){
    new $infobox_class($info_box_contents);
  }else{
    new infoBox($info_box_contents);
  }
}
?>

<!-- faqdesk_eof //-->
    </td>
  </tr>

<?php
}
?>