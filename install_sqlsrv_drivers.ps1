<#
install_sqlsrv_drivers.ps1

Detecta a versão do PHP (assume php.exe em C:\xampp\php\php.exe), identifica TS/NTS e arquitetura,
faça download dos DLLs correspondentes do GitHub (msphpsql) e coloca em C:\xampp\php\ext\.
Actualiza php.ini para carregar os ficheiros DLL explicitamente.

USO (executar em PowerShell com privilégios de Administrador):
  powershell -ExecutionPolicy Bypass -File .\install_sqlsrv_drivers.ps1
#>

$phpExe = 'C:\xampp\php\php.exe'
$extDir = 'C:\xampp\php\ext'
$phpIni = 'C:\xampp\php\php.ini'

function Abort($msg){ Write-Error $msg; exit 1 }

if (-not (Test-Path $phpExe)) { Abort("php.exe não encontrado em $phpExe. Atualize o caminho e tente novamente.") }
if (-not (Test-Path $extDir)) { Abort("Pasta ext não encontrada: $extDir") }
if (-not (Test-Path $phpIni)) { Abort("php.ini não encontrado em $phpIni") }

# Obter versão PHP
$phpVersion = & $phpExe -r "echo PHP_VERSION;" 2>&1
if ($LASTEXITCODE -ne 0 -or -not $phpVersion) { Abort('Falha ao obter versão do PHP via php.exe') }
$versionParts = $phpVersion.Split('.')
$majorMinor = "$($versionParts[0])$($versionParts[1])"  # ex: 82 para 8.2

# Detectar Thread Safety
$threadLine = & $phpExe -i | Select-String -Pattern 'Thread Safety' | Select-Object -First 1
$threadSafety = if ($threadLine -and $threadLine.ToString().ToLower().Contains('enabled')) { 'ts' } else { 'nts' }

# Detectar arquitectura (x64/x86)
$arch = & $phpExe -r "echo PHP_INT_SIZE * 8;" 2>&1
if ($arch -match '64') { $archLabel = 'x64' } else { $archLabel = 'x86' }

Write-Host "PHP versão: $phpVersion" -ForegroundColor Cyan
Write-Host "Thread Safety: $threadSafety" -ForegroundColor Cyan
Write-Host "Arquitetura: $archLabel" -ForegroundColor Cyan

# Construir nomes esperados
$sqlsrvName = "php_sqlsrv_${majorMinor}_${threadSafety}_${archLabel}.dll"
$pdoName = "php_pdo_sqlsrv_${majorMinor}_${threadSafety}_${archLabel}.dll"
Write-Host "Procurando assets: $sqlsrvName e $pdoName" -ForegroundColor Cyan

# Obter release mais recente do GitHub
$apiUrl = 'https://api.github.com/repos/microsoft/msphpsql/releases/latest'
try {
    $release = Invoke-RestMethod -Uri $apiUrl -UseBasicParsing -Headers @{ 'User-Agent' = 'msphpsql-installer-script' }
} catch {
    Abort('Erro ao aceder à API do GitHub. Verifique ligação de rede.')
}

# Procurar assets
$sqlsrvAsset = $release.assets | Where-Object { $_.name -eq $sqlsrvName }
$pdoAsset = $release.assets | Where-Object { $_.name -eq $pdoName }

if (-not $sqlsrvAsset -or -not $pdoAsset) {
    Write-Warning "Não foram encontrados os ficheiros exatos no release mais recente. Tentando procurar correspondências aproximadas..."
    $sqlsrvAsset = $release.assets | Where-Object { $_.name -match "php_sqlsrv_.*${majorMinor}.*${threadSafety}.*${archLabel}" } | Select-Object -First 1
    $pdoAsset = $release.assets | Where-Object { $_.name -match "php_pdo_sqlsrv_.*${majorMinor}.*${threadSafety}.*${archLabel}" } | Select-Object -First 1
}

if (-not $sqlsrvAsset -or -not $pdoAsset) {
    Write-Host "Assets disponíveis no release ($($release.tag_name)):" -ForegroundColor Yellow
    $release.assets | ForEach-Object { Write-Host $_.name }
    Abort('Não foi possível localizar os DLLs apropriados automaticamente. Baixe-os manualmente de https://github.com/microsoft/msphpsql/releases')
}

# Fazer download
$targets = @(
    @{ asset = $sqlsrvAsset; name = $sqlsrvName },
    @{ asset = $pdoAsset;   name = $pdoName }
)

foreach ($t in $targets) {
    $url = $t.asset.browser_download_url
    $out = Join-Path $extDir $t.name
    Write-Host "A descarregar $($t.name) ..." -ForegroundColor Green
    try {
        Invoke-WebRequest -Uri $url -OutFile $out -UseBasicParsing
    } catch {
        Abort("Falha ao descarregar $($t.name): $($_.Exception.Message)")
    }
}

# Backup php.ini
$backup = "$phpIni.bak-$(Get-Date -Format 'yyyyMMddHHmmss')"
Copy-Item -Path $phpIni -Destination $backup -Force
Write-Host "Backup do php.ini criado em $backup" -ForegroundColor Green

# Atualizar php.ini: substituir linhas extension=sqlsrv / extension=pdo_sqlsrv por nomes específicos
$iniText = Get-Content $phpIni -Raw
$iniText = $iniText -replace '^[ \t]*extension=sqlsrv\s*$','extension=' + $sqlsrvName -replace 'm','m'
$iniText = $iniText -replace '^[ \t]*extension=pdo_sqlsrv\s*$','extension=' + $pdoName -replace 'm','m'
Set-Content -Path $phpIni -Value $iniText -Encoding UTF8
Write-Host "php.ini atualizado para carregar: $sqlsrvName e $pdoName" -ForegroundColor Green

Write-Host "
Concluído: copie os ficheiros .dll para o diretório ext (feito automaticamente), reinicie o Apache e teste com verificar_sqlsrv.php ou phpinfo()." -ForegroundColor Cyan
Write-Host "Comando para reiniciar Apache (pode exigir privilégios): net stop Apache2.4 ; net start Apache2.4" -ForegroundColor Yellow

# Fim
