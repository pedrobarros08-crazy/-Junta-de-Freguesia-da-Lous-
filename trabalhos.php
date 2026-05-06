<?php
include 'config.php';

// Carregar localidades da base de dados
$sqlLocalidades = "SELECT id, nome FROM localidades ORDER BY nome";
$resLocalidades = sqlsrv_query($conn, $sqlLocalidades);
$localidades = [];
if ($resLocalidades !== false) {
    while ($row = sqlsrv_fetch_array($resLocalidades, SQLSRV_FETCH_ASSOC)) {
        $localidades[(int)$row['id']] = $row['nome'];
    }
}

$localidadeId = isset($_GET['localidade_id']) ? (int)$_GET['localidade_id'] : 0;
$localidadeSelecionada = isset($localidades[$localidadeId]) ? $localidades[$localidadeId] : '';

$status = isset($_GET['status']) ? $_GET['status'] : '';
$status = in_array($status, ['success', 'error'], true) ? $status : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

$historico = [];
if ($localidadeId > 0 && $localidadeSelecionada !== '') {
    $sql = "SELECT id, nome_rua, data_trabalho, tipo_trabalho, observacoes FROM trabalhos WHERE id_localidade = ? ORDER BY data_trabalho DESC, id DESC";
    $res = sqlsrv_query($conn, $sql, [$localidadeId]);
    if ($res !== false) {
        while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
            $historico[] = $row;
        }
    } else {
        error_log('trabalhos.php: sqlsrv_query falhou - ' . print_r(sqlsrv_errors(), true));
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Trabalhos - Junta de Freguesia</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 20px; color: #333; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 980px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        label { font-weight: bold; display: block; margin-top: 15px; margin-bottom: 5px; }
        select, input[type="text"], input[type="date"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { min-height: 80px; resize: vertical; }
        .btn-gravar { background-color: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; width: 100%; transition: 0.3s; }
        .btn-gravar:hover { background-color: #27ae60; }
        .btn-voltar { background-color: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; display: block; width: 100%; transition: 0.3s; }
        .btn-voltar:hover { background-color: #7f8c8d; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: 0.3s; }
        .btn-delete:hover { background-color: #c0392b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: white; }
        tbody tr:hover { background-color: #f9f9f9; }
        .error-message { color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: none; }
        .success-message { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: none; }
        .error-message.show, .success-message.show { display: block; }
        .helper { margin-top: 10px; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <h2>Registo de Trabalhos por Localidade</h2>

    <div class="error-message<?php echo $status === 'error' ? ' show' : ''; ?>">
        <?php echo $status === 'error' ? htmlspecialchars($message) : ''; ?>
    </div>
    <div class="success-message<?php echo $status === 'success' ? ' show' : ''; ?>">
        <?php echo $status === 'success' ? htmlspecialchars($message) : ''; ?>
    </div>

    <form method="GET">
        <label for="localidade_id">Localidade:</label>
        <select id="localidade_id" name="localidade_id" onchange="this.form.submit()" required>
            <option value="">Selecione a localidade</option>
            <?php foreach ($localidades as $id => $nome): ?>
                <option value="<?php echo $id; ?>"<?php echo $localidadeId === $id ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($nome); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($localidadeSelecionada === ''): ?>
        <p class="helper">Selecione uma localidade para consultar o histórico e registar novos trabalhos.</p>
    <?php else: ?>
        <h3 style="margin-top: 30px;">Novo Registo (<?php echo htmlspecialchars($localidadeSelecionada); ?>)</h3>
        <form action="gravar_trabalho.php" method="POST">
            <input type="hidden" name="localidade_id" value="<?php echo $localidadeId; ?>">

            <label for="nome_rua">Rua:</label>
            <input type="text" id="nome_rua" name="nome_rua" required maxlength="255">

            <label for="data_trabalho">Data:</label>
            <input type="date" id="data_trabalho" name="data_trabalho" value="<?php echo date('Y-m-d'); ?>" required>

            <label for="tipo_trabalho">Tipo de Trabalho:</label>
            <input type="text" id="tipo_trabalho" name="tipo_trabalho" required maxlength="255">

            <label for="observacoes">Observações:</label>
            <textarea id="observacoes" name="observacoes" maxlength="2000"></textarea>

            <button type="submit" class="btn-gravar">Gravar Trabalho</button>
        </form>

        <h3 style="margin-top: 30px;">Histórico de Trabalhos</h3>
        <table>
            <thead>
                <tr><th>Rua</th><th>Data</th><th>Tipo de Trabalho</th><th>Observações</th><th>Ações</th></tr>
            </thead>
            <tbody>
            <?php if (empty($historico)): ?>
                <tr><td colspan="5" style="text-align:center;">Sem registos para esta localidade.</td></tr>
            <?php else: ?>
                <?php foreach ($historico as $registo): ?>
                    <?php
                    if ($registo['data_trabalho'] instanceof DateTime) {
                        $data = $registo['data_trabalho']->format('d/m/Y');
                    } else {
                        $ts = strtotime((string) $registo['data_trabalho']);
                        $data = $ts !== false ? date('d/m/Y', $ts) : htmlspecialchars((string) $registo['data_trabalho']);
                    }
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($registo['nome_rua']); ?></td>
                        <td><?php echo htmlspecialchars($data); ?></td>
                        <td><?php echo htmlspecialchars($registo['tipo_trabalho']); ?></td>
                        <td><?php echo htmlspecialchars((string) $registo['observacoes']); ?></td>
                        <td>
                            <form method="POST" action="eliminar_trabalho.php" style="display:inline;" onsubmit="return confirm('Tem a certeza que deseja eliminar este registo?');">
                                <input type="hidden" name="trabalho_id" value="<?php echo $registo['id']; ?>">
                                <input type="hidden" name="localidade_id" value="<?php echo $localidadeId; ?>">
                                <button type="submit" class="btn-delete">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <button type="button" class="btn-voltar" onclick="window.location.href='index.html'">Voltar ao Menu Principal</button>
</div>
</body>
</html>
<?php sqlsrv_close($conn); ?>
