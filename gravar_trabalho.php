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

// ✅ Validação de entrada
if (!isset($_POST['id_rua']) || empty($_POST['id_rua']) || !is_numeric($_POST['id_rua'])) {
    redirect_with_message('error', 'Erro: Rua não selecionada.');
}
if (!isset($_POST['data']) || empty($_POST['data'])) {
    redirect_with_message('error', 'Erro: Data obrigatória.');
}
if (!isset($_POST['tipo_trabalho']) || empty(trim($_POST['tipo_trabalho']))) {
    redirect_with_message('error', 'Erro: Tipo de trabalho obrigatório.');
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
$obs    = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
$desc   = $tipos_trabalho[$tipo_trabalho] . ($obs !== '' ? ' - ' . $obs : '');

// ✅ Validação de comprimento
if (mb_strlen($desc, 'UTF-8') > 500) {
    redirect_with_message('error', 'Erro: Descrição muito longa (máximo 500 caracteres).');
}

// ✅ Prepared Statement com SQLSRV (Proteção contra SQL Injection)
$sql    = "INSERT INTO historico_trabalhos (id_rua, data_trabalho, descricao_servico) VALUES (?, ?, ?)";
$params = array($id_rua, $data, $desc);
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
