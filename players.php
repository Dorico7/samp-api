<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

// ============================================================
// CONFIGURACAO DO SERVIDOR
// ============================================================
define('SERVER_IP',    '135.148.164.122');
define('SERVER_PORT',  16579);
define('CACHE_FILE',   __DIR__ . '/cache_players.json');
define('CACHE_EXPIRY', 10); // segundos
// ============================================================

function querySampServer($ip, $port) {
    $socket = @fsockopen('udp://' . $ip, $port, $errno, $errstr, 2);
    if (!$socket) return null;

    stream_set_timeout($socket, 2);

    $parts      = explode('.', $ip);
    $portPacked = pack('v', $port);

    $packet  = 'SAMP';
    $packet .= chr((int)$parts[0]);
    $packet .= chr((int)$parts[1]);
    $packet .= chr((int)$parts[2]);
    $packet .= chr((int)$parts[3]);
    $packet .= $portPacked[0] . $portPacked[1];
    $packet .= 'i';

    $start = microtime(true);
    fwrite($socket, $packet);
    $response = @fread($socket, 2048);
    $ping = round((microtime(true) - $start) * 1000);
    fclose($socket);

    if (!$response || strlen($response) < 13) return null;

    $offset     = 11;
    $offset++;                  // isPassword
    $players    = unpack('v', substr($response, $offset, 2))[1]; $offset += 2;
    $maxPlayers = unpack('v', substr($response, $offset, 2))[1]; $offset += 2;

    return [
        'players'    => (int) $players,
        'maxplayers' => (int) $maxPlayers,
        'ping'       => (int) $ping
    ];
}

function getCachedData() {
    if (!file_exists(CACHE_FILE)) return null;
    $age = time() - filemtime(CACHE_FILE);
    if ($age > CACHE_EXPIRY) return null;
    $data = @file_get_contents(CACHE_FILE);
    return $data ? json_decode($data, true) : null;
}

function saveCache($data) {
    @file_put_contents(CACHE_FILE, json_encode($data));
}

function buildResponse($players, $ping) {
    return [
        'servers' => [[
            'players1' => (string) $players,
            'ping'     => (int)    $ping,
            'doubling' => 0,
            'new'      => 0
        ]]
    ];
}

// Tenta usar cache primeiro
$cached = getCachedData();
if ($cached) {
    echo json_encode($cached, JSON_UNESCAPED_UNICODE);
    exit;
}

// Consulta servidor SAMP
$result = querySampServer(SERVER_IP, SERVER_PORT);

if ($result) {
    $response = buildResponse($result['players'], $result['ping']);
} else {
    $response = buildResponse(0, 999);
}

saveCache($response);
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
