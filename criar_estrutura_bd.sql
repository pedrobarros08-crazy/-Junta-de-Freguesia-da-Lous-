-- Estrutura de base de dados para Trabalhos e Viaturas (SQL Server)

-- =========================
-- Tabelas de Trabalhos
-- =========================
IF OBJECT_ID(N'dbo.trabalhos_alfocheira', N'U') IS NULL
CREATE TABLE dbo.trabalhos_alfocheira (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_bairro_dos_carvalhos', N'U') IS NULL
CREATE TABLE dbo.trabalhos_bairro_dos_carvalhos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_cabeco_do_moiro', N'U') IS NULL
CREATE TABLE dbo.trabalhos_cabeco_do_moiro (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_cabo_do_soito', N'U') IS NULL
CREATE TABLE dbo.trabalhos_cabo_do_soito (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_cacilhas', N'U') IS NULL
CREATE TABLE dbo.trabalhos_cacilhas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_casal_dos_rios', N'U') IS NULL
CREATE TABLE dbo.trabalhos_casal_dos_rios (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_ceira_dos_vales', N'U') IS NULL
CREATE TABLE dbo.trabalhos_ceira_dos_vales (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_cornaga', N'U') IS NULL
CREATE TABLE dbo.trabalhos_cornaga (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_cova_da_areia', N'U') IS NULL
CREATE TABLE dbo.trabalhos_cova_da_areia (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_cova_do_lobo', N'U') IS NULL
CREATE TABLE dbo.trabalhos_cova_do_lobo (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_eira_de_calva', N'U') IS NULL
CREATE TABLE dbo.trabalhos_eira_de_calva (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_fornea', N'U') IS NULL
CREATE TABLE dbo.trabalhos_fornea (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_lousa', N'U') IS NULL
CREATE TABLE dbo.trabalhos_lousa (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_meiral', N'U') IS NULL
CREATE TABLE dbo.trabalhos_meiral (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_padrao', N'U') IS NULL
CREATE TABLE dbo.trabalhos_padrao (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_pegos', N'U') IS NULL
CREATE TABLE dbo.trabalhos_pegos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_penedo', N'U') IS NULL
CREATE TABLE dbo.trabalhos_penedo (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_pocas', N'U') IS NULL
CREATE TABLE dbo.trabalhos_pocas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_porto_da_pedra', N'U') IS NULL
CREATE TABLE dbo.trabalhos_porto_da_pedra (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_povoa_da_lousa', N'U') IS NULL
CREATE TABLE dbo.trabalhos_povoa_da_lousa (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_ramalhais', N'U') IS NULL
CREATE TABLE dbo.trabalhos_ramalhais (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_vale_de_maceira', N'U') IS NULL
CREATE TABLE dbo.trabalhos_vale_de_maceira (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_vale_domingos', N'U') IS NULL
CREATE TABLE dbo.trabalhos_vale_domingos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_vale_neira', N'U') IS NULL
CREATE TABLE dbo.trabalhos_vale_neira (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_vale_nogueira', N'U') IS NULL
CREATE TABLE dbo.trabalhos_vale_nogueira (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

IF OBJECT_ID(N'dbo.trabalhos_vale_pereira_do_areal', N'U') IS NULL
CREATE TABLE dbo.trabalhos_vale_pereira_do_areal (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(MAX) NULL
);

-- =========================
-- Tabelas de Viaturas
-- =========================
IF OBJECT_ID(N'dbo.toyota_dyna_06_53_sm', N'U') IS NULL
CREATE TABLE dbo.toyota_dyna_06_53_sm (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.toyota_dyna_96_98_ii', N'U') IS NULL
CREATE TABLE dbo.toyota_dyna_96_98_ii (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.mitsubishi_strakar_98_du_20', N'U') IS NULL
CREATE TABLE dbo.mitsubishi_strakar_98_du_20 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.hyndai_h1_98_66_st', N'U') IS NULL
CREATE TABLE dbo.hyndai_h1_98_66_st (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.opel_campos_01_77_lr', N'U') IS NULL
CREATE TABLE dbo.opel_campos_01_77_lr (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.renault_kangoo_33_bj_10', N'U') IS NULL
CREATE TABLE dbo.renault_kangoo_33_bj_10 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.renault_clio_42_bh_10', N'U') IS NULL
CREATE TABLE dbo.renault_clio_42_bh_10 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.trato_deutz_58_so_96', N'U') IS NULL
CREATE TABLE dbo.trato_deutz_58_so_96 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.trator_case_84_dm_83', N'U') IS NULL
CREATE TABLE dbo.trator_case_84_dm_83 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.retroescavadora_case_55_rr_48', N'U') IS NULL
CREATE TABLE dbo.retroescavadora_case_55_rr_48 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);

IF OBJECT_ID(N'dbo.dumper_astel_00_aa_90', N'U') IS NULL
CREATE TABLE dbo.dumper_astel_00_aa_90 (
    id INT IDENTITY(1,1) PRIMARY KEY,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL
);
