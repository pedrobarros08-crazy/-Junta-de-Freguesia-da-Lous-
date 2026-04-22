<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sqlsrv_close($conn);
    exit;
}

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

if (!isset($_POST['id_viatura']) || empty($_POST['id_viatura']) || !is_numeric($_POST['id_viatura'])) {
    die("Erro: Viatura não selecionada.");
}
if (!isset($_POST['data']) || empty($_POST['data'])) {
    die("Erro: Data obrigatória.");
}

$id_viatura = intval($_POST['id_viatura']);
if (!isset($mapa_viaturas[$id_viatura])) {
    die("Erro: Viatura inválida.");
}

$data_input = trim($_POST['data']);
$data_obj = DateTime::createFromFormat('Y-m-d', $data_input);
if (!$data_obj || $data_obj->format('Y-m-d') !== $data_input) {
    die("Erro: Data inválida. Use o formato aaaa-mm-dd.");
}
$data = $data_obj->format('Y-m-d');

if (!isset($_POST['trabalho']) || empty(trim($_POST['trabalho']))) {
    die("Erro: Descrição do trabalho obrigatória.");
}
if (!isset($_POST['fornecedor']) || empty(trim($_POST['fornecedor']))) {
    die("Erro: Fornecedor obrigatório.");
}
if (!isset($_POST['kms']) || !is_numeric($_POST['kms']) || $_POST['kms'] < 0) {
    die("Erro: Quilometragem inválida.");
}
if (!isset($_POST['preco']) || !is_numeric($_POST['preco']) || $_POST['preco'] < 0) {
    die("Erro: Preço inválido.");
}

$descricao  = trim($_POST['trabalho']);
$fornecedor = trim($_POST['fornecedor']);
$kms        = intval($_POST['kms']);
$custo      = floatval($_POST['preco']);

if (mb_strlen($descricao, 'UTF-8') > 500) {
    die("Erro: Descrição muito longa (máximo 500 caracteres).");
}
if (mb_strlen($fornecedor, 'UTF-8') > 100) {
    die("Erro: Nome do fornecedor muito longo (máximo 100 caracteres).");
}

$tabela = $mapa_viaturas[$id_viatura];
if (!preg_match('/^[a-z0-9_]+$/', $tabela)) {
    die("Erro: Tabela de manutenção inválida.");
}
$tabela_sql = '[' . $tabela . ']';
$sql    = "INSERT INTO {$tabela_sql} (data_servico, descricao, fornecedor, kms, custo) VALUES (?, ?, ?, ?, ?)";
$params = array($data, $descricao, $fornecedor, $kms, $custo);
$stmt   = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro na preparação da query: " . htmlspecialchars($msg));
}

if (sqlsrv_execute($stmt)) {
    echo "<script>alert('Reparação gravada com sucesso!'); window.location.href='signin.php';</script>";
} else {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    echo "Erro ao gravar: " . htmlspecialchars($msg);
}

sqlsrv_free_stmt($stmt);

sqlsrv_close($conn);
?>
