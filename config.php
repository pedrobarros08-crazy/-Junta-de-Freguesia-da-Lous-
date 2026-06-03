<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/app_helpers.php';

// Mostrar erros em ambiente de desenvolvimento para facilitar depuração
if (!is_production()) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// Verificar se a extensão SQLSRV está carregada
if (!extension_loaded('sqlsrv')) {
    die("Erro: A extensão SQLSRV não está instalada. Consulte o README.md para instruções de instalação.");
}

$serverName = trim((string) getenv('DB_SERVER'));
$dbName = trim((string) getenv('DB_NAME'));
$dbUser = trim((string) getenv('DB_USER'));
$dbPassword = (string) getenv('DB_PASSWORD');
$dbCharset = trim((string) getenv('DB_CHARSET'));

if ($serverName === '' || $dbName === '' || $dbUser === '' || $dbPassword === '') {
    die("Erro: variáveis DB_SERVER, DB_NAME, DB_USER e DB_PASSWORD são obrigatórias. Consulte o ficheiro .env.example.");
}

if ($dbCharset === '') {
    $dbCharset = 'UTF-8';
}

$connectionOptions = [
    "Database"     => $dbName,
    "Uid"          => $dbUser,
    "PWD"          => $dbPassword,
    "CharacterSet" => $dbCharset,
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
