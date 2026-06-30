<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$servidor   = "JFLVILARINHO\SQLEXPRESS";
$baseDados  = "ACESS APLICAÇÃO";
$utilizador = "Aplicação User";
$password   = "JFLousan#2026";

$mapeamento = [
    "Alfocheira"                 => "Trabalhos Alfocheira",
    "Cabeço do Moiro"             => "Trabalhos Cabeço do Moiro",
    "Cabo de Soito"               => "Trabalhos Cabo de Soito",
    "Cacilhas"                    => "Trabalhos Cacilhas",
    "Casal dos Rios"              => "Trabalhos Casal dos Rios",
    "Ceira dos Vales"             => "Trabalhos Ceira dos Vales",
    "Cómoros"                     => "Trabalhos Cómoros",
    "Cornaga"                     => "Trabalhos Cornaga",
    "Cova da Areia"               => "Trabalhos Cova da Areia",
    "Cova do Lobo"                => "Trabalhos Cova do Lobo",
    "Eira de Calva/Levagadas/Picoto" => "Trabalhos Eira de Calva/Levagadas/Picoto",
    "Fonte do Mouro"              => "Trabalhos Fonte do Mouro",
    "Fórnea"                      => "Trabalhos Fórnea",
    "Lousã"                       => "Trabalhos Lousã",
    "Meiral"                      => "Trabalhos Meiral",
    "Padrão"                      => "Trabalhos Padrão",
    "Pegos"                       => "Trabalhos Pegos",
    "Poças"                       => "Trabalhos Poças",
    "Porto da Pedra"              => "Trabalhos Porto da Pedra",
    "Póvoa da Lousã"              => "Trabalhos Póvoa da Lousã",
    "Ramalhais"                   => "Trabalhos Ramalhais",
    "Senhora das Barraquinhas"    => "Trabalhos Senhora das barraquinhas",
    "Vale Domingos"               => "Trabalhos Vale Domingos",
    "Vale Maceira"                => "Trabalhos Vale Maceira",
    "Vale Neira"                  => "Trabalhos Vale Neira",
    "Vale Nogueira"               => "Trabalhos Vale Nogueira",
    "Vale Pereira da Serra"       => "Trabalhos Vale Pereira da Serra",
    "Vale Pereira do Areal"       => "Trabalhos Vale Pereira do Areal",
];

$conn = odbc_connect("Driver={ODBC Driver 17 for SQL Server};Server=$servidor;Database=$baseDados;", $utilizador, $password);
if (!$conn) { echo json_encode(["erro" => odbc_errormsg()]); exit; }

$acao = $_GET['acao'] ?? $_POST['acao'] ?? '';

if ($acao === 'gravar') {
    $localidade = $_POST['localidade'] ?? '';
    $rua        = $_POST['rua']        ?? '';
    $data       = $_POST['data']       ?? '';
    $trabalho   = $_POST['trabalho']   ?? '';
    $obs        = $_POST['obs']        ?? '';

    if (!isset($mapeamento[$localidade])) { echo json_encode(["erro" => "Localidade inválida"]); exit; }

    $tabela = $mapeamento[$localidade];
    $sql    = "INSERT INTO dbo.[{$tabela}] ([Trabalhos efectuados], Ruas, Obs, Data) VALUES (?, ?, ?, ?)";
    $stmt   = odbc_prepare($conn, $sql);
    if (!$stmt) { echo json_encode(["erro" => odbc_errormsg()]); exit; }

    $ok = odbc_execute($stmt, [$trabalho, $rua, $obs, $data]);
    echo $ok ? json_encode(["sucesso" => true]) : json_encode(["erro" => odbc_errormsg()]);

} elseif ($acao === 'carregar') {
    $localidade = $_GET['localidade'] ?? '';
    if (!isset($mapeamento[$localidade])) { echo json_encode(["erro" => "Localidade inválida"]); exit; }

    $tabela = $mapeamento[$localidade];
    $res    = odbc_exec($conn, "SELECT [Trabalhos efectuados] AS Trabalho, Ruas, Obs, Data FROM dbo.[{$tabela}] ORDER BY Data DESC");
    if (!$res) { echo json_encode(["erro" => odbc_errormsg()]); exit; }

    $rows = [];
    while ($row = odbc_fetch_array($res)) { $rows[] = $row; }
    echo json_encode($rows);
} else {
    echo json_encode(["erro" => "Ação inválida"]);
}

odbc_close($conn);
?>
