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
$data   = $_POST['data'];
$desc   = trim($_POST['descricao']);

// ✅ Validação de comprimento
if (strlen($desc) > 500) {
    die("Erro: Descrição muito longa (máximo 500 caracteres).");
}

// ✅ Prepared Statement com SQLSRV (Proteção contra SQL Injection)
$sql    = "INSERT INTO historico_trabalhos (id_rua, data_trabalho, descricao_servico) VALUES (?, ?, ?)";
$params = array($id_rua, $data, $desc);
$stmt   = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro na preparação da query: " . htmlspecialchars($msg));
}

if (sqlsrv_execute($stmt)) {
    echo "<script>alert('Trabalho registado com sucesso!'); window.location.href='trabalhos.php';</script>";
} else {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    echo "Erro ao guardar: " . htmlspecialchars($msg);
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
