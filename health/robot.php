<?php
require('includes/application_top.php');

header("Content-type: image/jpeg");

$ran = "";
$random = 7;
$counts = $random;

// Create new image
$width = $random * 32;
$img = imagecreatetruecolor ($width, 54) or die ("Image create error");

// Image loag for backgound image
$tile = imagecreatefromgif('images/metka.gif');

$chars = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
$count = count($chars)-1;
ImageCopyResized($img, $tile, 0, 0, 0, 0, $width+1, 55, ImageSX($tile), ImageSY($tile));

$double_size = 8;
for($i = 0; $i < $counts; $i++)
{
  $size = rand(24,26);
  $angle = rand(-10,10);
  $color = imagecolorallocate($img, rand(0,99), rand(0,99), rand(0,99));

  $random = $chars[rand(0, $count)];
  $ran = $ran . $random;

  $tile = @imagecreatefromgif('images/metka.gif');
  imagettftext($img, $size, $angle, $double_size, 37, $color, 'images/times.ttf',  $random);
  $double_size = $double_size + 31;
}

global $random;
$random = $ran;
tep_session_register('random');
$HTTP_SESSION_VARS['random'] = $random;

// Function for paint over image $img
// IMG_COLOR_TILED - is special color. The use for paint over image and make background image
imagefill($img, 1, 1, IMG_COLOR_TILED);

// Save image
imagejpeg($img);
?>