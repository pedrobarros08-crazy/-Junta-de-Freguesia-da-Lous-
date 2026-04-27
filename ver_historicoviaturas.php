<?php
include 'config.php';

$viaturas = [
    'toyota_dyna_06_53_sm'          => 'Toyota Dyna 06-53-SM',
    'toyota_dyna_96_98_ii'          => 'Toyota Dyna 96-98-II',
    'mitsubishi_strakar_98_du_20'   => 'Mitsubishi Strakar 98-DU-20',
    'hyndai_h1_98_66_st'            => 'Hyndai H1 98-66-ST',
    'opel_campos_01_77_lr'          => 'Opel Campos 01-77-LR',
    'renault_kangoo_33_bj_10'       => 'Renault Kangoo 33-BJ-10',
    'renault_clio_42_bh_10'         => 'Renault Clio 42-BH-10',
    'trato_deutz_58_so_96'          => 'Trato Deutz 58-SO-96',
    'trator_case_84_dm_83'          => 'Trator Case 84-DM-83',
    'retroescavadora_case_55_rr_48' => 'Retroescavadora Case 55-RR-48',
    'dumper_astel_00_aa_90'         => 'Dumper Astel 00-AA-90',
];

$viatura = isset($_GET['viatura']) ? trim($_GET['viatura']) : '';
if (!isset($viaturas[$viatura])) {
    die("Erro: Viatura inválida.");
}

if (!preg_match('/^[a-z0-9_]+$/', $viatura)) {
    die("Erro: Nome de viatura inválido.");
}
$tabelaEscapada = '[' . str_replace(']', ']]', $viatura) . ']';

$sql  = "SELECT id, data_servico, km, intervencao, valor, fornecedor FROM $tabelaEscapada ORDER BY data_servico DESC, id DESC";
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
            <th style='padding: 10px; border: 1px solid #ddd;'>Data</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>KM</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Intervenção</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Valor</th>
            <th style='padding: 10px; border: 1px solid #ddd;'>Fornecedor</th>
        </tr>";

$temRegistos = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temRegistos = true;
    $data = $row['data_servico'] instanceof DateTime
        ? $row['data_servico']->format('d/m/Y')
        : date('d/m/Y', strtotime($row['data_servico']));
    echo "<tr style='border-bottom: 1px solid #ddd;'>
            <td style='padding: 10px;'>" . htmlspecialchars($data) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars((string) $row['km']) . " km</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['intervencao']) . "</td>
            <td style='padding: 10px;'>" . htmlspecialchars(number_format((float) $row['valor'], 2, ',', '.')) . " €</td>
            <td style='padding: 10px;'>" . htmlspecialchars($row['fornecedor']) . "</td>
          </tr>";
}

if (!$temRegistos) {
    echo "<tr><td colspan='5' style='padding: 10px; text-align: center;'>Sem registos de manutenção.</td></tr>";
}

echo "</table>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>
