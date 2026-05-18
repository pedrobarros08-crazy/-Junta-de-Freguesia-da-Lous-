-- Schema normalizado para gestão de trabalhos por localidade (SQL Server)

IF OBJECT_ID(N'dbo.trabalhos', N'U') IS NOT NULL DROP TABLE dbo.trabalhos;
IF OBJECT_ID(N'dbo.localidades', N'U') IS NOT NULL DROP TABLE dbo.localidades;

CREATE TABLE localidades (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME2 NOT NULL CONSTRAINT DF_localidades_created_at DEFAULT SYSDATETIME(),
    updated_at DATETIME2 NOT NULL CONSTRAINT DF_localidades_updated_at DEFAULT SYSDATETIME(),
    created_by INT NULL,
    updated_by INT NULL,
    audit_ip NVARCHAR(45) NULL,
    CONSTRAINT CHK_localidades_nome_not_empty CHECK (LEN(LTRIM(RTRIM(nome))) > 0)
);

CREATE TABLE trabalhos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_localidade INT NOT NULL,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(2000) NULL,
    created_at DATETIME2 NOT NULL CONSTRAINT DF_trabalhos_created_at DEFAULT SYSDATETIME(),
    updated_at DATETIME2 NOT NULL CONSTRAINT DF_trabalhos_updated_at DEFAULT SYSDATETIME(),
    created_by INT NULL,
    updated_by INT NULL,
    audit_ip NVARCHAR(45) NULL,
    CONSTRAINT FK_trabalhos_localidades FOREIGN KEY (id_localidade) REFERENCES localidades(id) ON DELETE CASCADE,
    CONSTRAINT CHK_trabalhos_nome_rua_not_empty CHECK (LEN(LTRIM(RTRIM(nome_rua))) > 0),
    CONSTRAINT CHK_trabalhos_tipo_not_empty CHECK (LEN(LTRIM(RTRIM(tipo_trabalho))) > 0),
    CONSTRAINT CHK_trabalhos_data_not_future CHECK (data_trabalho <= CAST(GETDATE() AS DATE))
);

CREATE INDEX IX_trabalhos_localidade_data ON trabalhos (id_localidade, data_trabalho DESC, id DESC);
CREATE INDEX IX_trabalhos_created_at ON trabalhos (created_at);

INSERT INTO localidades (nome) VALUES
(N'Alfocheira'),
(N'Bairro dos Carvalhos'),
(N'Cabeço do Moiro'),
(N'Cabo do Soito'),
(N'Cacilhas'),
(N'Casal dos Rios'),
(N'Ceira dos Vales'),
(N'Cornaga'),
(N'Cova da Areia'),
(N'Cova do Lobo'),
(N'Eira de Calva'),
(N'Fórnea'),
(N'Lousã'),
(N'Meiral'),
(N'Padrão'),
(N'Pegos'),
(N'Penedo'),
(N'Poças'),
(N'Porto da Pedra'),
(N'Póvoa da Lousã'),
(N'Ramalhais'),
(N'Vale de Maceira'),
(N'Vale Domingos'),
(N'Vale Neira'),
(N'Vale Nogueira'),
(N'Vale Pereira do Areal');
