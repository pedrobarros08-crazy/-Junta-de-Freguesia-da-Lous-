-- Schema normalizado para gestão de trabalhos por localidade (SQL Server)

-- 1. Tabela de Localidades
IF OBJECT_ID(N'dbo.trabalhos', N'U') IS NOT NULL DROP TABLE dbo.trabalhos;
IF OBJECT_ID(N'dbo.localidades', N'U') IS NOT NULL DROP TABLE dbo.localidades;

CREATE TABLE localidades (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(100) NOT NULL UNIQUE
);

-- 2. Tabela de Trabalhos (ligada às localidades)
CREATE TABLE trabalhos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_localidade INT NOT NULL,
    nome_rua NVARCHAR(255) NOT NULL,
    data_trabalho DATE NOT NULL,
    tipo_trabalho NVARCHAR(255) NOT NULL,
    observacoes NVARCHAR(2000) NULL,
    FOREIGN KEY (id_localidade) REFERENCES localidades(id)
);

-- 3. Inserção inicial de localidades
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