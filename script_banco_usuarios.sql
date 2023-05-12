CREATE TABLE tb_usuarios(
	id_usuario INT AUTO_INCREMENT,
	nome_usuario VARCHAR(45),
	nivel VARCHAR(45),
	pontos INT(6),
	tarefas_concluidas DECIMAl(5),
	login VARCHAR(45),
	email VARCHAR(45),
	senha VARCHAR(45),
  PRIMARY KEY (id_usuario)
);

INSERT INTO tb_usuarios (nome_usuario, login, senha) VALUES ('Sr. Administrador', 'admin', 'admin');
INSERT INTO tb_usuarios (nome_usuario, login, senha) VALUES ('Sr. Root', 'root', 'root');