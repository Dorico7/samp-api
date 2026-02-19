<?php
header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'status'  => 'online',
    'api'     => 'SAMP Launcher API',
    'version' => '1.0',
    'endpoints' => [
        '/players.php'          => 'Players online no servidor',
        '/client_config.json'   => 'Configuracao de update do APK',
        '/generate_files.php'   => 'Lista de arquivos para download',
        '/news.json'            => 'Noticias do launcher'
    ]
]);
?>
