<?php
require_once __DIR__ . '/security.php';
require_login();

if (is_production()) {
    http_response_code(404);
    exit('Página indisponível.');
}

// Enhanced SQLSRV connection test using environment variables from .env
$serverEnv = trim((string) getenv('DB_SERVER'));
$db = trim((string) getenv('DB_NAME'));
$user = trim((string) getenv('DB_USER'));
$pwd = (string) getenv('DB_PASSWORD');
$charset = trim((string) getenv('DB_CHARSET'));
if ($charset === '') {
    $charset = 'UTF-8';
}

header('Content-Type: text/plain; charset=utf-8');

echo "Testing SQLSRV connection diagnostics...\n\n";

if (!function_exists('sqlsrv_connect')) {
    echo "ERROR: sqlsrv_connect() not available. The SQLSRV extension is not loaded.\n";
    echo "Check php.ini and ensure the sqlsrv/pdo_sqlsrv DLLs are installed in the PHP ext directory.\n";
    exit(1);
}

if ($serverEnv === '' || $db === '' || $user === '' || $pwd === '') {
    echo "ERROR: Missing required DB environment variables.\n";
    echo "Required: DB_SERVER, DB_NAME, DB_USER, DB_PASSWORD\n";
    exit(1);
}

$candidates = [
    $serverEnv,
    'localhost\\SQLEXPRESS',
    '127.0.0.1\\SQLEXPRESS',
    'localhost,1433',
    '127.0.0.1,1433'
];

$seen = [];
foreach ($candidates as $s) {
    $s = trim($s);
    if ($s === '' || in_array($s, $seen, true)) {
        continue;
    }
    $seen[] = $s;

    echo "Trying server: $s\n";

    // Try with different encrypt/trust options to diagnose TLS/prelogin issues
    $optionsList = [
        ['Database' => $db, 'Uid' => $user, 'PWD' => $pwd, 'CharacterSet' => $charset, 'Encrypt' => false, 'TrustServerCertificate' => true],
        ['Database' => $db, 'Uid' => $user, 'PWD' => $pwd, 'CharacterSet' => $charset, 'Encrypt' => true,  'TrustServerCertificate' => true],
        ['Database' => $db, 'Uid' => $user, 'PWD' => $pwd, 'CharacterSet' => $charset]
    ];

    foreach ($optionsList as $opts) {
        $label = 'Encrypt=' . (isset($opts['Encrypt']) ? ($opts['Encrypt'] ? 'true' : 'false') : 'default') . ", TrustServerCertificate=" . (isset($opts['TrustServerCertificate']) ? ($opts['TrustServerCertificate'] ? 'true' : 'false') : 'default');
        echo "  -> Options: $label ... ";
        $conn = @sqlsrv_connect($s, $opts);
        if ($conn !== false) {
            echo "OK\n";
            echo "Connected successfully to $s / $db\n";
            sqlsrv_close($conn);
            exit(0);
        }

        echo "FAILED\n";
        $errs = sqlsrv_errors();
        if ($errs !== null) {
            foreach ($errs as $e) {
                echo "    SQLSTATE=" . ($e['SQLSTATE'] ?? 'N/A') . " Code=" . ($e['code'] ?? 'N/A') . " Message=" . ($e['message'] ?? '') . "\n";
            }
        } else {
            echo "    No error information available.\n";
        }
    }

    echo "\n";
}

echo "All attempts failed. See errors above. Follow the checklist:\n";
echo "- Ensure SQL Server service (instance) is running.\n";
echo "- Enable TCP/IP for the instance via SQL Server Configuration Manager.\n";
echo "- Start SQL Server Browser service if using instance names (SQLEXPRESS).\n";
echo "- Check Windows Firewall allowing SQL Server port (1433 or dynamic port).\n";
echo "- Try connecting with sqlcmd from this machine (sqlcmd -S .\\SQLEXPRESS -E).\n";
exit(1);
