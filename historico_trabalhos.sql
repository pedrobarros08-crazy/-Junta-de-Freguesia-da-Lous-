-- Tabela de Ruas
CREATE TABLE ruas (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_rua NVARCHAR(255) NOT NULL,
    localidade NVARCHAR(100) NOT NULL
);

-- Tabela de Histórico de Trabalhos
CREATE TABLE historico_trabalhos (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_rua INT,
    data_trabalho DATE NOT NULL,
    descricao_servico NVARCHAR(MAX) NOT NULL,
    FOREIGN KEY (id_rua) REFERENCES ruas(id)
);

-- Tabela de Viaturas
CREATE TABLE viaturas (
    id INT PRIMARY KEY IDENTITY(1,1),
    nome_viatura NVARCHAR(100) NOT NULL,
    matricula NVARCHAR(20) -- Adicionado para suporte a registos completos
);

-- Tabela de Manutenções
CREATE TABLE manutencoes (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_viatura INT,
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2),
    FOREIGN KEY (id_viatura) REFERENCES viaturas(id)
);