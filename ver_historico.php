<?php
include 'config.php';

// ✅ Validação do ID da rua (parâmetro GET)
if (!isset($_GET['id_rua']) || empty($_GET['id_rua']) || !is_numeric($_GET['id_rua'])) {
    die("Erro: ID de rua inválido.");
}

$id_rua = intval($_GET['id_rua']);

// ✅ Prepared Statement com SQLSRV para evitar SQL Injection
$sql    = "SELECT h.id, h.data_trabalho, h.descricao_servico, r.nome_rua, r.localidade
           FROM historico_trabalhos h
           JOIN ruas r ON h.id_rua = r.id
           WHERE h.id_rua = ?
           ORDER BY h.data_trabalho DESC";
$params = array($id_rua);
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

echo "<table style='border-collapse: collapse; width: 100%;'>
        <tr style='background-color: #333; color: white;'>
            <th style='padding: 10px; border: 1px solid #ddd;'>Localidade</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Rua</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Descrição</th>
        </tr>";

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_trabalho'] instanceof DateTime
        ? $row['data_trabalho']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_trabalho']));
    // ✅ Output escaping (proteção contra XSS)
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($row['localidade']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['nome_rua']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($data) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['descricao_servico']) . "</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='4' style='padding: 10px; text-align: center;'>Sem registos de trabalho.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
