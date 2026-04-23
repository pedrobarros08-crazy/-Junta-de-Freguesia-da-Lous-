<?php
include 'config.php';

$localidades = [
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
    'Fórnea' => 'trabalhos_fornea',
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
    'Vale Pereira do Areal' => 'trabalhos_vale_pereira_do_areal',
];

function redirect_with_message($localidade, $status, $message)
{
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    $query = 'localidade=' . urlencode($localidade) . '&status=' . urlencode($status) . '&message=' . urlencode($message);
    header('Location: trabalhos.php?' . $query);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: trabalhos.php');
    exit;
}

$localidade = isset($_POST['localidade']) ? trim($_POST['localidade']) : '';
if (!isset($localidades[$localidade])) {
    redirect_with_message('', 'error', 'Localidade inválida.');
}

$tabela = $localidades[$localidade];
$nomeRua = isset($_POST['nome_rua']) ? trim($_POST['nome_rua']) : '';
$dataInput = isset($_POST['data_trabalho']) ? trim($_POST['data_trabalho']) : '';
$tipoTrabalho = isset($_POST['tipo_trabalho']) ? trim($_POST['tipo_trabalho']) : '';
$observacoes = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : '';

if ($nomeRua === '' || $tipoTrabalho === '') {
    redirect_with_message($localidade, 'error', 'Rua e tipo de trabalho são obrigatórios.');
}

$dataObj = DateTime::createFromFormat('Y-m-d', $dataInput);
if (!$dataObj || $dataObj->format('Y-m-d') !== $dataInput) {
    redirect_with_message($localidade, 'error', 'Data inválida.');
}

if (mb_strlen($nomeRua, 'UTF-8') > 255 || mb_strlen($tipoTrabalho, 'UTF-8') > 255 || mb_strlen($observacoes, 'UTF-8') > 2000) {
    redirect_with_message($localidade, 'error', 'Um ou mais campos excedem o tamanho permitido.');
}

$sql = "INSERT INTO $tabela (nome_rua, data_trabalho, tipo_trabalho, observacoes) VALUES (?, ?, ?, ?)";
$params = [$nomeRua, $dataInput, $tipoTrabalho, $observacoes];
$stmt = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    redirect_with_message($localidade, 'error', 'Erro interno ao preparar o registo.');
}

if (sqlsrv_execute($stmt)) {
    redirect_with_message($localidade, 'success', 'Trabalho registado com sucesso.');
}

redirect_with_message($localidade, 'error', 'Erro ao guardar o trabalho.');
