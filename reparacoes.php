<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$servidor   = "JFLVILARINHO\\SQLEXPRESS";
$baseDados  = "ACESS APLICAÇÃO";
$utilizador = "Aplicação User";
$password   = "JFLousan#2026";

$mapeamento = [
    "Toyota Dyna 06-53-SM"    => "dbo.Manutenção Toyota Dyna",
    "Toyota Dyna 96-98-II"    => "dbo.Manutenção Toyota Dyna 96-98-II",
    "Mitsubishi 92-DU-20"     => "dbo.Manutenção Mitubishi Strakar",
    "Opel 01-77-LR"           => "dbo.Manutenção OPEL CAMPOS",
    "Hyundai 98-66-ST"        => "dbo.Manutenção Hyundai",
    "Renault Clio 42-BH-11"   => "dbo.Manutenção Renault Clio",
    "Renault Kangoo 33-BJ-10" => "dbo.Manutenção Renault Kangoo",
    "Trator Deutz"            => "dbo.Manutenção Trator Deutz",
    "Dumper Astel"            => "dbo.Manutenção Dumper VN Astel",
    "Retroescavadora Case"    => "dbo.Manutenção Retroescavadora Case"
];

$conn = odbc_connect("Driver={ODBC Driver 17 for SQL Server};Server=$servidor;Database=$baseDados;", $utilizador, $password);
if (!$conn) { echo json_encode(["erro" => odbc_errormsg()]); exit; }

$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

if ($acao === 'gravar') {
    $viatura    = $_POST['viatura']    ?? '';
    $data       = $_POST['data']       ?? '';
    $trabalho   = $_POST['trabalho']   ?? '';
    $fornecedor = $_POST['fornecedor'] ?? '';
    $kms        = $_POST['kms']        ?? '';
    $preco      = $_POST['preco']      ?? '';

    if (!isset($mapeamento[$viatura])) { echo json_encode(["erro" => "Viatura inválida"]); exit; }

    $tabela = $mapeamento[$viatura];
    $sql    = "INSERT INTO dbo.[{$tabela}] (KM, Data, [Intervenção], Valor, Fornecedor) VALUES (?, ?, ?, ?, ?)";
    $stmt   = odbc_prepare($conn, $sql);
    if (!$stmt) { echo json_encode(["erro" => odbc_errormsg()]); exit; }

    $ok = odbc_execute($stmt, [$kms, $data, $trabalho, $preco, $fornecedor]);
    echo $ok ? json_encode(["sucesso" => true]) : json_encode(["erro" => odbc_errormsg()]);

} elseif ($acao === 'carregar') {
    $viatura = $_GET['viatura'] ?? '';
    if (!isset($mapeamento[$viatura])) { echo json_encode(["erro" => "Viatura inválida"]); exit; }

    $tabela = $mapeamento[$viatura];
    $res    = odbc_exec($conn, "SELECT KM, Data, [Intervenção], Valor, Fornecedor FROM dbo.[{$tabela}] ORDER BY Data DESC");
    if (!$res) { echo json_encode(["erro" => odbc_errormsg()]); exit; }

    $rows = [];
    while ($row = odbc_fetch_array($res)) { $rows[] = $row; }
    echo json_encode($rows);
} else {
    echo json_encode(["erro" => "Ação inválida"]);
}

odbc_close($conn);
?>
