<?php
include 'config.php';

// ✅ Validação de entrada
if (!isset($_POST['id_rua']) || empty($_POST['id_rua']) || !is_numeric($_POST['id_rua'])) {
    die("Erro: Rua não selecionada.");
}
if (!isset($_POST['data']) || empty($_POST['data']) || !strtotime($_POST['data'])) {
    die("Erro: Data inválida.");
}
if (!isset($_POST['descricao']) || empty($_POST['descricao'])) {
    die("Erro: Descrição do trabalho obrigatória.");
}

$id_rua = intval($_POST['id_rua']);
$data = $_POST['data'];
$desc = trim($_POST['descricao']);

// ✅ Validação de comprimento
if (strlen($desc) > 500) {
    die("Erro: Descrição muito longa (máximo 500 caracteres).");
}

// ✅ Prepared Statement (Proteção contra SQL Injection)
$stmt = $conn->prepare("INSERT INTO historico_trabalhos (id_rua, data_trabalho, descricao_servico) VALUES (?, ?, ?)");

if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("iss", $id_rua, $data, $desc);

if ($stmt->execute()) {
    echo "<script>alert('Trabalho registado com sucesso!'); window.location.href='trabalhos.php';</script>";
} else {
    echo "Erro ao guardar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>