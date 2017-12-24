<?php

$dir = $_SERVER['argv'][1];
if (!is_dir($dir)) {
    throw new Exception("Usage: php cut.php [dir]");
}

foreach (glob($dir . '/*.png') as $png_file) {
    if (strpos($png_file, '-cell.png')) {
        continue;
    }
    if (!file_exists($png_file . '.json')) {
        continue;
    }

    error_log($png_file);
    $json = json_decode(file_get_contents("{$png_file}.json"));
    if (is_null($json)) {
        continue;
    }
    $tables = $json->cross_points;

    $rows = count($tables) - 1;
    $cols = count($tables[0]) - 1;
    error_log("rows={$rows}, cols={$cols}");
    exit;
    $scale = 1;
    $gd = imagecreatefrompng($png_file);

    for ($i = 0; $i < $rows; $i ++) {
        for ($j = 0; $j < $cols; $j ++) {
            if ($i != 0 and $j != 0) {
                continue;
            }
            $path = "{$png_file}-{$i}-{$j}-cell.png";

            $rect = array(
                'x' => intval($tables[$i][$j][0] * $scale),
                'y' => intval($tables[$i][$j][1] * $scale),
                'width' => intval(($tables[$i+1][$j+1][0] - $tables[$i][$j][0]) * $scale),
                'height' => intval(($tables[$i+1][$j+1][1] - $tables[$i][$j][1]) * $scale),
            );
            $crop = imagecrop($gd, $rect);
            imagepng($crop, $path);
            imagedestroy($crop);
        }
    }
}

