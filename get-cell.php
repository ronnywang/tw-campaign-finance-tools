<?php

$dir = $_SERVER['argv'][1];
if (!is_dir($dir)) {
    throw new Exception("Usage: php get-cell.php [dir]");
}

include(__DIR__ . '/TableScanner.php');

foreach (glob($dir . '/*.png') as $png_file) {
    clearstatcache(true);
    if (file_exists("{$png_file}.json") and filesize("{$png_file}.json") < 10000) {

        continue;
    }
    if (strpos($png_file, '-debug.png')) {
        continue;
    }
    touch("{$png_file}.json");
    $title = basename($png_file);
    fwrite(STDERR, chr(27) . "k{$title}" . chr(27) . "\\");
    error_log($png_file);
    $scanner = new TableScanner;
    list($obj, $gd)= $scanner->getCellsFromFile($png_file, true);
    file_put_contents("{$png_file}.json", json_Encode($obj));
    imagepng($gd, "{$png_file}-debug.png");
}
