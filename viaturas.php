<?php
include 'config.php';

$viaturas = [
    'toyota_dyna_06_53_sm'         => 'Toyota Dyna 06-53-SM',
    'toyota_dyna_96_98_ii'         => 'Toyota Dyna 96-98-II',
    'mitsubishi_strakar_98_du_20'  => 'Mitsubishi Strakar 98-DU-20',
    'hyndai_h1_98_66_st'           => 'Hyndai H1 98-66-ST',
    'opel_campos_01_77_lr'         => 'Opel Campos 01-77-LR',
    'renault_kangoo_33_bj_10'      => 'Renault Kangoo 33-BJ-10',
    'renault_clio_42_bh_10'        => 'Renault Clio 42-BH-10',
    'trato_deutz_58_so_96'         => 'Trato Deutz 58-SO-96',
    'trator_case_84_dm_83'         => 'Trator Case 84-DM-83',
    'retroescavadora_case_55_rr_48' => 'Retroescavadora Case 55-RR-48',
    'dumper_astel_00_aa_90'        => 'Dumper Astel 00-AA-90',
];

$viaturaSelecionada = isset($_GET['viatura']) ? trim($_GET['viatura']) : '';
$viaturaValida = isset($viaturas[$viaturaSelecionada]);

$status = isset($_GET['status']) ? $_GET['status'] : '';
$status = in_array($status, ['success', 'error'], true) ? $status : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';

$historico = [];
$despesaTotal = 0.0;
$taxaIva = 0.23;

if ($viaturaValida) {
    $tabela = $viaturaSelecionada;
    if (preg_match('/^[a-z0-9_]+$/', $tabela)) {
        $tabelaEscapada = '[' . str_replace(']', ']]', $tabela) . ']';
        $sql = "SELECT id, data_servico, km, intervencao, valor, fornecedor FROM $tabelaEscapada ORDER BY data_servico DESC, id DESC";
    } else {
        $sql = '';
    }
    if ($sql !== '') {
        $res = sqlsrv_query($conn, $sql);
        if ($res !== false) {
            while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                $historico[] = $row;
                $despesaTotal += (float) $row['valor'];
            }
        }
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
        .btn-viatura { display: block; text-align: center; text-decoration: none; padding: 10px; border-radius: 6px; background-color: #e9ecef; color: #222; font-weight: 600; border: 1px solid #d3d7db; }
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
        .resumo { margin-top: 12px; display: flex; gap: 25px; flex-wrap: wrap; }
        .btn-voltar { background-color: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; display: block; width: 100%; text-align: center; text-decoration: none; box-sizing: border-box; }
    </style>
</head>
<body>
<div class="container">
    <div class="box">
        <h2>Viaturas</h2>
        <?php if ($status === 'error'): ?><div class="status-error"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($status === 'success'): ?><div class="status-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

        <div class="grid">
            <?php foreach ($viaturas as $slug => $nome): ?>
                <a class="btn-viatura<?php echo $viaturaSelecionada === $slug ? ' active' : ''; ?>" href="viaturas.php?viatura=<?php echo urlencode($slug); ?>">
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
                </tr>
                </thead>
                <tbody>
                <?php if (empty($historico)): ?>
                    <tr><td colspan="6" style="text-align:center;">Sem registos para esta viatura.</td></tr>
                <?php else: ?>
                    <?php foreach ($historico as $registo): ?>
                        <?php
                        $data = $registo['data_servico'] instanceof DateTime
                            ? $registo['data_servico']->format('d/m/Y')
                            : date('d/m/Y', strtotime($registo['data_servico']));
                        ?>
                        <tr>
                            <td><?php echo (int) $registo['id']; ?></td>
                            <td><?php echo htmlspecialchars($data); ?></td>
                            <td><?php echo htmlspecialchars((string) $registo['km']); ?></td>
                            <td><?php echo htmlspecialchars($registo['intervencao']); ?></td>
                            <td><?php echo htmlspecialchars(number_format((float) $registo['valor'], 2, ',', '.')) . ' €'; ?></td>
                            <td><?php echo htmlspecialchars($registo['fornecedor']); ?></td>
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
                <input type="hidden" name="viatura" value="<?php echo htmlspecialchars($viaturaSelecionada); ?>">

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
