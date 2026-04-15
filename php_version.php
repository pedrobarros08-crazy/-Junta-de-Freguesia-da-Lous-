<?php
/**
 * Informações de versão PHP
 * Utilidade para verificar a configuração do ambiente
 */
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Versão PHP</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #eee; }
        .btn-voltar { display: block; margin-top: 20px; padding: 10px; background: #95a5a6; color: white; text-align: center; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Informações do Ambiente PHP</h2>
    <table>
        <tr><th>Propriedade</th><th>Valor</th></tr>
        <tr><td>Versão PHP</td><td><?php echo htmlspecialchars(phpversion()); ?></td></tr>
        <tr><td>Sistema Operativo</td><td><?php echo htmlspecialchars(PHP_OS); ?></td></tr>
        <tr><td>SAPI</td><td><?php echo htmlspecialchars(php_sapi_name()); ?></td></tr>
        <tr>
            <td>Extensões carregadas</td>
            <td>
                <?php
                $extensoes = get_loaded_extensions();
                sort($extensoes);
                echo htmlspecialchars(implode(', ', $extensoes));
                ?>
            </td>
        </tr>
        <tr><td>Diretório de extensões</td><td><?php echo htmlspecialchars(ini_get('extension_dir')); ?></td></tr>
        <tr><td>Ficheiro php.ini</td><td><?php echo htmlspecialchars(php_ini_loaded_file() ?: 'N/A'); ?></td></tr>
    </table>
    <a href="index.html" class="btn-voltar">Voltar ao Menu</a>
</div>
</body>
</html>
