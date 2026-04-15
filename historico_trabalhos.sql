-- 1. Tabela com a lista de ruas
CREATE TABLE ruas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome_rua NVARCHAR(255) NOT NULL,
    localidade NVARCHAR(100) NOT NULL
);

-- 2. Tabela de Registos de Trabalho
CREATE TABLE historico_trabalhos (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_rua INT, -- Aqui indicamos a que rua pertence o trabalho
    data_trabalho DATE NOT NULL,
    descricao_servico NVARCHAR(MAX) NOT NULL,
    FOREIGN KEY (id_rua) REFERENCES ruas(id)
);