<?php
header("content-type: image/jpeg");
session_start();
$secure = $_SESSION['capcha'];

$image_w = 100;
$image_h = 30;
$font_size = 22;

$image = imagecreate($image_w,$image_h);
imagecolorallocate($image,255,255,255);

$font_color = imagecolorallocate($image,255,0,0);


for($x=0;$x<50;$x++){
	$x1 = rand(0,100);
	$y1 = rand(0,100);
	$x2 = rand(0,100);
	$y2 = rand(0,100);
	
	$line_color = imagecolorallocate($image,$x1,$y2,$y1);
	imageline($image,$x1,$y1,$x2,$y2,$line_color);
}
imagettftext($image,$font_size,0,1,22,$line_color,'AIFusion.otf',$secure);


imagegif($image);


?>