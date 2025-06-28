<?php
// Pie chart for domestic tourists 2011
$data = [
    "Shopping" => 13149,
    "Transport" => 10019,
    "Food & beverages" => 9691,
    "Accommodation" => 5028,
    "Packages/Tickets" => 1097,
    "Others" => 3362
];

$total = array_sum($data);

// Create image
$width = 600;
$height = 600;
$image = imagecreate($width, $height);
$bg = imagecolorallocate($image, 255, 255, 255);

// Colors for slices
$colors = [
    imagecolorallocate($image, 255, 99, 132),
    imagecolorallocate($image, 54, 162, 235),
    imagecolorallocate($image, 255, 206, 86),
    imagecolorallocate($image, 75, 192, 192),
    imagecolorallocate($image, 153, 102, 255),
    imagecolorallocate($image, 255, 159, 64)
];

$black = imagecolorallocate($image, 0, 0, 0);
$font = __DIR__ . "/arial.ttf"; // font file path

$startAngle = 0;
$i = 0;

// Draw Pie Slices and Percentages
foreach ($data as $label => $value) {
    $angle = ($value / $total) * 360;
    $endAngle = $startAngle + $angle;
    imagefilledarc($image, 300, 300, 400, 400, $startAngle, $endAngle, $colors[$i], IMG_ARC_PIE);

    // Calculate percentage
    $percentage = round(($value / $total) * 100, 1) . "%";

    // Calculate label position
    $theta = deg2rad(($startAngle + $endAngle) / 2);
    $labelX = 300 + cos($theta) * 130;
    $labelY = 300 + sin($theta) * 130;

    // Add percentage text
    imagettftext($image, 10, 0, $labelX - 10, $labelY, $black, $font, $percentage);

    $startAngle = $endAngle;
    $i++;
}

// Title
$title = "Expenditure by Domestic Visitors (2011)";
imagettftext($image, 14, 0, ($width - 400) / 2, 30, $black, $font, $title);

// Add legend
$x = 20;
$y = 50;
$i = 0;
foreach ($data as $label => $value) {
    imagefilledrectangle($image, $x, $y, $x + 15, $y + 15, $colors[$i]);
    imagettftext($image, 10, 0, $x + 20, $y + 13, $black, $font, "$label ($value)");
    $y += 20;
    $i++;
}

header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
?>
