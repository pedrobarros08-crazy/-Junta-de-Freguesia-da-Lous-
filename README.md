# Sistema de Gestão - Junta de Freguesia da Lousã

Sistema de gestão de trabalhos e viaturas desenvolvido para a [Junta de Freguesia da Lousã](https://www.freguesiadalousan.pt)

---

## 📋 Requisitos

- **XAMPP** com PHP 8.2+ e Apache
- **SQL Server** (SQLEXPRESS ou superior)
- **Driver SQLSRV** para PHP 8.2 (`php_sqlsrv_82_ts_x64.dll`)
- **SQL Server Management Studio** (opcional, para gerir a BD)

---

## 🚀 Instalação

### 1. Instalar o Driver SQLSRV

1. Descarregue o driver SQLSRV mais recente compatível com PHP 8.2+ de [Microsoft Drivers for PHP](https://github.com/microsoft/msphpsql/releases)
2. Extraia e copie para `C:\xampp\php\ext\`:
   - `php_sqlsrv_82_ts_x64.dll`
   - `php_pdo_sqlsrv_82_ts_x64.dll`
3. Edite `C:\xampp\php\php.ini` e adicione:
   ```ini
   extension=php_sqlsrv_82_ts_x64.dll
   extension=php_pdo_sqlsrv_82_ts_x64.dll
   ```
4. Reinicie o Apache no XAMPP Control Panel

### 2. Configurar a Base de Dados

No **SQL Server Management Studio**, execute os scripts SQL pela seguinte ordem:

```sql
-- 1. Criar as tabelas de localidades e trabalhos
-- Executar: gestao_junta.sql

-- 2. Criar as tabelas de viaturas e manutenções
-- Executar: viaturas.sql
```

### 3. Configurar as Credenciais

1. Copie `.env.example` para `.env`:
   ```
   copy .env.example .env
   ```
2. Edite `.env` com as suas credenciais reais:
   ```ini
   DB_SERVER=SEU_SERVIDOR\SQLEXPRESS
   DB_NAME=NOME_DA_BASE_DE_DADOS
   DB_USER=utilizador_bd
   DB_PASSWORD=a_sua_password
   DB_CHARSET=UTF-8
   ```

### 4. Copiar os Ficheiros

Copie todos os ficheiros para `C:\xampp\htdocs\gestao-junta\`

### 5. Aceder à Aplicação

```
http://localhost/gestao-junta/index.html
```

---

## 📁 Estrutura de Ficheiros

```
gestao-junta/
├── .env                    # Credenciais locais (não versionado)
├── .env.example            # Template de configuração
├── .gitignore              # Ficheiros ignorados pelo Git
├── loader.env.php          # Carregador de variáveis de ambiente
├── config.php              # Configuração da ligação à BD
├── index.html              # Menu principal
├── trabalhos.php           # Gestão de trabalhos por localidade
├── gravar_trabalho.php     # Gravação de trabalhos
├── ver_historico.php       # Histórico de trabalhos por localidade (AJAX)
├── ver_rua.php             # Detalhes de trabalho por rua (AJAX)
├── viaturas.php            # Gestão de viaturas e manutenções
├── gravar_viaturas.php     # Gravação de manutenções
├── ver_historicoviaturas.php # Histórico de manutenções por viatura (AJAX)
├── gestao_junta.sql        # Schema normalizado: localidades + trabalhos
└── viaturas.sql            # Schema normalizado: viaturas + manutencoes_viaturas
```

---

## 🔒 Segurança

- **Variáveis de ambiente** — Credenciais em `.env` (fora do controlo de versão)
- **Prepared Statements SQLSRV** — Proteção contra SQL Injection
- **`htmlspecialchars()`** em todos os outputs — Proteção contra XSS
- **Validação de entrada** em todos os formulários PHP

---

## 🔧 Resolução de Problemas

| Problema | Solução |
|---------|---------|
| `sqlsrv_connect` não encontrada | Instale o driver SQLSRV e reinicie o Apache |
| Erro de ligação à BD | Verifique as credenciais em `.env` |
| Página em branco | Verifique o log em `C:\xampp\apache\logs\error.log` |
| PHP 8.2 — ficheiro `.dll` correto | Use `php_sqlsrv_82_ts_x64.dll` (TS = Thread Safe) |

---

## 👤 Autor

Desenvolvido no âmbito de estágio na [Junta de Freguesia da Lousã](https://www.freguesiadalousan.pt)

