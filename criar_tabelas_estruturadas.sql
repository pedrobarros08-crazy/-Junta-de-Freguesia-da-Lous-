-- Script de criação de estrutura com 1 tabela por viatura e 1 tabela por localidade
-- Mantém as tabelas existentes `ruas` e `viaturas` (não são apagadas neste script).

-- ===============================================
-- Tabelas de Manutenções por Viatura
-- ===============================================
IF OBJECT_ID('dbo.manutencoes_toyota_dyna_06_53_sm', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_toyota_dyna_06_53_sm;
IF OBJECT_ID('dbo.manutencoes_toyota_dyna_96_98_ii', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_toyota_dyna_96_98_ii;
IF OBJECT_ID('dbo.manutencoes_mitsubishi_92_du_20', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_mitsubishi_92_du_20;
IF OBJECT_ID('dbo.manutencoes_opel_01_77_lr', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_opel_01_77_lr;
IF OBJECT_ID('dbo.manutencoes_hyundai_98_66_st', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_hyundai_98_66_st;
IF OBJECT_ID('dbo.manutencoes_renault_clio_42_bh_11', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_renault_clio_42_bh_11;
IF OBJECT_ID('dbo.manutencoes_renault_kangoo_33_bj_10', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_renault_kangoo_33_bj_10;
IF OBJECT_ID('dbo.manutencoes_trator_deutz', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_trator_deutz;
IF OBJECT_ID('dbo.manutencoes_dumper_astel', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_dumper_astel;
IF OBJECT_ID('dbo.manutencoes_retroescavadora_case', 'U') IS NOT NULL DROP TABLE dbo.manutencoes_retroescavadora_case;

CREATE TABLE dbo.manutencoes_toyota_dyna_06_53_sm (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_toyota_dyna_96_98_ii (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_mitsubishi_92_du_20 (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_opel_01_77_lr (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_hyundai_98_66_st (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_renault_clio_42_bh_11 (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_renault_kangoo_33_bj_10 (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_trator_deutz (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_dumper_astel (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

CREATE TABLE dbo.manutencoes_retroescavadora_case (
    id INT PRIMARY KEY IDENTITY(1,1),
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2)
);

-- ===============================================
-- Tabelas de Trabalhos por Localidade
-- ===============================================
IF OBJECT_ID('dbo.trabalhos_alfocheira', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_alfocheira;
IF OBJECT_ID('dbo.trabalhos_bairro_dos_carvalhos', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_bairro_dos_carvalhos;
IF OBJECT_ID('dbo.trabalhos_cabeco_do_moiro', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_cabeco_do_moiro;
IF OBJECT_ID('dbo.trabalhos_cabo_do_soito', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_cabo_do_soito;
IF OBJECT_ID('dbo.trabalhos_cacilhas', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_cacilhas;
IF OBJECT_ID('dbo.trabalhos_casal_dos_rios', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_casal_dos_rios;
IF OBJECT_ID('dbo.trabalhos_ceira_dos_vales', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_ceira_dos_vales;
IF OBJECT_ID('dbo.trabalhos_cornaga', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_cornaga;
IF OBJECT_ID('dbo.trabalhos_cova_da_areia', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_cova_da_areia;
IF OBJECT_ID('dbo.trabalhos_cova_do_lobo', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_cova_do_lobo;
IF OBJECT_ID('dbo.trabalhos_eira_de_calva', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_eira_de_calva;
IF OBJECT_ID('dbo.trabalhos_fornea', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_fornea;
IF OBJECT_ID('dbo.trabalhos_lousa', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_lousa;
IF OBJECT_ID('dbo.trabalhos_meiral', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_meiral;
IF OBJECT_ID('dbo.trabalhos_padrao', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_padrao;
IF OBJECT_ID('dbo.trabalhos_pegos', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_pegos;
IF OBJECT_ID('dbo.trabalhos_penedo', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_penedo;
IF OBJECT_ID('dbo.trabalhos_pocas', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_pocas;
IF OBJECT_ID('dbo.trabalhos_porto_da_pedra', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_porto_da_pedra;
IF OBJECT_ID('dbo.trabalhos_povoa_da_lousa', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_povoa_da_lousa;
IF OBJECT_ID('dbo.trabalhos_ramalhais', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_ramalhais;
IF OBJECT_ID('dbo.trabalhos_vale_de_maceira', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_vale_de_maceira;
IF OBJECT_ID('dbo.trabalhos_vale_domingos', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_vale_domingos;
IF OBJECT_ID('dbo.trabalhos_vale_neira', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_vale_neira;
IF OBJECT_ID('dbo.trabalhos_vale_nogueira', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_vale_nogueira;
IF OBJECT_ID('dbo.trabalhos_vale_pereira_do_areal', 'U') IS NOT NULL DROP TABLE dbo.trabalhos_vale_pereira_do_areal;

CREATE TABLE dbo.trabalhos_alfocheira (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_bairro_dos_carvalhos (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_cabeco_do_moiro (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_cabo_do_soito (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_cacilhas (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_casal_dos_rios (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_ceira_dos_vales (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_cornaga (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_cova_da_areia (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_cova_do_lobo (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_eira_de_calva (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_fornea (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_lousa (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_meiral (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_padrao (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_pegos (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_penedo (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_pocas (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_porto_da_pedra (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_povoa_da_lousa (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_ramalhais (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_vale_de_maceira (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_vale_domingos (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_vale_neira (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_vale_nogueira (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);

CREATE TABLE dbo.trabalhos_vale_pereira_do_areal (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255),
    data_trabalho DATE,
    tipo_trabalho VARCHAR(50),
    observacoes NVARCHAR(MAX)
);
