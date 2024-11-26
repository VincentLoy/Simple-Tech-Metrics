<?php

function simple_tech_metrics_calculate_folder_size($folder) {
    $size = 0;

    if (!is_dir($folder)) {
        return $size;
    }

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
    foreach ($files as $file) {
        if ($file->isFile()) {
            $size += $file->getSize();
        }
    }

    return size_format($size, 2); // Taille formatée (exemple : "1.5 MB")
}
