<?php
include 'config.php';

// 1. Procurar as Ruas para o formulário
$sql_ruas = "SELECT id, nome_rua, localidade FROM ruas ORDER BY localidade, nome_rua";
$res_ruas = $conn->query($sql_ruas);
$ruas_db = [];
while($row = $res_ruas->fetch_assoc()){
    $ruas_db[$row['localidade']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Trabalhos - Junta de Freguesia</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 900px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        select, input, button { width: 100%; padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #2ecc71; color: white; border: none; cursor: pointer; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
<div class="container">
    <h2>Registo de Trabalho</h2>
    <form action="gravar_trabalho.php" method="POST">
        <label>Localidade:</label>
        <select name="localidade" id="localidade" onchange="atualizarRuas()" required>
            <option value="">Selecione...</option>
            <?php foreach(array_keys($ruas_db) as $loc): ?>
                <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
            <?php endforeach; ?>
        </select>

        <label>Rua:</label>
        <select name="id_rua" id="rua" required>
            <option value="">Selecione a localidade primeiro</option>
        </select>

        <label>Data:</label>
        <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required>

        <label>Descrição:</label>
        <input type="text" name="descricao" placeholder="O que foi feito?" required>

        <button type="submit">Gravar Trabalho</button>
    </form>

    <h3>Histórico de Intervenções</h3>
    <table>
        <thead>
            <tr><th>Localidade</th><th>Rua</th><th>Data</th><th>Descrição</th></tr>
        </thead>
        <tbody>
            <?php
            $sql_h = "SELECT h.*, r.nome_rua, r.localidade FROM historico_trabalhos h JOIN ruas r ON h.id_rua = r.id ORDER BY h.data_trabalho DESC";
            $res_h = $conn->query($sql_h);
            while($h = $res_h->fetch_assoc()) {
                echo "<tr><td>{$h['localidade']}</td><td>{$h['nome_rua']}</td><td>".date('d/m/Y', strtotime($h['data_trabalho']))."</td><td>{$h['descricao_servico']}</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <button onclick="window.location.href='index.html'" style="background:#95a5a6;">Voltar</button>
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