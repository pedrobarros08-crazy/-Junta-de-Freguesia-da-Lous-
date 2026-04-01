<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root'; // padrão do XAMPP
$pass = getenv('DB_PASS') ?: '';     // padrão do XAMPP
$db   = getenv('DB_NAME') ?: 'gestao_junta';

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die("Falha na ligação: " . $conn->connect_error);
}
?>