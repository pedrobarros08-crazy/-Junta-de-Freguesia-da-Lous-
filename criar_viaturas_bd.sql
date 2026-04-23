-- Estrutura de base de dados para Viaturas (SQL Server)

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
