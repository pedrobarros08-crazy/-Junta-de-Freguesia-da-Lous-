<?php
require_once __DIR__ . '/loader.env.php';

// Verificar se a extensão SQLSRV está carregada
if (!extension_loaded('sqlsrv')) {
    die("Erro: A extensão SQLSRV não está instalada. Consulte o README.md para instruções de instalação.");
}

$serverName = getenv('DB_SERVER');
if (!$serverName) {
    die("Erro: variável de ambiente DB_SERVER não configurada. Consulte o ficheiro .env.example.");
}

$connectionOptions = array(
    "Database"     => getenv('DB_NAME') ?: '',
    "Uid"          => getenv('DB_USER') ?: '',
    "PWD"          => getenv('DB_PASSWORD') ?: '',
    "CharacterSet" => getenv('DB_CHARSET') ?: 'UTF-8',
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Falha na ligação à base de dados: " . htmlspecialchars($msg));
}
?>

