<?php
/*
  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2004 AuctionBlox

  Released under the GNU General Public License
*/
?>
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_AUCTION,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=auctionblox'));

  if ($selected_box == 'auctionblox') {
    $contents[] = array('text'  => tep_admin_files_boxes('auctionblox.php', BOX_HEADING_AUCTION));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
