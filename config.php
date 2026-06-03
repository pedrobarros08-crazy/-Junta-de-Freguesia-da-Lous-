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

$serverName = getenv('DB_SERVER') ?: 'JFLVILARINHO\SQLEXPRESS';
if (!$serverName) {
    die("Erro: variável de ambiente DB_SERVER não configurada. Consulte o ficheiro .env.example.");
}

$connectionOptions = [
    "Database"     => getenv('DB_NAME') ?: 'ACCESS APLICAÇÃO',
    "Uid"          => getenv('DB_USER') ?: 'Aplicação User',
    "PWD"          => getenv('DB_PASSWORD') ?: 'JFLousan#2026',
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
