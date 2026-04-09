<?php
include 'config.php';

// ✅ Validação do ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    die("Erro: ID inválido");
}

// ✅ Prepared Statement com SQLSRV
$sql    = "SELECT * FROM historico_trabalhos WHERE id = ?";
$params = array($id);
$stmt   = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro na preparação da query: " . htmlspecialchars($msg));
}

if (!sqlsrv_execute($stmt)) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro ao executar a query: " . htmlspecialchars($msg));
}

// ✅ Output escaping (proteção contra XSS)
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo htmlspecialchars($row['descricao_servico']) . "<br>";
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
