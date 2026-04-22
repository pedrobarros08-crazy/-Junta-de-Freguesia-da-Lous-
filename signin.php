<?php
include 'config.php';

$mapa_viaturas = [
    1  => 'manutencoes_toyota_dyna_06_53_sm',
    2  => 'manutencoes_toyota_dyna_96_98_ii',
    3  => 'manutencoes_mitsubishi_92_du_20',
    4  => 'manutencoes_opel_01_77_lr',
    5  => 'manutencoes_hyundai_98_66_st',
    6  => 'manutencoes_renault_clio_42_bh_11',
    7  => 'manutencoes_renault_kangoo_33_bj_10',
    8  => 'manutencoes_trator_deutz',
    9  => 'manutencoes_dumper_astel',
    10 => 'manutencoes_retroescavadora_case'
];

$sql_viaturas = "SELECT id, nome FROM viaturas ORDER BY id";
$res_viaturas = sqlsrv_query($conn, $sql_viaturas);
$viaturas = [];
if ($res_viaturas !== false) {
    while ($row = sqlsrv_fetch_array($res_viaturas, SQLSRV_FETCH_ASSOC)) {
        if (isset($mapa_viaturas[(int)$row['id']])) {
            $viaturas[] = $row;
        }
    }
}

$id_viatura_hist = isset($_GET['id_viatura']) ? intval($_GET['id_viatura']) : 0;
if ($id_viatura_hist === 0 && !empty($viaturas)) {
    $id_viatura_hist = (int)$viaturas[0]['id'];
}
if (!isset($mapa_viaturas[$id_viatura_hist])) {
    $id_viatura_hist = 0;
}

$historico = [];
if ($id_viatura_hist > 0) {
    $tabela = $mapa_viaturas[$id_viatura_hist];
    if (preg_match('/^[a-z0-9_]+$/', $tabela)) {
        $tabela_sql = '[' . $tabela . ']';
        $sql_hist = "SELECT id, data_servico, descricao, fornecedor, kms, custo FROM {$tabela_sql} ORDER BY data_servico DESC, id DESC";
    } else {
        $sql_hist = null;
    }
    $res_hist = $sql_hist !== null ? sqlsrv_query($conn, $sql_hist) : false;
    if ($res_hist !== false) {
        while ($row = sqlsrv_fetch_array($res_hist, SQLSRV_FETCH_ASSOC)) {
            $historico[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Reparações de Frota</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; padding: 20px; color: #333; }
        .container { max-width: 900px; margin: auto; }
        .form-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .grid-form { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .full-width { grid-column: span 2; }

        .btn-gravar { background-color: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 15px; width: 100%; transition: 0.3s; }
        .btn-gravar:hover { background-color: #2980b9; }
        .btn-voltar { background-color: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-top: 20px; transition: 0.3s; width: 100%; }
        .btn-voltar:hover { background-color: #5a6268; }

        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-top: 10px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #333; color: white; }
        tr:hover { background-color: #f9f9f9; }
        .header-tabela { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; }

        .error-message { color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: none; }
        .filtro-historico { display: flex; gap: 10px; align-items: flex-end; margin-bottom: 10px; }
        .filtro-historico > div { flex: 1; }
    </style>
</head>
<body>

<div class="container">
    <div id="errorMessage" class="error-message"></div>

    <div class="form-container">
        <h2>Novo Registo de Reparação</h2>
        <form id="reparacaoForm" action="gravar_viaturas.php" method="POST">
            <div class="grid-form">
                <div>
                    <label>Viatura:</label>
                    <select id="cars" name="id_viatura" required>
                        <option value="">Selecione...</option>
                        <?php if (!empty($viaturas)): ?>
                            <?php foreach ($viaturas as $v): ?>
                                <option value="<?php echo intval($v['id']); ?>"<?php echo ((int)$v['id'] === $id_viatura_hist) ? ' selected' : ''; ?>><?php echo htmlspecialchars($v['nome']); ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="1">Toyota Dyna 06-53-SM</option>
                            <option value="2">Toyota Dyna 96-98-II</option>
                            <option value="3">Mitsubishi 92-DU-20</option>
                            <option value="4">Opel 01-77-LR</option>
                            <option value="5">Hyundai 98-66-ST</option>
                            <option value="6">Renault Clio 42-BH-11</option>
                            <option value="7">Renault Kangoo 33-BJ-10</option>
                            <option value="8">Trator Deutz</option>
                            <option value="9">Dumper Astel</option>
                            <option value="10">Retroescavadora Case</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label>Data:</label>
                    <input type="date" id="data" name="data" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="full-width">
                    <label>O que foi feito?</label>
                    <input type="text" id="tipoTrabalho" name="trabalho" placeholder="Ex: Mudança de óleo" required>
                </div>
                <div>
                    <label>Fornecedor:</label>
                    <input type="text" id="nomeFornecedor" name="fornecedor" required>
                </div>
                <div>
                    <label>Custo (€):</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" required>
                </div>
                <div>
                    <label>Kms Atuais:</label>
                    <input type="number" id="kms" name="kms" min="0" required>
                </div>
            </div>
            <button type="submit" class="btn-gravar">Gravar Reparação</button>
        </form>
    </div>

    <div class="header-tabela">
        <h2>Histórico de Manutenções</h2>
    </div>

    <form method="GET" class="filtro-historico">
        <div>
            <label for="id_viatura_hist">Viatura do histórico:</label>
            <select name="id_viatura" id="id_viatura_hist" required>
                <option value="">Selecione...</option>
                <?php foreach ($viaturas as $v): ?>
                    <option value="<?php echo intval($v['id']); ?>"<?php echo ((int)$v['id'] === $id_viatura_hist) ? ' selected' : ''; ?>><?php echo htmlspecialchars($v['nome']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-gravar" style="width: 220px; margin-top: 0;">Ver Histórico</button>
    </form>

    <table id="tabelaReparacoes">
        <thead>
            <tr>
                <th>Data</th>
                <th>Trabalho</th>
                <th>Fornecedor</th>
                <th>Kms</th>
                <th>Custo</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($id_viatura_hist === 0): ?>
                <tr><td colspan="5" style="text-align: center; padding: 20px;">Selecione uma viatura para ver o histórico.</td></tr>
            <?php elseif (empty($historico)): ?>
                <tr><td colspan="5" style="text-align: center; padding: 20px;">Sem registos de manutenção.</td></tr>
            <?php else: ?>
                <?php foreach ($historico as $h): ?>
                    <?php
                    $data = $h['data_servico'] instanceof DateTime
                        ? $h['data_servico']->format('d/m/Y')
                        : date('d/m/Y', strtotime($h['data_servico']));
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data); ?></td>
                        <td><?php echo htmlspecialchars($h['descricao']); ?></td>
                        <td><?php echo htmlspecialchars($h['fornecedor']); ?></td>
                        <td><?php echo htmlspecialchars(number_format((float)$h['kms'], 0, ',', '.')) . ' km'; ?></td>
                        <td><?php echo htmlspecialchars(number_format((float)$h['custo'], 2, ',', '.')) . ' €'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <button onclick="window.location.href='index.html'" class="btn-voltar">Voltar ao Menu</button>
</div>

<script>
    const form = document.getElementById('reparacaoForm');
    const errorDiv = document.getElementById('errorMessage');

    form.addEventListener('submit', function(event) {
        errorDiv.style.display = 'none';

        const viatura   = document.getElementById('cars').value;
        const data      = document.getElementById('data').value;
        const trabalho  = document.getElementById('tipoTrabalho').value;
        const fornecedor = document.getElementById('nomeFornecedor').value;
        const kms       = document.getElementById('kms').value;
        const preco     = document.getElementById('preco').value;

        if (!viatura) { event.preventDefault(); mostrarErro('Seleciona uma viatura!'); return; }
        if (!data)    { event.preventDefault(); mostrarErro('Seleciona uma data!'); return; }
        if (!trabalho || trabalho.trim().length === 0) { event.preventDefault(); mostrarErro('Descreve o trabalho realizado!'); return; }
        if (!fornecedor || fornecedor.trim().length === 0) { event.preventDefault(); mostrarErro('Indica o fornecedor!'); return; }
        if (!kms || kms <= 0) { event.preventDefault(); mostrarErro('Indica uma quilometragem válida!'); return; }
        if (!preco || preco < 0) { event.preventDefault(); mostrarErro('Indica um preço válido!'); return; }
    });

    function mostrarErro(mensagem) {
        errorDiv.innerText = mensagem;
        errorDiv.style.display = 'block';
        setTimeout(() => { errorDiv.style.display = 'none'; }, 5000);
    }
</script>

</body>
</html>
<?php sqlsrv_close($conn); ?>
