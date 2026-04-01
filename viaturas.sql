-- 1. Tabela de Viaturas
CREATE TABLE viaturas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_viatura VARCHAR(100) NOT NULL
);

-- 2. Tabela de Manutenções (Ligada às viaturas)
CREATE TABLE manutencoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_viatura INT,
    data_servico DATE,
    descricao TEXT,
    fornecedor VARCHAR(100),
    kms INT,
    custo DECIMAL(10,2),
    FOREIGN KEY (id_viatura) REFERENCES viaturas(id)
);