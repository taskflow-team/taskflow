-- Cria a tabela de usuários
CREATE TABLE tb_usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(45) NOT NULL UNIQUE,
    nivel VARCHAR(45),
    pontos INT(6),
    tarefas_concluidas DECIMAL(5),
    login VARCHAR(45) NOT NULL UNIQUE,
    email VARCHAR(45) NOT NULL UNIQUE,
    senha VARCHAR(45) NOT NULL
);

-- Insere alguns dados iniciais na tabela de usuários
INSERT INTO tb_usuarios (nome_usuario, login, email, senha) VALUES ('Sr. Administrador', 'admin', 'admin@admin', 'admin');
INSERT INTO tb_usuarios (nome_usuario, login, email, senha) VALUES ('Sr. Root', 'root','root@root', 'root');

-- Cria a tabela de níveis
CREATE TABLE tb_niveis (
    idtb_niveis INT PRIMARY KEY AUTO_INCREMENT,
    nome_nivel VARCHAR(45)
);

-- Cria a tabela de grupos
CREATE TABLE tb_grupos (
    idtb_grupos INT PRIMARY KEY AUTO_INCREMENT,
    codigo_convite VARCHAR(45),
    nome VARCHAR(45)
);

-- Cria a tabela de listas
CREATE TABLE tb_listas (
    idtb_listas INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(45),
    idtb_grupo INT,
    FOREIGN KEY (idtb_grupo) REFERENCES tb_grupos (idtb_grupos)
);

-- Cria a tabela de tarefas
CREATE TABLE tb_tarefas (
    id_tarefa INT AUTO_INCREMENT PRIMARY KEY,
    nome_tarefa VARCHAR(150) NOT NULL,
    descricao VARCHAR(150),
    concluida BOOLEAN DEFAULT false,
    dificuldade VARCHAR(45),
    prioridade INT,
    valor_pontos INT,
    id_usuario INT,
    idtb_listas INT,
    FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario),
    FOREIGN KEY (idtb_listas) REFERENCES tb_listas (idtb_listas)
);

-- Cria a tabela de associação entre usuários e grupos
CREATE TABLE tb_usuarios_has_tb_grupos (
    usuarios_idtb_usuarios INT,
    grupos_idtb_grupos INT,
    administradores TINYINT,
    pontos INT,
    recompensas VARCHAR(150),
    PRIMARY KEY (usuarios_idtb_usuarios, grupos_idtb_grupos),
    FOREIGN KEY (usuarios_idtb_usuarios) REFERENCES tb_usuarios (id_usuario),
    FOREIGN KEY (grupos_idtb_grupos) REFERENCES tb_grupos (idtb_grupos)
);

-- Adiciona a chave estrangeira referenciando a tabela tb_listas
ALTER TABLE tb_tarefas ADD FOREIGN KEY (idtb_listas) REFERENCES tb_listas (idtb_listas);

-- Adiciona a chave estrangeira referenciando a tabela tb_usuarios na tabela tb_tarefas
ALTER TABLE tb_tarefas ADD CONSTRAINT fk_usuarios FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

-- Insere alguns dados iniciais na tabela de tarefas
INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos, id_usuario) VALUES ('Tarefa 1', 'Descrição da tarefa 1', 'easy', 1, 10, 1);
INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos, id_usuario) VALUES ('Tarefa 2', 'Descrição da tarefa 2', 'medium', 2, 20, 1);
