<?php
include 'config.php';

$localidadeId = isset($_GET['localidade_id']) ? (int)$_GET['localidade_id'] : 0;

// Validar localidade na base de dados
$sqlCheck = "SELECT id, nome FROM localidades WHERE id = ?";
$stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [$localidadeId]);
if ($stmtCheck === false || !sqlsrv_execute($stmtCheck)) {
    die("Erro: Localidade inválida.");
}
$localidadeRow = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);
if (!$localidadeRow) {
    die("Erro: Localidade inválida.");
}

$sql  = "SELECT id, nome_rua, data_trabalho, tipo_trabalho, observacoes FROM trabalhos WHERE id_localidade = ? ORDER BY data_trabalho DESC, id DESC";
$params = [$localidadeId];
$stmt = sqlsrv_prepare($conn, $sql, $params);

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
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Trabalhos - <?php echo htmlspecialchars($localidadeRow['nome']); ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 20px; color: #333; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 980px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; margin-top: 0; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        tr { border-bottom: 1px solid #ddd; }
        th { background-color: #333; color: white; padding: 10px; text-align: left; font-weight: bold; }
        td { padding: 10px; }
        tbody tr:hover { background-color: #f9f9f9; }
        .btn-voltar { background-color: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; transition: 0.3s; }
        .btn-voltar:hover { background-color: #7f8c8d; }
    </style>
</head>
<body>
<div class="container">
    <h2>Histórico de Trabalhos - <?php echo htmlspecialchars($localidadeRow['nome']); ?></h2>
    
    <table>
        <thead>
            <tr>
                <th>Rua</th>
                <th>Data</th>
                <th>Tipo de Trabalho</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $temRegistos = false;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $temRegistos = true;
            $data = $row['data_trabalho'] instanceof DateTime
                ? $row['data_trabalho']->format('d/m/Y')
                : date('d/m/Y', strtotime((string)$row['data_trabalho']));
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nome_rua']); ?></td>
                <td><?php echo htmlspecialchars($data); ?></td>
                <td><?php echo htmlspecialchars($row['tipo_trabalho']); ?></td>
                <td><?php echo htmlspecialchars((string) $row['observacoes']); ?></td>
            </tr>
            <?php
        }

        if (!$temRegistos) {
            echo "<tr><td colspan='4' style='text-align: center;'>Sem registos de trabalho para esta localidade.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    
    <button type="button" class="btn-voltar" onclick="window.history.back()">Voltar</button>
</div>
</body>
</html>
<?php
sqlsrv_free_stmt($stmt);
sqlsrv_free_stmt($stmtCheck);
sqlsrv_close($conn);
?>
