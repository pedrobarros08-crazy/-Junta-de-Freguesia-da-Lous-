<?php
include 'config.php';  // ✅ Mudado de config_viaturas.php para config.php

// ✅ Validação de dados
$id_rua = isset($_POST['id_rua']) ? intval($_POST['id_rua']) : 0;
$descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
$data = isset($_POST['data']) ? $_POST['data'] : date('Y-m-d');

// ✅ Verificar se data é válida
if (!strtotime($data)) {
    die("Data inválida.");
}

// ✅ Verificar campos obrigatórios
if ($id_rua <= 0) {
    die("Rua não selecionada.");
}

if (empty($descricao)) {
    die("Descrição do trabalho obrigatória.");
}

if (strlen($descricao) > 500) {
    die("Descrição muito longa (máximo 500 caracteres).");
}

// ✅ Prepared statement para evitar SQL Injection
$stmt = $conn->prepare("INSERT INTO historico_trabalhos (id_rua, data_trabalho, descricao_servico) 
                       VALUES (?, ?, ?)");

if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("iss", $id_rua, $data, $descricao);

if ($stmt->execute()) {
    echo "<script>alert('Trabalho registado com sucesso!'); window.location.href='trabalhos.php';</script>";
} else {
    echo "Erro ao guardar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>