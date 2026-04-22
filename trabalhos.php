<?php
include 'config.php';

$mapa_localidades = [
    'Alfocheira' => 'trabalhos_alfocheira',
    'Bairro dos Carvalhos' => 'trabalhos_bairro_dos_carvalhos',
    'Cabeço do Moiro' => 'trabalhos_cabeco_do_moiro',
    'Cabo do Soito' => 'trabalhos_cabo_do_soito',
    'Cacilhas' => 'trabalhos_cacilhas',
    'Casal dos Rios' => 'trabalhos_casal_dos_rios',
    'Ceira dos Vales' => 'trabalhos_ceira_dos_vales',
    'Cornaga' => 'trabalhos_cornaga',
    'Cova da Areia' => 'trabalhos_cova_da_areia',
    'Cova do Lobo' => 'trabalhos_cova_do_lobo',
    'Eira de Calva' => 'trabalhos_eira_de_calva',
    'Fornea' => 'trabalhos_fornea',
    'Lousã' => 'trabalhos_lousa',
    'Meiral' => 'trabalhos_meiral',
    'Padrão' => 'trabalhos_padrao',
    'Pegos' => 'trabalhos_pegos',
    'Penedo' => 'trabalhos_penedo',
    'Poças' => 'trabalhos_pocas',
    'Porto da Pedra' => 'trabalhos_porto_da_pedra',
    'Póvoa da Lousã' => 'trabalhos_povoa_da_lousa',
    'Ramalhais' => 'trabalhos_ramalhais',
    'Vale de Maceira' => 'trabalhos_vale_de_maceira',
    'Vale Domingos' => 'trabalhos_vale_domingos',
    'Vale Neira' => 'trabalhos_vale_neira',
    'Vale Nogueira' => 'trabalhos_vale_nogueira',
    'Vale Pereira do Areal' => 'trabalhos_vale_pereira_do_areal'
];

$sql_ruas = "SELECT id, nome_rua, localidade FROM ruas ORDER BY localidade, nome_rua";
$res_ruas = sqlsrv_query($conn, $sql_ruas);
$ruas_db = [];
if ($res_ruas !== false) {
    while ($row = sqlsrv_fetch_array($res_ruas, SQLSRV_FETCH_ASSOC)) {
        if (isset($mapa_localidades[$row['localidade']])) {
            $ruas_db[$row['localidade']][] = $row;
        }
    }
}

$tipos_trabalho = [
    'LBV' => 'Limpeza de Bermas e Valetas',
    'CM' => 'Colocação de manilhas',
    'LA' => 'Limpeza Aqueduto',
    'DA' => 'Desbaste de árvores',
    'AH' => 'Aplicação de Herbicida',
    'LBT' => 'Limpeza de Bermas com trator',
    'CRP' => 'Construção/Reparação de passeios',
    'CRMS' => 'Construção/Reparação de muros de suporte',
    'Outros' => 'Outros'
];

$localidades = array_keys($ruas_db);
$status = isset($_GET['status']) ? $_GET['status'] : '';
$status = in_array($status, ['success', 'error'], true) ? $status : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';
$localidade_selecionada = isset($_GET['localidade']) ? trim($_GET['localidade']) : '';
if (!isset($mapa_localidades[$localidade_selecionada])) {
    $localidade_selecionada = '';
}

$historico = [];
if ($localidade_selecionada !== '') {
    $tabela_historico = $mapa_localidades[$localidade_selecionada];
    $sql_h = "SELECT nome_rua, data_trabalho, tipo_trabalho, observacoes FROM {$tabela_historico} ORDER BY data_trabalho DESC, id DESC";
    $res_h = sqlsrv_query($conn, $sql_h);
    if ($res_h !== false) {
        while ($h = sqlsrv_fetch_array($res_h, SQLSRV_FETCH_ASSOC)) {
            $historico[] = $h;
        }
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
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 900px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        label { font-weight: bold; display: block; margin-top: 15px; margin-bottom: 5px; }
        select, input[type="text"], input[type="date"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-gravar { background-color: #2ecc71; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; width: 100%; transition: 0.3s; }
        .btn-gravar:hover { background-color: #27ae60; }
        .btn-voltar { background-color: #95a5a6; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; display: block; width: 100%; text-align: center; text-decoration: none; box-sizing: border-box; transition: 0.3s; }
        .btn-voltar:hover { background-color: #7f8c8d; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #2c3e50; color: white; }
        tbody tr:hover { background-color: #f9f9f9; }
        .error-message { color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: none; }
        .success-message { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; display: none; }
        .error-message.show, .success-message.show { display: block; }
        .filtro-historico { display: flex; gap: 10px; align-items: flex-end; margin-top: 25px; }
        .filtro-historico > div { flex: 1; }
    </style>
</head>
<body>
<div class="container">
    <h2>Registo Diário de Trabalhos</h2>
    <div class="error-message<?php echo $status === 'error' ? ' show' : ''; ?>">
        <?php echo $status === 'error' ? htmlspecialchars($message) : ''; ?>
    </div>
    <div class="success-message<?php echo $status === 'success' ? ' show' : ''; ?>">
        <?php echo $status === 'success' ? htmlspecialchars($message) : ''; ?>
    </div>
    <form action="gravar_trabalho.php" method="POST">
        <label>Localidade:</label>
        <select name="localidade" id="localidade" onchange="atualizarRuas()" required>
            <option value="">Selecione a localidade</option>
            <?php foreach ($localidades as $loc): ?>
                <option value="<?php echo htmlspecialchars($loc); ?>"<?php echo $loc === $localidade_selecionada ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($loc); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Rua:</label>
        <select name="id_rua" id="rua" required>
            <option value="">Selecione a localidade primeiro</option>
        </select>

        <label>Data:</label>
        <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required>

        <label>Tipo de Trabalho:</label>
        <select name="tipo_trabalho" required>
            <option value="">Selecione o tipo de trabalho</option>
            <?php foreach ($tipos_trabalho as $codigo => $tipo): ?>
                <option value="<?php echo htmlspecialchars($codigo); ?>">
                    <?php echo htmlspecialchars($tipo . ' (' . $codigo . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Observações:</label>
        <input type="text" name="descricao" placeholder="Escreve aqui o trabalho realizado...">

        <button type="submit" class="btn-gravar">Gravar Trabalho</button>
    </form>

    <h3 style="margin-top: 30px;">Histórico de Trabalhos</h3>
    <form method="GET" class="filtro-historico">
        <div>
            <label for="localidade_historico">Localidade do histórico:</label>
            <select name="localidade" id="localidade_historico" required>
                <option value="">Selecione a localidade</option>
                <?php foreach ($localidades as $loc): ?>
                    <option value="<?php echo htmlspecialchars($loc); ?>"<?php echo $loc === $localidade_selecionada ? ' selected' : ''; ?>>
                        <?php echo htmlspecialchars($loc); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-gravar" style="width: 220px; margin-top: 0;">Ver Histórico</button>
    </form>

    <table>
        <thead>
            <tr><th>Rua</th><th>Data</th><th>Tipo de Trabalho</th><th>Observações</th></tr>
        </thead>
        <tbody>
            <?php if ($localidade_selecionada === ''): ?>
                <tr><td colspan="4" style="text-align:center;">Selecione uma localidade para ver o histórico.</td></tr>
            <?php elseif (empty($historico)): ?>
                <tr><td colspan="4" style="text-align:center;">Sem registos para esta localidade.</td></tr>
            <?php else: ?>
                <?php foreach ($historico as $h): ?>
                    <?php
                    $data = $h['data_trabalho'] instanceof DateTime
                        ? $h['data_trabalho']->format('d/m/Y')
                        : date('d/m/Y', strtotime($h['data_trabalho']));
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($h['nome_rua']); ?></td>
                        <td><?php echo htmlspecialchars($data); ?></td>
                        <td><?php echo htmlspecialchars($h['tipo_trabalho']); ?></td>
                        <td><?php echo htmlspecialchars($h['observacoes']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <button type="button" class="btn-voltar" onclick="window.location.href='index.html'">Voltar ao Menu Principal</button>
</div>

<script>
const ruasDB = <?php echo json_encode($ruas_db); ?>;
function atualizarRuas() {
    const loc = document.getElementById('localidade').value;
    const selectRua = document.getElementById('rua');
    selectRua.innerHTML = '<option value="">Selecione a rua...</option>';
    if (ruasDB[loc]) {
        ruasDB[loc].forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.nome_rua;
            selectRua.appendChild(opt);
        });
    }
}
atualizarRuas();
</script>
</body>
</html>
<?php sqlsrv_close($conn); ?>
