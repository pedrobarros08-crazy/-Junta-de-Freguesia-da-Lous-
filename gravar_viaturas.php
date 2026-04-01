<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ Validação de entrada
    if (!isset($_POST['id_viatura']) || empty($_POST['id_viatura']) || !is_numeric($_POST['id_viatura'])) {
        die("Erro: Viatura não selecionada.");
    }
    if (!isset($_POST['data']) || empty($_POST['data']) || !strtotime($_POST['data'])) {
        die("Erro: Data inválida.");
    }
    if (!isset($_POST['trabalho']) || empty($_POST['trabalho'])) {
        die("Erro: Descrição do trabalho obrigatória.");
    }
    if (!isset($_POST['fornecedor']) || empty($_POST['fornecedor'])) {
        die("Erro: Fornecedor obrigatório.");
    }
    if (!isset($_POST['kms']) || !is_numeric($_POST['kms']) || $_POST['kms'] < 0) {
        die("Erro: Quilometragem inválida.");
    }
    if (!isset($_POST['preco']) || !is_numeric($_POST['preco']) || $_POST['preco'] < 0) {
        die("Erro: Preço inválido.");
    }

    $id_viatura = intval($_POST['id_viatura']);
    $data       = $_POST['data'];
    $descricao  = trim($_POST['trabalho']);
    $fornecedor = trim($_POST['fornecedor']);
    $kms        = intval($_POST['kms']);
    $custo      = floatval($_POST['preco']);

    // ✅ Prepared Statement (Proteção contra SQL Injection)
    $stmt = $conn->prepare("INSERT INTO manutencoes (id_viatura, data_servico, descricao, fornecedor, kms, custo) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Erro na preparação da query: " . $conn->error);
    }

    $stmt->bind_param("isssid", $id_viatura, $data, $descricao, $fornecedor, $kms, $custo);

    if ($stmt->execute()) {
        echo "<script>alert('Reparação gravada com sucesso!'); window.location.href='signin.php';</script>";
    } else {
        echo "Erro ao gravar: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>