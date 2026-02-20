<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$files = [
    [
        "name" => "arqivos sem mod.zip",
        "size" => 355619057,
        "path" => "files",
        "url" => "https://drive.google.com/uc?export=download&id=1f1czXumJW3q6OAgtmp6XuOFyVAplVRVr"
    ]
];

echo json_encode([
    "success" => true,
    "total" => 1,
    "files" => $files
]);
?>
