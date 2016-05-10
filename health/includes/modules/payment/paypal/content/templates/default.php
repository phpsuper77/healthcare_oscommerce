<?php
/*
  $Id: default.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo $page->metaTitle; ?></title>
<?php echo $page->css() . $page->javascript(); ?>
</head>
<body onLoad="<?php echo $page->onLoad; ?>">
<table border="0" cellspacing="2" cellpadding="2" class="main" align="center" style="background: #ffffff url('<?php echo $page->baseURL.'images/logo.gif'; ?>') no-repeat top left;">
  <tr style="height: 55px; vertical-align: bottom;">
    <td class="ppheading" style="text-align: right;">&nbsp;<?php echo $page->pageTitle; ?>&nbsp;</td>
  </tr>
  <tr><td><hr class="solid"></td></tr>
  <tr>
    <td><?php if ($page->contentType == 'string') { echo $page->content; } else { include( $page->contentFile ); } ?></td>
  </tr>
  <tr>
    <td><?php echo $page->copyright(); ?></td>
  </tr>
</table>
</body>
</html>