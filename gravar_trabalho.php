<?php
include 'config.php';

function redirect_with_message($status, $message) {
    $status = in_array($status, ['success', 'error'], true) ? $status : 'error';
    header('Location: trabalhos.php?status=' . urlencode($status) . '&message=' . urlencode($message));
    exit;
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

if (!isset($_POST['localidade']) || empty(trim($_POST['localidade']))) {
    redirect_with_message('error', 'Erro: Localidade não selecionada.');
}
if (!isset($_POST['id_rua']) || empty($_POST['id_rua']) || !is_numeric($_POST['id_rua'])) {
    redirect_with_message('error', 'Erro: Rua não selecionada.');
}
if (!isset($_POST['data']) || empty($_POST['data'])) {
    redirect_with_message('error', 'Erro: Data obrigatória.');
}
if (!isset($_POST['tipo_trabalho']) || empty(trim($_POST['tipo_trabalho']))) {
    redirect_with_message('error', 'Erro: Tipo de trabalho obrigatório.');
}

$localidade = trim($_POST['localidade']);
if (!isset($mapa_localidades[$localidade])) {
    redirect_with_message('error', 'Erro: Localidade inválida.');
}

$tipo_trabalho = trim($_POST['tipo_trabalho']);
if (!array_key_exists($tipo_trabalho, $tipos_trabalho)) {
    redirect_with_message('error', 'Erro: Tipo de trabalho inválido.');
}

// ✅ Validação robusta de data para SQL Server (formato yyyy-mm-dd)
$data_input = trim($_POST['data']);
$data_obj = DateTime::createFromFormat('Y-m-d', $data_input);
if (!$data_obj || $data_obj->format('Y-m-d') !== $data_input) {
    redirect_with_message('error', 'Erro: Data inválida. Use o formato aaaa-mm-dd.');
}
$data = $data_obj->format('Y-m-d');

$id_rua = intval($_POST['id_rua']);
$observacoes = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';

if (mb_strlen($observacoes, 'UTF-8') > 1000) {
    redirect_with_message('error', 'Erro: Observações muito longas (máximo 1000 caracteres).');
}

$sql_rua = "SELECT nome_rua, localidade FROM ruas WHERE id = ?";
$stmt_rua = sqlsrv_prepare($conn, $sql_rua, array($id_rua));
if ($stmt_rua === false || !sqlsrv_execute($stmt_rua)) {
    redirect_with_message('error', 'Erro ao validar a rua selecionada.');
}
$rua = sqlsrv_fetch_array($stmt_rua, SQLSRV_FETCH_ASSOC);
sqlsrv_free_stmt($stmt_rua);

if (!$rua) {
    redirect_with_message('error', 'Erro: Rua inválida.');
}
if ($rua['localidade'] !== $localidade) {
    redirect_with_message('error', 'Erro: Rua não pertence à localidade selecionada.');
}

$tabela = $mapa_localidades[$localidade];
if (!preg_match('/^[a-z0-9_]+$/', $tabela)) {
    redirect_with_message('error', 'Erro: Tabela de localidade inválida.');
}
$tabela_sql = '[' . $tabela . ']';
$nome_rua = $rua['nome_rua'];

$sql    = "INSERT INTO {$tabela_sql} (nome_rua, data_trabalho, tipo_trabalho, observacoes) VALUES (?, ?, ?, ?)";
$params = array($nome_rua, $data, $tipo_trabalho, $observacoes);
$stmt   = sqlsrv_prepare($conn, $sql, $params);

if ($stmt === false) {
    redirect_with_message('error', 'Erro interno ao processar o pedido.');
}

if (sqlsrv_execute($stmt)) {
    redirect_with_message('success', 'Trabalho registado com sucesso!');
} else {
    redirect_with_message('error', 'Erro ao guardar o trabalho.');
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
