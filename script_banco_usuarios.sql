CREATE TABLE tb_usuarios(
	id_usuario INT AUTO_INCREMENT UNIQUE,
	nome_usuario VARCHAR(45) NOT NULL UNIQUE,
	nivel VARCHAR(45),
	pontos INT(6),
	tarefas_concluidas DECIMAl(5),
	login VARCHAR(45) NOT NULL UNIQUE,
	email VARCHAR(45) NOT NULL UNIQUE,
	senha VARCHAR(45) NOT NULL,
  PRIMARY KEY (id_usuario)
);

INSERT INTO tb_usuarios (nome_usuario, login, email, senha) VALUES ('Sr. Administrador', 'admin', 'admin@admin', 'admin');
INSERT INTO tb_usuarios (nome_usuario, login, email, senha) VALUES ('Sr. Root', 'root','root@root', 'root');


CREATE TABLE tb_tarefas(
	id_tarefa INT AUTO_INCREMENT,
	nome_tarefa VARCHAR(45) NOT NULL,
	dificuldade VARCHAR(45),
	valor_pontos INT,
	id_usuario INT,
	-- id_lista INT,
  PRIMARY KEY (id_tarefa)
);

ALTER TABLE tb_tarefas ADD CONSTRAINT fk_usuarios FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos) VALUES ('Tarefa 1', 'Descrição da tarefa 1', 'Fácil', 'Baixa', 10);
INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos) VALUES ('Tarefa 2', 'Descrição da tarefa 2', 'Médio', 'Média', 20);
