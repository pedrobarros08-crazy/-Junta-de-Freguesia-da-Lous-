<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "gestao_junta";

$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}
?>