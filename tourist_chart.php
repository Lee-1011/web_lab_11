<?php
$conn = new mysqli("localhost", "root", "", "web_lab_11");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT category, amount FROM expenditure_tourists");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[$row['category']] = $row['amount'];
}

$width = 800;
$height = 500;
$image = imagecreate($width, $height);
$bg = imagecolorallocate($image, 255, 255, 255);
$barColor = imagecolorallocate($image, 52, 152, 219); // Blue
$textColor = imagecolorallocate($image, 0, 0, 0);
$lineColor = imagecolorallocate($image, 200, 200, 200);

// Title
$title = "Expenditure by Domestic Tourists (2011)";
imagestring($image, 5, ($width - strlen($title) * 9) / 2, 10, $title, $textColor);

// Axis Labels
$font = __DIR__ . "/arial.ttf"; // Font file must be present in the same directory
imagettftext($image, 12, 90, 30, $height / 2 + 50, $textColor, $font, "Expenditure (RM million)");
imagestring($image, 3, $width / 2 - 50, $height - 20, "Expenditure Categories", $textColor);

// Bar Chart
$barWidth = 40;
$gap = 30;
$x = 100;
$maxValue = max($data);
$scale = 300 / $maxValue;

// Horizontal grid lines and values
for ($i = 0; $i <= 5; $i++) {
    $y = $height - 50 - ($i * 60);
    imageline($image, 80, $y, $width - 50, $y, $lineColor);
    imagestring($image, 3, 40, $y - 7, (int)($i * $maxValue / 5), $textColor);
}

// Bars and rotated labels
foreach ($data as $label => $value) {
    $barHeight = $value * $scale;
    imagefilledrectangle($image, $x, $height - $barHeight - 50, $x + $barWidth, $height - 50, $barColor);
    imagestring($image, 2, $x + 5, $height - $barHeight - 65, $value, $textColor);
    // Shorten label if needed
    $shortLabel = strlen($label) > 15 ? substr($label, 0, 12) . "..." : $label;
    imagettftext($image, 10, 45, $x - 5, $height - 35, $textColor, $font, $shortLabel);
    $x += $barWidth + $gap;
}

header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);
$conn->close();
?>
