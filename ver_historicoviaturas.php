<?php
include 'config.php';

// ✅ Validação do ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Erro: ID da viatura inválido.");
}

$id_escolhido = intval($_GET['id']);

// ✅ Prepared Statement
$stmt = $conn->prepare("SELECT * FROM manutencoes WHERE id_viatura = ? ORDER BY data_servico DESC");

if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("i", $id_escolhido);
$stmt->execute();
$result = $stmt->get_result();

echo "<table style='border-collapse: collapse; width: 100%;'>
        <tr style='background-color: #333; color: white;'>
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Trabalho</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Fornecedor</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Kms</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Custo</th>
        </tr>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // ✅ Output escaping (proteção contra XSS)
        echo "<tr style='border-bottom: 1px solid #ddd;'>
                <td style='padding: 10px;'>" . htmlspecialchars($row['data_servico']) . "</td>
                <td style='padding: 10px;'>" . htmlspecialchars($row['descricao']) . "</td>
                <td style='padding: 10px;'>" . htmlspecialchars($row['fornecedor']) . "</td>
                <td style='padding: 10px;'>" . htmlspecialchars($row['kms']) . " km</td>
                <td style='padding: 10px;'>" . htmlspecialchars(number_format($row['custo'], 2, ',', '.')) . " €</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5' style='padding: 10px; text-align: center;'>Sem registos de manutenção.</td></tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>