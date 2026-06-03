<?php
require_once __DIR__ . '/security.php';
require_login();
require_once __DIR__ . '/config.php';

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

[$status, $message] = get_status_and_message_from_query();

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viaturas - Junta de Freguesia</title>
    <link rel="stylesheet" href="common.css">
    <style>
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
        .resumo { margin-top: 12px; display: flex; gap: 25px; flex-wrap: wrap; }
    </style>
</head>
<body>
<div class="container">
    <div class="box top-actions">
        <?php render_user_session_actions(); ?>
    </div>
    <div class="box">
        <h2>Viaturas</h2>
        <?php if ($status === 'error'): ?><div class="status-error"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
        <?php if ($status === 'success'): ?><div class="status-success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

        <div class="grid">
            <?php foreach ($viaturas as $id => $nome): ?>
                <a class="btn-viatura<?php echo $viaturaId === $id ? ' active' : ''; ?>" href="viaturas.php?viatura_id=<?php echo $id; ?>">
                    <?php echo htmlspecialchars($nome, ENT_QUOTES, 'UTF-8'); ?>
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
                        <?php $data = format_sqlsrv_date($registo['data_servico']); ?>
                        <tr>
                            <td><?php echo (int) $registo['id']; ?></td>
                            <td><?php echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars((string) $registo['km'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($registo['intervencao'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(number_format((float) $registo['valor'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') . ' €'; ?></td>
                            <td><?php echo htmlspecialchars($registo['fornecedor'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <form method="POST" action="eliminar_viatura.php" style="display:inline;" onsubmit="return confirm('Tem a certeza que deseja eliminar este registo?');">
                                    <input type="hidden" name="manutencao_id" value="<?php echo (int) $registo['id']; ?>">
                                    <input type="hidden" name="viatura_id" value="<?php echo (int) $viaturaId; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="resumo">
                <strong>Despesa Total: <?php echo htmlspecialchars(number_format($despesaTotal, 2, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> €</strong>
                <strong>Total c/IVA (23%): <?php echo htmlspecialchars(number_format($totalComIva, 2, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> €</strong>
            </div>
        <?php endif; ?>
    </div>

    <div class="box">
        <h3>Novo Registo</h3>
        <?php if (!$viaturaValida): ?>
            <p>Selecione uma viatura para inserir um novo registo.</p>
        <?php else: ?>
            <form method="POST" action="gravar_viaturas.php">
                <input type="hidden" name="viatura_id" value="<?php echo (int) $viaturaId; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

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
