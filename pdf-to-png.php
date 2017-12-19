<?php

$file = $_SERVER['argv'][1];
$col = $_SERVER['argv'][2];
$row = $_SERVER['argv'][3];

if (!file_exists($file)) {
    throw new Exception("Usage: php pdf-to-png.php [pdf file]");
}

$target_path = $file . '.png/';
if (file_exists($target_path)) {
    throw new Exception("{$target_path} 已經存在，無法成功繼續");
}
mkdir($target_path);

system(sprintf("pdfimages %s %s", escapeshellarg($file), escapeshellarg($target_path . 'png')));

foreach (glob($target_path . 'png-*.pbm') as $pbm_file) {
    $png_file = preg_replace('#\.pbm$#', '.png', $pbm_file);
    if ($row and $col) {
        $width = 100 / $col;
        $height = 100 / $row;
        for ($c = 0; $c < $col; $c ++) {
            for ($r = 0; $r < $row; $r ++) {
                $cmd = (sprintf("convert -crop %.2f%%x%.2f%%+%.2f%%+%.2f%% %s %s",
                    $width,
                    $height,
                    floatval($c * $width),
                    floatval($r * $height),
                    escapeshellarg($pbm_file),
                    escapeshellarg("{$png_file}-{$c}-{$r}.png")
                ));
                system($cmd);
            }
        }
    } else {
        system(sprintf("convert %s %s", escapeshellarg($pbm_file), escapeshellarg($png_file)));
    }
}
