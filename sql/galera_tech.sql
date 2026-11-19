CREATE DATABASE IF NOT EXISTS galera_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE galera_tech;
CREATE TABLE IF NOT EXISTS responsaveis (
 id_responsavel INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 parentesco VARCHAR(40) NOT NULL,
 telefone VARCHAR(11) NOT NULL
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS alunos (
 id_aluno INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(150) NOT NULL,
 data_nascimento DATE NOT NULL,
 cpf CHAR(11) NOT NULL UNIQUE,
 rg VARCHAR(30) NOT NULL,
 sexo VARCHAR(30) NOT NULL,
 email VARCHAR(150) NOT NULL,
 telefone CHAR(11) NOT NULL,
 reside_rio_preto VARCHAR(3) NOT NULL,
 cep CHAR(8) NOT NULL,
 logradouro VARCHAR(150) NOT NULL,
 numero VARCHAR(15) NOT NULL,
 complemento VARCHAR(100) NULL,
 bairro VARCHAR(100) NOT NULL,
 cidade VARCHAR(100) NOT NULL,
 estado CHAR(2) NOT NULL,
 nome_escola VARCHAR(150) NOT NULL,
 serie_escolar VARCHAR(80) NOT NULL,
 origem_divulgacao VARCHAR(100) NOT NULL,
 responsavel_id INT NULL,
 CONSTRAINT fk_aluno_responsavel FOREIGN KEY (responsavel_id) REFERENCES responsaveis(id_responsavel)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS inscricoes (
 id_inscricao INT AUTO_INCREMENT PRIMARY KEY,
 aluno_id INT NOT NULL,
 ano SMALLINT NOT NULL,
 semestre TINYINT NOT NULL,
 data_inscricao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 situacao VARCHAR(30) NOT NULL DEFAULT 'Recebida',
 ciencia_programa BOOLEAN NOT NULL,
 ciencia_transporte BOOLEAN NOT NULL,
 declarou_veracidade BOOLEAN NOT NULL,
 ciencia_privacidade BOOLEAN NOT NULL,
 data_aceite DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_inscricao_aluno FOREIGN KEY (aluno_id) REFERENCES alunos(id_aluno),
 CONSTRAINT uq_inscricao_periodo UNIQUE (aluno_id, ano, semestre)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS usuarios_admin (
 id_usuario INT AUTO_INCREMENT PRIMARY KEY,
 nome VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 senha_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
