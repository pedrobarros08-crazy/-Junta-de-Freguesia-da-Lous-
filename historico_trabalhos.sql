-- 1. Tabela com a lista de ruas
CREATE TABLE ruas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_rua VARCHAR(255) NOT NULL,
    localidade VARCHAR(100) NOT NULL
);

-- 2. Tabela de Registos de Trabalho
CREATE TABLE historico_trabalhos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_rua INT, -- Aqui indicamos a que rua pertence o trabalho
    data_trabalho DATE NOT NULL,
    descricao_servico TEXT NOT NULL,
    FOREIGN KEY (id_rua) REFERENCES ruas(id)
);