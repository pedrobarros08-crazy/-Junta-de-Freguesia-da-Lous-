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

function redirect_with_message_viatura($viatura, $status, $message)
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $query = 'viatura=' . urlencode($viatura) . '&status=' . urlencode($status) . '&message=' . urlencode($message);
    header('Location: viaturas.php?' . $query);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: viaturas.php');
    exit;
}

$viatura = isset($_POST['viatura']) ? trim($_POST['viatura']) : '';
if (!isset($viaturas[$viatura])) {
    redirect_with_message_viatura('', 'error', 'Viatura inválida.');
}

$tabela = $viatura;
if (!preg_match('/^[a-z0-9_]+$/', $tabela)) {
    redirect_with_message_viatura($viatura, 'error', 'Tabela de viatura inválida.');
}
$tabelaEscapada = '[' . str_replace(']', ']]', $tabela) . ']';

$dataServico = isset($_POST['data_servico']) ? trim($_POST['data_servico']) : '';
$km          = isset($_POST['km'])           ? trim($_POST['km'])           : '';
$intervencao = isset($_POST['intervencao'])  ? trim($_POST['intervencao'])  : '';
$valor       = isset($_POST['valor'])        ? trim($_POST['valor'])        : '';
$fornecedor  = isset($_POST['fornecedor'])   ? trim($_POST['fornecedor'])   : '';

$dataObj = DateTime::createFromFormat('Y-m-d', $dataServico);
if (!$dataObj || $dataObj->format('Y-m-d') !== $dataServico) {
    redirect_with_message_viatura($viatura, 'error', 'Data de serviço inválida.');
}

if ($intervencao === '' || $fornecedor === '') {
    redirect_with_message_viatura($viatura, 'error', 'Intervenção e fornecedor são obrigatórios.');
}

if (!is_numeric($km) || (int)$km < 0) {
    redirect_with_message_viatura($viatura, 'error', 'KM inválido.');
}

if (!is_numeric($valor) || (float)$valor < 0) {
    redirect_with_message_viatura($viatura, 'error', 'Valor inválido.');
}

if (mb_strlen($intervencao, 'UTF-8') > 500 || mb_strlen($fornecedor, 'UTF-8') > 255) {
    redirect_with_message_viatura($viatura, 'error', 'Um ou mais campos excedem o tamanho permitido.');
}

$sql = "INSERT INTO $tabelaEscapada (data_servico, km, intervencao, valor, fornecedor) VALUES (?, ?, ?, ?, ?)";
$params = [$dataServico, (int)$km, $intervencao, (float)$valor, $fornecedor];
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    redirect_with_message_viatura($viatura, 'error', 'Erro interno ao preparar o registo.');
}

if (sqlsrv_execute($stmt)) {
    redirect_with_message_viatura($viatura, 'success', 'Registo guardado com sucesso.');
}

redirect_with_message_viatura($viatura, 'error', 'Erro ao guardar o registo.');
