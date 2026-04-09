<?php
/**
 * Carregador de variáveis de ambiente a partir do ficheiro .env
 * Lê o ficheiro .env e define as variáveis com putenv() / $_ENV
 */

$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorar comentários
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value);
            // Remover aspas opcionais à volta do valor
            $value = trim($value, '"\'');
            if (!empty($name)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
            }
        }
    }
}
?>
