<?php
/*
  $Id: boxes.tpl.php,v 1.1.1.1 2005/12/03 21:36:13 max Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';
    var $table = '';

// class constructor
    function tableBox($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;
      $this->table = $tableBox_string;
      return $tableBox_string;
    }
  }

  class infoBox extends tableBox {
    function infoBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="infoBoxContentsLeft"', 'text' => '&nbsp;'),
                                   array('params' => 'class="infoBoxContentsCenter"', 'text' => $this->infoBoxContents($contents)),
                                   array('params' => 'class="infoBoxContentsRight"', 'text' => '&nbsp;'));
      $this->table_cellpadding = '0';
      $this->table_border = '0';
      $this->table_parameters = 'class="infoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_border = '0';
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(
                                     array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => (isset($contents[$i]['params']) ? $contents[$i]['params'] : ' width="100%"'),
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')),
                                     );
      }

      return $this->tableBox($info_box_contents);
    }
  }
  
  class infoBoxBanner extends tableBox{
    function infoBoxBanner($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => '', 'text' => tep_draw_separator("pixel_trans.gif", 5, 5) . '' . $this->infoBoxContents($contents) . '' . tep_draw_separator("pixel_trans.gif", 5, 5)));
      $this->table_cellpadding = '0';
      $this->table_border = '0';
      $this->table_parameters = '';
      $this->tableBox($info_box_contents, true);
    }
    
    function infoBoxContents($contents) {
      $this->table_border = '0';
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $info_box_contents = array();

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(
                                     array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => (isset($contents[$i]['params']) ? $contents[$i]['params'] : ' width="100%"'),
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')),
                                     );
      }

      return $this->tableBox($info_box_contents);
    }  
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';
      if ($right_arrow == true) {
        $arrow = '<a href="' . $right_arrow . '">' . $contents[0]['text']  . '</a>';
      } else {
        $arrow = $contents[0]['text'];
      }
      $this->table_parameters = 'class="infoBoxHeading"';
      echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBoxHeading">
        <tr>
          <td class="infoBoxHeadingLeft">&nbsp;</td>
          <td class="infoBoxHeadingCenter">' . $arrow . '</td>
          <td class="infoBoxHeadingRight">&nbsp;</td>
        </tr>
      </table>';
    }
  }

  class infoBox1Heading extends tableBox {
    function infoBox1Heading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';

      if ($right_arrow == true) {
        $corner_l = '<a href="' . $right_arrow . '">' . $contents[0]['text']  . '</a>';
      } else {
        $corner_l = $contents[0]['text'];
      }
      $corner_r = '&nbsp;';
      $this->table_parameters = 'class="infoBox1Heading"';
      echo '<table border="0" width="100%" cellspacing="0" cellpadding="0" class="infoBox1Heading">
        <tr>
          <td class="infoBox1HeadingLeft">&nbsp;</td>
          <td class="infoBox1HeadingCenter">' . $corner_l . '</td>
          <td class="infoBox1HeadingRight">&nbsp;</td>
        </tr>
      </table>';      
    }
  }
  
  class infoBox1 extends tableBox {
    function infoBox1($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="infoBox1ContentsLeft"', 'text' => '&nbsp;'),
                                   array('params' => 'class="infoBox1ContentsCenter"', 'text' => $this->infoBoxContents($contents)),
                                   array('params' => 'class="infoBox1ContentsRight"', 'text' => '&nbsp;'));
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="infoBox1"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_border = '0';
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="infoBox1Contents"';
      $info_box_contents = array();

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(
                                     array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => (isset($contents[$i]['params']) ? $contents[$i]['params'] : 'class="boxText" width="100%"'),
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')),
                                     );
      }

      return $this->tableBox($info_box_contents);
    }
  }  

  class infoBoxImageHeading extends tableBox {
    function infoBoxImageHeading($contents, $link = '') {
      $this->table_cellpadding = '0';

      $info_box_contents = array();
      if ($link != ''){
        $info_box_contents[] = array(
                                   array(
                                         'text' => '<a href="' . $link . '">' . $contents[0]['text'] . '</a>')
                                   );
      }else{
        $info_box_contents[] = array(
                                   array(
                                         'text' => $contents[0]['text'])
                                   );
      }

      $this->tableBox($info_box_contents, true);
    }
  }

  class infoboxFooter extends tableBox {
    function infoboxFooter($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';
      if ($left_corner) {
        $left_corner = tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif');
      } else {
        $left_corner = tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif');
      }
      if ($right_arrow) {
        $right_arrow = '<a class="infoBoxContents" href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . '/infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner) {
        $right_corner = $right_arrow . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif');
      } else {
        $right_corner = $right_arrow . tep_image(DIR_WS_TEMPLATE_IMAGES . 'pixel_trans.gif');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="infoBoxHeading"', 'text' => $left_corner),
                                   array('params' => ' width="100%" ', 'text' => $contents[0]['text']),
 						array('params' => ' class="infoBoxHeading" nowrap', 'text' => $right_corner));

      $this->tableBox($info_box_contents, true);
    }
  }
/*
  class contentBox extends tableBox {
    function contentBox($contents, $params='') {
      $info_box_contents = array();
      $this->table_border=0;
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents, $params));
      $this->table_cellpadding = '1';
      if ($params != ''){
        $this->table_parameters = $params . ' class="contentBox"';
      }else{
        $this->table_parameters = 'class="contentBox"';
      }
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents, $params = '') {
      $this->table_cellpadding = '4';
      $this->table_parameters = $params . ' class="contentBoxContents"';
      return $this->tableBox($contents);
    }
  }
*/  
  class contentBox extends tableBox {
    function contentBox($contents, $params = '') {
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="contentBoxContentsLeft"', 'text' => tep_draw_separator('spacer.gif', 1, 1)),
                                   array('params' => 'class="contentBoxContentsCenter"', 'text' => $this->contentBoxContents($contents)),
                                   array('params' => 'class="contentBoxContentsRight"', 'text' => tep_draw_separator('spacer.gif', 1, 1)));
      $this->table_cellpadding = '0';
      if ($params != ''){
        $this->table_parameters = $params . ' class="contentBox"';
      }else{
        $this->table_parameters = 'class="contentBox"';
      }      
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_border = '0';
      $this->table_cellpadding = '0';
      $this->table_parameters = 'class="contentBoxContents"';
      return $this->tableBox($contents);
    }
  }

   

  class buttonBox extends tableBox {
    function buttonBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->buttonBoxContents($contents));
      $this->table_border = '0';
      $this->table_cellpadding = '1';
      $this->table_parameters = 'class="contentboxfooter"';
      $this->tableBox($info_box_contents, true);
    }

    function buttonBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_border = '0';
      $this->table_parameters = '';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false, $output = true) {
      $this->table_width = '100%';
      $this->table_cellpadding = '0';
      $corner_l = '&nbsp;';
      /*
      if ($left_corner) {
        $corner_l = tep_image(DIR_WS_TEMPLATE_IMAGES . 'contentbox/cont_head_left.gif', '', '45', '27');
//        $corner_l = tep_image(DIR_WS_IMAGES. 'pixel_trans.gif');
      } else {
        $corner_l = tep_image(DIR_WS_TEMPLATE_IMAGES . 'contentbox/cont_head_left.gif', '', '45', '27');
//        $corner_l = tep_image(DIR_WS_IMAGES. 'pixel_trans.gif');
      }
      */
      if ($right_arrow) {
        $arrow = '<a class="contentBoxContents" href="' . $right_arrow . '">' . tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME .  '/images/contentbox/cb_h_arrow.gif', ICON_ARROW_RIGHT, '23', '23', 'class="transpng"') . '</a>';
      } else {
        $arrow = tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME .  '/images/contentbox/cb_h_arrow.gif', ICON_ARROW_RIGHT, '23', '23', 'class="transpng"');
      }
      $corner_l = $arrow;
      $corner_r = '&nbsp;';
      /*
      if ($right_corner) {
        $corner_r = $arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      } else {
        $corner_r = $arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }
      */
      /*
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="contentBoxHeadingLeft"',
                                         'text' => $corner_l),
                                   array('params' => 'class="contentBoxHeadingCenter"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'class="contentBoxHeadingRight" nowrap',
                                         'text' => $corner_r));

      return $this->tableBox($info_box_contents, $output);
      */
      $str = '<table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="contentBoxHeadingLeft">' . $corner_l . '</td>
                  <td class="contentBoxHeadingCenter">' . $contents[0]['text'] . '</td>
              
                  <td class="contentBoxHeadingRight" nowrap>' . $corner_r . '</td>
                </tr>
              </table>';
      if ($output){
        echo $str;
      }else{
        return $str;
      }
    }
  }

  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents) {
      $this->table_cellpadding = '0';
//      $this->table_border = 1;
//      $this->table_parameters = 'class="productListing"';
      $content = array();
      for ($i =0,$n=sizeof($contents);$i<$n;$i++){
        $content[$i] = array('params' => $contents[$i]['params']);
        for ($j=0,$m=sizeof($contents[$i]);$j<$m;$j++){
          $content[$i][] = array('params' => $contents[$i][$j]['params'] . ($i==0?($j==0?' id="firstHeadingSell"':($j==($m-1)?' id="lastHeadingSell"':'')):($j==0?' id="firstContentSell"':($j==($m-2)?' id="lastContentSell"':''))),
                                 'text' => $contents[$i][$j]['text'],
                                 'align' => $contents[$i][$j]['align']);
        }
      }
      $content[] = array(array('params' => 'colspan="'.($m+2).'" class="productListing-bottom"', 'text'=> '&nbsp;'));

      $this->tableBox($content, true);
//      $this->tableBox($contents, true);
    }
  }
  
  class productListingBox_index extends tableBox {
    function productListingBox_index($contents) {
//      $this->table_parameters = 'class="contentBoxContent"';
//      $this->tableBox($contents, true);
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->productListingBoxContents($contents));
      $this->table_border = '0';
      $this->table_cellpadding = '0';
      //$this->table_parameters = 'class="contentBox"';
      $this->tableBox($info_box_contents, true);
    }

    function productListingBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_cellspacing = '0';
      $this->table_border = '0';
      //$this->table_parameters = 'class="contentBoxContents"';
      return $this->tableBox($contents);
    }

  }

  class contentPageHeading extends tableBox {
    function contentPageHeading($contents) {
      $this->table_cellpadding = '0';
      $content = array();
      for ($i =0,$n=sizeof($contents);$i<$n;$i++){
        $content[$i] = array('params' => $contents[$i]['params']);
        for ($j=0,$m=sizeof($contents[$i]);$j<$m;$j++){
          $content[$i][] = array('params' => $contents[$i][$j]['params'] . ($j==0?' class="pageHeading"':''),
                                 'text' => $contents[$i][$j]['text'],
                                 'align' => $contents[$i][$j]['align']);
        }
      }
      $this->tableBox($content, true);
    }
  }


?>
