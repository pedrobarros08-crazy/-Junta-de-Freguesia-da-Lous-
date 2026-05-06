<?php
include 'config.php';

// Carregar viaturas da base de dados
$sqlViaturas = "SELECT id, nome FROM viaturas ORDER BY nome";
$resViaturas = sqlsrv_query($conn, $sqlViaturas);
$viaturas = [];
if ($resViaturas !== false) {
    while ($row = sqlsrv_fetch_array($resViaturas, SQLSRV_FETCH_ASSOC)) {
        $viaturas[(int)$row['id']] = $row['nome'];
    }
}

$viaturaId = isset($_GET['viatura_id']) ? (int)$_GET['viatura_id'] : 0;
$viaturaValida = isset($viaturas[$viaturaId]);

$status = isset($_GET['status']) ? $_GET['status'] : '';
$status = in_array($status, ['success', 'error'], true) ? $status : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

$historico = [];
$despesaTotal = 0.0;
$taxaIva = 0.23;

if ($viaturaValida) {
    $sql = "SELECT id, data_servico, km, intervencao, valor, fornecedor FROM manutencoes_viaturas WHERE id_viatura = ? ORDER BY data_servico DESC, id DESC";
    $params = [$viaturaId];
    $res = sqlsrv_query($conn, $sql, $params);
    if ($res !== false) {
        while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
            $historico[] = $row;
            $despesaTotal += (float) $row['valor'];
        }
    } else {
        error_log('viaturas.php: sqlsrv_query falhou - ' . print_r(sqlsrv_errors(), true));
    }
}

$totalComIva = $despesaTotal * (1 + $taxaIva);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Viaturas - Junta de Freguesia</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 20px; color: #333; }
        .container { max-width: 1100px; margin: auto; }
        .box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 10px; }
        .btn-viatura { display: block; text-align: center; text-decoration: none; padding: 10px; border-radius: 6px; background-color: #e9ecef; color: #222; font-weight: 600; border: 1px solid #d3d9df; transition: 0.3s; }
        .btn-viatura:hover { background-color: #d3d9df; }
        .btn-viatura.active { background-color: #3498db; color: white; border-color: #3498db; }
        .status-error { color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .status-success { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #2c3e50; color: white; }
        label { display: block; margin-top: 10px; font-weight: 600; }
        input, textarea { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        textarea { min-height: 80px; resize: vertical; }
        .btn-submit { margin-top: 15px; width: 100%; padding: 10px; border: none; border-radius: 4px; background: #2ecc71; color: white; font-weight: 700; cursor: pointer; }
        .btn-submit:hover { background-color: #27ae60; }
        .btn-delete { background-color: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: 0.3s; }
        .btn-delete:hover { background-color: #c0392b; }
        .resumo { margin-top: 12px; display: flex; gap: 25px; flex-wrap: wrap; }
        .btn-voltar { background-color: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; display: block; width: 100%; transition: 0.3s; }
        .btn-voltar:hover { background-color: #7f8c8d; }
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <h2>Viaturas</h2>
        <?php if ($status === 'error'): ?><div class="status-error"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($status === 'success'): ?><div class="status-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

        <div class="grid">
            <?php foreach ($viaturas as $id => $nome): ?>
                <a class="btn-viatura<?php echo $viaturaId === $id ? ' active' : ''; ?>" href="viaturas.php?viatura_id=<?php echo $id; ?>">
                    <?php echo htmlspecialchars($nome); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="box">
        <h3>Histórico de Manutenções</h3>
        <?php if (!$viaturaValida): ?>
            <p>Selecione uma viatura para ver os registos.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>KM</th>
                    <th>Intervenção</th>
                    <th>Valor</th>
                    <th>Fornecedor</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($historico)): ?>
                    <tr><td colspan="7" style="text-align:center;">Sem registos para esta viatura.</td></tr>
                <?php else: ?>
                    <?php foreach ($historico as $registo): ?>
                        <?php
                        if ($registo['data_servico'] instanceof DateTime) {
                            $data = $registo['data_servico']->format('d/m/Y');
                        } else {
                            $ts = strtotime((string) $registo['data_servico']);
                            $data = $ts !== false ? date('d/m/Y', $ts) : htmlspecialchars((string) $registo['data_servico']);
                        }
                        ?>
                        <tr>
                            <td><?php echo (int) $registo['id']; ?></td>
                            <td><?php echo htmlspecialchars($data); ?></td>
                            <td><?php echo htmlspecialchars((string) $registo['km']); ?></td>
                            <td><?php echo htmlspecialchars($registo['intervencao']); ?></td>
                            <td><?php echo htmlspecialchars(number_format((float) $registo['valor'], 2, ',', '.')) . ' €'; ?></td>
                            <td><?php echo htmlspecialchars($registo['fornecedor']); ?></td>
                            <td>
                                <form method="POST" action="eliminar_viatura.php" style="display:inline;" onsubmit="return confirm('Tem a certeza que deseja eliminar este registo?');">
                                    <input type="hidden" name="manutencao_id" value="<?php echo $registo['id']; ?>">
                                    <input type="hidden" name="viatura_id" value="<?php echo $viaturaId; ?>">
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="resumo">
                <strong>Despesa Total: <?php echo htmlspecialchars(number_format($despesaTotal, 2, ',', '.')); ?> €</strong>
                <strong>Total c/IVA (23%): <?php echo htmlspecialchars(number_format($totalComIva, 2, ',', '.')); ?> €</strong>
            </div>
        <?php endif; ?>
    </div>

    <div class="box">
        <h3>Novo Registo</h3>
        <?php if (!$viaturaValida): ?>
            <p>Selecione uma viatura para inserir um novo registo.</p>
        <?php else: ?>
            <form method="POST" action="gravar_viaturas.php">
                <input type="hidden" name="viatura_id" value="<?php echo $viaturaId; ?>">

                <label for="data_servico">Data</label>
                <input type="date" id="data_servico" name="data_servico" value="<?php echo date('Y-m-d'); ?>" required>

                <label for="km">KM</label>
                <input type="number" id="km" name="km" min="0" required>

                <label for="intervencao">Intervenção</label>
                <textarea id="intervencao" name="intervencao" required></textarea>

                <label for="valor">Valor</label>
                <input type="number" id="valor" name="valor" min="0" step="0.01" required>

                <label for="fornecedor">Fornecedor</label>
                <input type="text" id="fornecedor" name="fornecedor" maxlength="255" required>

                <button type="submit" class="btn-submit">Gravar Registo</button>
            </form>
        <?php endif; ?>
    </div>

    <a class="btn-voltar" href="index.html">Voltar ao Menu Principal</a>
</div>
</body>
</html>
<?php sqlsrv_close($conn); ?>
