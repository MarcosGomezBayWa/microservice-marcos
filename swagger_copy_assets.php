<?php

$vendorDir = __DIR__ . '/vendor/swagger-api/swagger-ui/dist';
$publicDir = __DIR__ . '/public/documentation';

// Remove the existing documentation folder
if (is_dir($publicDir)) {
    $files = glob("$publicDir/*.*");

    foreach ($files as $file) {
        if (basename($file) === 'index.html') continue;
        unlink($file);
    }
//    array_map('unlink', );
//    rmdir($publicDir);
}

// Copy files from vendor Swagger
if (is_dir($vendorDir)) {
    $files = array_diff(scandir($vendorDir), ['.', '..']);

    if (count($files) < 1) {
        die();
    }

//    mkdir($publicDir);

    foreach ($files as $file) {
        if (basename($file) === 'index.html') {
            continue; //No copy the index.html
        }
        $sourceFile = $vendorDir .'/'. $file;
        $destinationFile = $publicDir .'/'. $file;

        copy($sourceFile, $destinationFile);
    }
} else {
    echo "Swagger dist folder not found!\n";
}

echo "Swagger UI assets copied successfully!\n";