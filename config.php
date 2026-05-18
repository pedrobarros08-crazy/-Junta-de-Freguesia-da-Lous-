<?php
require_once __DIR__ . '/security.php';

// Verificar se a extensão SQLSRV está carregada
if (!extension_loaded('sqlsrv')) {
    die("Erro: A extensão SQLSRV não está instalada. Consulte o README.md para instruções de instalação.");
}

$serverName = getenv('DB_SERVER');
if (!$serverName) {
    die("Erro: variável de ambiente DB_SERVER não configurada. Consulte o ficheiro .env.example.");
}

$connectionOptions = [
    "Database"     => getenv('DB_NAME') ?: '',
    "Uid"          => getenv('DB_USER') ?: '',
    "PWD"          => getenv('DB_PASSWORD') ?: '',
    "CharacterSet" => getenv('DB_CHARSET') ?: 'UTF-8',
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    $errors = sqlsrv_errors();
    error_log('Erro na ligação à base de dados: ' . print_r($errors, true));
    $msg = is_production()
        ? 'Erro interno ao ligar à base de dados.'
        : ('Erro na ligação à base de dados: ' . (isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido'));
    die(htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'));
}
?>
