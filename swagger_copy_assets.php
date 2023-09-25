<?php

$vendorDir = __DIR__ . '/vendor/swagger-api/swagger-ui/dist';
$publicDir = __DIR__ . '/public/documentation';

// Reset the documentation folder
if (is_dir($publicDir)) {
    array_map('unlink', glob("$publicDir/*.*"));
    rmdir($publicDir);
}

// Copy files from vendor Swagger
if (is_dir($vendorDir)) {
    $files = array_diff(scandir($vendorDir), ['.', '..']);

    // Do nothing if no files to copy
    if (count($files) < 1) {
        die();
    }

    mkdir($publicDir);

    foreach ($files as $file) {
        $sourceFile = $vendorDir . '/' . $file;
        $destinationFile = $publicDir . '/' . $file;
        copy($sourceFile, $destinationFile);
    }
} else {
    echo "Swagger Dist folder not found!\n";
}

echo "Swagger UI assets copied successfully!\n";
