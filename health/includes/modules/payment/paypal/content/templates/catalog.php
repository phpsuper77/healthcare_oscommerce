<?php
/*
  $Id: catalog.php,v 1.1.1.1 2005/12/03 21:36:11 max Exp $

  AuctionBlox, sell more, work less!
  http://www.auctionblox.com

  Copyright (c) 2005 AuctionBlox

  Released under the GNU General Public License
*/
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<?php echo $page->css(); ?>
</head>
<body onLoad="<?php echo $page->onLoad; ?>">
<table border="0" cellspacing="2" cellpadding="2" class="main" align="center" style="background: #ffffff;">
  <tr>
    <td><?php if ($page->contentType == 'string') { echo $page->content; } else { include( $page->contentFile ); } ?></td>
  </tr>
  <tr><td><hr class="solid"></td></tr>
  <tr>
    <td class="buttontd"><form name="winClose"><input type="button" value="Close Window" onclick="window.close();return(false);" class="ppbuttonsmall"></form></td>
  </tr>
  <tr><td><br class="h10"></td></tr>
</table>
</body>
</html>
