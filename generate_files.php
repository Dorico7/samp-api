<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// ============================================================
// CONFIGURACAO - Troque pela sua URL do Railway depois
// ============================================================
$base_url = getenv('RAILWAY_STATIC_URL') ?: 'SUA_URL_RAILWAY';
// ============================================================

$base_dir = __DIR__;

function listarArquivos($dir, $base_dir, $base_url) {
    $arquivos = [];
    if (!is_dir($dir)) return $arquivos;

    $itens = scandir($dir);
    foreach ($itens as $item) {
        if ($item === '.' || $item === '..') continue;

        // Ignora arquivos PHP e JSON de configuracao
        $ignorar = ['generate_files.php', 'players.php', 'client_config.json', 'news.json'];
        if (in_array($item, $ignorar)) continue;

        $caminho_completo = $dir . '/' . $item;
        $caminho_relativo = str_replace($base_dir . '/', '', $caminho_completo);

        if (is_dir($caminho_completo)) {
            $arquivos = array_merge(
                $arquivos,
                listarArquivos($caminho_completo, $base_dir, $base_url)
            );
        } else {
            $arquivos[] = [
                'name' => $item,
                'size' => filesize($caminho_completo),
                'path' => str_replace('\\', '/', $caminho_relativo),
                'url'  => $base_url . '/' . str_replace('\\', '/', $caminho_relativo)
            ];
        }
    }
    return $arquivos;
}

$arquivos = listarArquivos($base_dir, $base_dir, $base_url);

echo json_encode([
    'success' => true,
    'total'   => count($arquivos),
    'files'   => $arquivos
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
