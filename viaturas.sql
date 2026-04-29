-- Schema normalizado para gestão de viaturas (SQL Server)

-- 1. Tabela de Viaturas
IF OBJECT_ID(N'dbo.manutencoes_viaturas', N'U') IS NOT NULL DROP TABLE dbo.manutencoes_viaturas;
IF OBJECT_ID(N'dbo.viaturas', N'U') IS NOT NULL DROP TABLE dbo.viaturas;

CREATE TABLE viaturas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(100) NOT NULL UNIQUE,
    matricula NVARCHAR(20) NOT NULL UNIQUE
);

-- 2. Tabela de Manutenções (ligada às viaturas)
CREATE TABLE manutencoes_viaturas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_viatura INT NOT NULL,
    data_servico DATE NOT NULL,
    km INT NOT NULL,
    intervencao NVARCHAR(500) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    fornecedor NVARCHAR(255) NOT NULL,
    FOREIGN KEY (id_viatura) REFERENCES viaturas(id)
);

-- 3. Inserção inicial de viaturas
INSERT INTO viaturas (nome, matricula) VALUES
(N'Toyota Dyna 06-53-SM', N'06-53-SM'),
(N'Toyota Dyna 96-98-II', N'96-98-II'),
(N'Mitsubishi Strakar 98-DU-20', N'98-DU-20'),
(N'Hyndai H1 98-66-ST', N'98-66-ST'),
(N'Opel Campos 01-77-LR', N'01-77-LR'),
(N'Renault Kangoo 33-BJ-10', N'33-BJ-10'),
(N'Renault Clio 42-BH-10', N'42-BH-10'),
(N'Trato Deutz 58-SO-96', N'58-SO-96'),
(N'Trator Case 84-DM-83', N'84-DM-83'),
(N'Retroescavadora Case 55-RR-48', N'55-RR-48'),
(N'Dumper Astel 00-AA-90', N'00-AA-90');