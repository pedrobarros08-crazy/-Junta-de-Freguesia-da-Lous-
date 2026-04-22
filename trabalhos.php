<?php
include 'config.php';

// 1. Procurar as Ruas para o formulário
$sql_ruas = "SELECT id, nome_rua, localidade FROM ruas ORDER BY localidade, nome_rua";
$res_ruas = sqlsrv_query($conn, $sql_ruas);
$ruas_db = [];
if ($res_ruas !== false) {
    while ($row = sqlsrv_fetch_array($res_ruas, SQLSRV_FETCH_ASSOC)) {
        $ruas_db[$row['localidade']][] = $row;
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
    'OUTROS' => 'Outros'
];

$status = isset($_GET['status']) ? $_GET['status'] : '';
$status = in_array($status, ['success', 'error'], true) ? $status : '';
$message = isset($_GET['message']) ? $_GET['message'] : '';
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
            <?php foreach (array_keys($ruas_db) as $loc): ?>
                <option value="<?php echo htmlspecialchars($loc); ?>"><?php echo htmlspecialchars($loc); ?></option>
            <?php endforeach; ?>
        </select>

        <label>Rua:</label>
        <select name="id_rua" id="rua" required>
            <option value="">Selecione a localidade primeiro</option>
        </select>

        <label>Data:</label>
        <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required>

        <label>O que foi feito? (Tipo de Trabalho):</label>
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

    <h3 style="margin-top: 30px;">Histórico Geral de Trabalhos</h3>
    <table>
        <thead>
            <tr><th>Localidade</th><th>Rua</th><th>Data</th><th>Trabalho Realizado</th></tr>
        </thead>
        <tbody>
            <?php
            $sql_h = "SELECT h.id, h.data_trabalho, h.descricao_servico, r.nome_rua, r.localidade
                      FROM historico_trabalhos h
                      JOIN ruas r ON h.id_rua = r.id
                      ORDER BY h.data_trabalho DESC";
            $res_h = sqlsrv_query($conn, $sql_h);
            if ($res_h !== false) {
                while ($h = sqlsrv_fetch_array($res_h, SQLSRV_FETCH_ASSOC)) {
                    $data = $h['data_trabalho'] instanceof DateTime
                        ? $h['data_trabalho']->format('d/m/Y')
                        : date('d/m/Y', strtotime($h['data_trabalho']));
                    echo "<tr>"
                        . "<td>" . htmlspecialchars($h['localidade']) . "</td>"
                        . "<td>" . htmlspecialchars($h['nome_rua']) . "</td>"
                        . "<td>" . htmlspecialchars($data) . "</td>"
                        . "<td>" . htmlspecialchars($h['descricao_servico']) . "</td>"
                        . "</tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <button type="button" class="btn-voltar" onclick="window.location.href='index.html'">Voltar ao Menu Principal</button>
</div>

<script>
const ruasDB = <?php echo json_encode($ruas_db); ?>;
function atualizarRuas() {
    const loc = document.getElementById("localidade").value;
    const selectRua = document.getElementById("rua");
    selectRua.innerHTML = '<option value="">Selecione a rua...</option>';
    if(ruasDB[loc]) {
        ruasDB[loc].forEach(r => {
            let opt = document.createElement("option");
            opt.value = r.id;
            opt.textContent = r.nome_rua;
            selectRua.appendChild(opt);
        });
    }
}
</script>
</body>
</html>
