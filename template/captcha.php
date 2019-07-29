<?php
include_once('../config.php');

define('SELF_FILE', __FILE__);

$image = imagecreatetruecolor(100, 38);


$bgcolor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgcolor);
$captch_code = '';

for ($i = 0; $i < 4; $i++) {
    $fontsize = 10;  //
    $fontcolor = imagecolorallocate($image, rand(0, 120), rand(0, 120), rand(0, 120));//??????
    $data = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $fontcontent = substr($data, rand(0, strlen($data)), 1);
    $captch_code .= $fontcontent;
    $x = ($i * 100 / 4) + rand(5, 10);
    $y = rand(5, 10);
    imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);
}

$_SESSION['captcha_code'] = strtolower($captch_code);


for ($i = 0; $i < 200; $i++) {
    $pointcolor = imagecolorallocate($image, rand(50, 200), rand(50, 200), rand(50, 200));
    imagesetpixel($image, rand(1, 99), rand(1, 29), $pointcolor);//
}


for ($i = 0; $i < 3; $i++) {
    $linecolor = imagecolorallocate($image, rand(80, 280), rand(80, 220), rand(80, 220));
    imageline($image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $linecolor);
}


header('content-type: image/png');
imagepng($image);


imagedestroy($image);

?>