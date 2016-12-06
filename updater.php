<?php
/**
 * A simple command line update script to update to latest stable release
 */

$streamOptions = [
    'http' => [
        'method' => "GET",
        "timeout" => 10,
        'header' => "Accept-language: en\r\nCookie: foo=bar\r\nUser-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n"
    ]
];

$context = stream_context_create($streamOptions);

// fetch release data
$apiData = file_get_contents("https://api.github.com/repos/brainfoolong/omxwebgui-v2/releases", null, $context);
if ($apiData) {
    $apiData = json_decode($apiData, true);

    $tmpFile = __DIR__ . "/tmp/update.zip";

    // download latest zipball
    file_put_contents(
        $tmpFile,
        file_get_contents(
            $apiData[0]["zipball_url"],
            null,
            $context
        )
    );

    // unpack and install
    if (file_exists($tmpFile)) {
        $updateFolder = __DIR__ . "/tmp/update";
        $cmd = "unzip -u -o " . escapeshellarg($tmpFile) . " -d " . escapeshellarg($updateFolder);
        // copy all files and folders
        $cmd .= " && cp -Rf " . $updateFolder . "/*/. " . escapeshellarg(__DIR__);
        // remove update folder
        $cmd .= " && rm -Rf " . escapeshellarg($updateFolder);
        // remove update.zip
        $cmd .= " && rm  " . escapeshellarg($tmpFile);
        exec($cmd);
    }
}