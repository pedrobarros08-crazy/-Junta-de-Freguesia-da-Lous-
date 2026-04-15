<?php
/**
 * Verificação do driver SQLSRV
 * Utilidade para diagnosticar problemas de ligação à base de dados
 */
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Verificação SQLSRV</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .ok { color: #27ae60; font-weight: bold; }
        .erro { color: #e74c3c; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #eee; }
        .btn-voltar { display: block; margin-top: 20px; padding: 10px; background: #95a5a6; color: white; text-align: center; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Verificação do Driver SQLSRV</h2>
    <table>
        <tr>
            <th>Verificação</th>
            <th>Estado</th>
        </tr>
        <tr>
            <td>Versão PHP</td>
            <td><?php echo htmlspecialchars(phpversion()); ?></td>
        </tr>
        <tr>
            <td>Extensão SQLSRV</td>
            <td>
                <?php if (extension_loaded('sqlsrv')): ?>
                    <span class="ok">✅ Instalada</span>
                <?php else: ?>
                    <span class="erro">❌ Não instalada</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Extensão PDO_SQLSRV</td>
            <td>
                <?php if (extension_loaded('pdo_sqlsrv')): ?>
                    <span class="ok">✅ Instalada</span>
                <?php else: ?>
                    <span class="erro">❌ Não instalada</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Ficheiro .env</td>
            <td>
                <?php if (file_exists(__DIR__ . '/.env')): ?>
                    <span class="ok">✅ Encontrado</span>
                <?php else: ?>
                    <span class="erro">❌ Não encontrado — copie .env.example para .env</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php if (extension_loaded('sqlsrv')): ?>
        <tr>
            <td>Ligação à BD</td>
            <td>
                <?php
                require_once __DIR__ . '/loader.env.php';
                $serverName = getenv('DB_SERVER');
                if (!$serverName) {
                    echo '<span class="erro">❌ DB_SERVER não configurado</span>';
                } else {
                    $connectionOptions = array(
                        "Database"     => getenv('DB_NAME') ?: '',
                        "Uid"          => getenv('DB_USER') ?: '',
                        "PWD"          => getenv('DB_PASSWORD') ?: '',
                        "CharacterSet" => getenv('DB_CHARSET') ?: 'UTF-8',
                    );
                    $testConn = sqlsrv_connect($serverName, $connectionOptions);
                    if ($testConn !== false) {
                        echo '<span class="ok">✅ Ligação bem-sucedida</span>';
                        sqlsrv_close($testConn);
                    } else {
                        $errors = sqlsrv_errors();
                        $msg = isset($errors[0]['message']) ? $errors[0]['message'] : 'Erro desconhecido';
                        echo '<span class="erro">❌ ' . htmlspecialchars($msg) . '</span>';
                    }
                }
                ?>
            </td>
        </tr>
        <?php endif; ?>
    </table>
    <a href="index.html" class="btn-voltar">Voltar ao Menu</a>
</div>
</body>
</html>
