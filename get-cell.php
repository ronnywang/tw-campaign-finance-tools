<?php

$dir = $_SERVER['argv'][1];
if (!is_dir($dir)) {
    throw new Exception("Usage: php get-cell.php [dir]");
}

include(__DIR__ . '/TableScanner.php');

foreach (array_merge(glob($dir . '/*.jpg'), glob($dir . '/*.png')) as $image_file) {
    clearstatcache(true);
    if (file_exists("{$image_file}.json") and filesize("{$image_file}.json") < 10000) {

        continue;
    }
    if (strpos($image_file, '-debug.') or strpos($image_file, '-part')) {
        continue;
    }
    touch("{$image_file}.json");
    $title = basename($image_file);
    fwrite(STDERR, chr(27) . "k{$title}" . chr(27) . "\\");
    error_log($image_file);
    $scanner = new TableScanner;
    list($obj, $gd)= $scanner->getCellsFromFile($image_file, true);
    file_put_contents("{$image_file}.json", json_Encode($obj));
    imagepng($gd, "{$image_file}-debug.png");
}
