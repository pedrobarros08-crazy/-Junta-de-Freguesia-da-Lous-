<?php
include 'config.php';

$localidades = [
    'Alfocheira'           => 'trabalhos_alfocheira',
    'Bairro dos Carvalhos' => 'trabalhos_bairro_dos_carvalhos',
    'Cabeço do Moiro'      => 'trabalhos_cabeco_do_moiro',
    'Cabo do Soito'        => 'trabalhos_cabo_do_soito',
    'Cacilhas'             => 'trabalhos_cacilhas',
    'Casal dos Rios'       => 'trabalhos_casal_dos_rios',
    'Ceira dos Vales'      => 'trabalhos_ceira_dos_vales',
    'Cornaga'              => 'trabalhos_cornaga',
    'Cova da Areia'        => 'trabalhos_cova_da_areia',
    'Cova do Lobo'         => 'trabalhos_cova_do_lobo',
    'Eira de Calva'        => 'trabalhos_eira_de_calva',
    'Fórnea'               => 'trabalhos_fornea',
    'Lousã'                => 'trabalhos_lousa',
    'Meiral'               => 'trabalhos_meiral',
    'Padrão'               => 'trabalhos_padrao',
    'Pegos'                => 'trabalhos_pegos',
    'Penedo'               => 'trabalhos_penedo',
    'Poças'                => 'trabalhos_pocas',
    'Porto da Pedra'       => 'trabalhos_porto_da_pedra',
    'Póvoa da Lousã'       => 'trabalhos_povoa_da_lousa',
    'Ramalhais'            => 'trabalhos_ramalhais',
    'Vale de Maceira'      => 'trabalhos_vale_de_maceira',
    'Vale Domingos'        => 'trabalhos_vale_domingos',
    'Vale Neira'           => 'trabalhos_vale_neira',
    'Vale Nogueira'        => 'trabalhos_vale_nogueira',
    'Vale Pereira do Areal'=> 'trabalhos_vale_pereira_do_areal',
];

$localidade = isset($_GET['localidade']) ? trim($_GET['localidade']) : '';
if (!isset($localidades[$localidade])) {
    die("Erro: Localidade inválida.");
}

$tabela = $localidades[$localidade];
if (!preg_match('/^[a-z0-9_]+$/', $tabela)) {
    die("Erro: Tabela de localidade inválida.");
}
$tabelaEscapada = '[' . str_replace(']', ']]', $tabela) . ']';

$sql  = "SELECT id, nome_rua, data_trabalho, tipo_trabalho, observacoes FROM $tabelaEscapada ORDER BY data_trabalho DESC, id DESC";
$stmt = sqlsrv_prepare($conn, $sql);

if ($stmt === false) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro na preparação da query: " . htmlspecialchars($msg));
}

if (!sqlsrv_execute($stmt)) {
    $errors = sqlsrv_errors();
    $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
    die("Erro ao executar a query: " . htmlspecialchars($msg));
}

echo "<table style='border-collapse: collapse; width: 100%;'>
        <tr style='background-color: #333; color: white;'>
            <th style='padding: 10px; border: 1px solid #ddd;'>Rua</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Tipo de Trabalho</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Observações</th>
        </tr>";

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_trabalho'] instanceof DateTime
        ? $row['data_trabalho']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_trabalho']));
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($row['nome_rua']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($data) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['tipo_trabalho']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars((string) $row['observacoes']) . "</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='4' style='padding: 10px; text-align: center;'>Sem registos de trabalho.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
