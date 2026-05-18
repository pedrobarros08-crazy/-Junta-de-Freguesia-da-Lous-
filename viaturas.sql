-- Schema normalizado para gestão de viaturas (SQL Server)

IF OBJECT_ID(N'dbo.manutencoes_viaturas', N'U') IS NOT NULL DROP TABLE dbo.manutencoes_viaturas;
IF OBJECT_ID(N'dbo.viaturas', N'U') IS NOT NULL DROP TABLE dbo.viaturas;

CREATE TABLE viaturas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(100) NOT NULL UNIQUE,
    matricula NVARCHAR(20) NOT NULL UNIQUE,
    created_at DATETIME2 NOT NULL CONSTRAINT DF_viaturas_created_at DEFAULT SYSDATETIME(),
    updated_at DATETIME2 NOT NULL CONSTRAINT DF_viaturas_updated_at DEFAULT SYSDATETIME(),
    created_by INT NULL,
    updated_by INT NULL,
    audit_ip NVARCHAR(45) NULL,
    CONSTRAINT CHK_viaturas_nome_not_empty CHECK (LEN(LTRIM(RTRIM(nome))) > 0),
    CONSTRAINT CHK_viaturas_matricula_not_empty CHECK (LEN(LTRIM(RTRIM(matricula))) > 0)
);

CREATE TABLE manutencoes_viaturas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_viatura INT NOT NULL,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL,
    created_at DATETIME2 NOT NULL CONSTRAINT DF_manutencoes_created_at DEFAULT SYSDATETIME(),
    updated_at DATETIME2 NOT NULL CONSTRAINT DF_manutencoes_updated_at DEFAULT SYSDATETIME(),
    created_by INT NULL,
    updated_by INT NULL,
    audit_ip NVARCHAR(45) NULL,
    CONSTRAINT FK_manutencoes_viaturas FOREIGN KEY (id_viatura) REFERENCES viaturas(id) ON DELETE CASCADE,
    CONSTRAINT CHK_manutencoes_data_not_future CHECK (data_servico <= CAST(GETDATE() AS DATE)),
    CONSTRAINT CHK_manutencoes_km_positive CHECK (km >= 0),
    CONSTRAINT CHK_manutencoes_valor_positive CHECK (valor > 0),
    CONSTRAINT CHK_manutencoes_intervencao_not_empty CHECK (LEN(LTRIM(RTRIM(intervencao))) > 0),
    CONSTRAINT CHK_manutencoes_fornecedor_not_empty CHECK (LEN(LTRIM(RTRIM(fornecedor))) > 0)
);

CREATE INDEX IX_manutencoes_viatura_data ON manutencoes_viaturas (id_viatura, data_servico DESC, id DESC);
CREATE INDEX IX_manutencoes_created_at ON manutencoes_viaturas (created_at);

INSERT INTO viaturas (nome, matricula) VALUES
(N'Toyota Dyna 06-53-SM', N'06-53-SM'),
(N'Toyota Dyna 96-98-II', N'96-98-II'),
(N'Mitsubishi Strakar 98-DU-20', N'98-DU-20'),
(N'Hyundai H1 98-66-ST', N'98-66-ST'),
(N'Opel Campos 01-77-LR', N'01-77-LR'),
(N'Renault Kangoo 33-BJ-10', N'33-BJ-10'),
(N'Renault Clio 42-BH-10', N'42-BH-10'),
(N'Trator Deutz 58-SO-96', N'58-SO-96'),
(N'Trator Case 84-DM-83', N'84-DM-83'),
(N'Retroescavadora Case 55-RR-48', N'55-RR-48'),
(N'Dumper Astel 00-AA-90', N'00-AA-90');
