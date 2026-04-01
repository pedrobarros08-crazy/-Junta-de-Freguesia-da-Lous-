<?php
include 'config.php';

// ✅ Validação do ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    die("Erro: ID inválido");
}

// ✅ Prepared Statement
$stmt = $conn->prepare("SELECT * FROM historico_trabalhos WHERE id = ?");

if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Output escaping (proteção contra XSS)
while($row = $result->fetch_assoc()) {
    echo htmlspecialchars($row['descricao_servico']) . "<br>";
}

$stmt->close();
$conn->close();
?>