-- 1. Tabela de Viaturas
CREATE TABLE viaturas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nome NVARCHAR(100) NOT NULL
);

-- 2. Tabela de Manutenções (Ligada às viaturas)
CREATE TABLE manutencoes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_viatura INT,
    data_servico DATE,
    descricao NVARCHAR(MAX),
    fornecedor NVARCHAR(100),
    kms INT,
    custo DECIMAL(10,2),
    FOREIGN KEY (id_viatura) REFERENCES viaturas(id)
);

-- 3. Inserção de viaturas
INSERT INTO viaturas (nome) VALUES
('Toyota Dyna 06-53-SM'),
('Toyota Dyna 96-98-II'),
('Mitsubishi 92-DU-20'),
('Opel 01-77-LR'),
('Hyundai 98-66-ST'),
('Renault Clio 42-BH-11'),
('Renault Kangoo 33-BJ-10'),
('Trator Deutz'),
('Dumper Astel'),
('Retroescavadora Case');