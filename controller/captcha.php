<?php

// We start a session to access
// the captcha externally!
session_start();


$random_num    = md5(random_bytes(64));
$captcha_code  = substr($random_num, 0, 6);

// The captcha will be stored
// for the session
$_SESSION["captcha"] = $captcha_code;

// Generate a 50x24 standard captcha image
$im = imagecreatetruecolor(100, 30);

// Blue color
$bg = imagecolorallocate($im, 22, 86, 165);

// White color
$fg = imagecolorallocate($im, 255, 255, 255);

// Give the image a blue background
imagefill($im, 0, 0, $bg);

// Print the captcha text in the image
// with random position & size
imagestring(
    $im,
    rand(5, 7),
    rand(1, 7),
    rand(1, 7),
    $captcha_code,
    $fg
);

// VERY IMPORTANT: Prevent any Browser Cache!!
header("Cache-Control: no-store, no-cache, must-revalidate");

// The PHP-file will be rendered as image
header('Content-type: image/png');

// Finally output the captcha as
// PNG image the browser
imagepng($im);

// Free memory
imagedestroy($im);
