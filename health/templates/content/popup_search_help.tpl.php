<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_SEARCH_HELP);


  new contentBoxHeading($info_box_contents, true, true);
  $info_box_contents = array();
  $info_box_contents[] = array('text' => TEXT_SEARCH_HELP);

  new contentBox($info_box_contents);
?>

<p class="smallText" align="right"><a href="javascript:window.close()"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
