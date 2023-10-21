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
    
-- Cria a tabela de grupos
CREATE TABLE tb_grupos (
    idtb_grupos INT PRIMARY KEY AUTO_INCREMENT,
    codigo_convite VARCHAR(45),
    nome VARCHAR(45)
);
 protected function save()
    {
        // Obter os dados JSON brutos do corpo da requisição
        $jsonString = file_get_contents('php://input');
        $requestData = json_decode($jsonString, true); // Converter JSON para um array associativo

        $groupName = $requestData['groupName'];
        $groupCode = $requestData['groupCode'];
        $userID = $requestData['userID'];

        $grupo = new Grupo();
        $grupo->setNome($groupName);
        $grupo->setCodigo_convite($groupCode);
        $grupo->setId_usuario($userID);

        $erros = $this->grupoService->validarDados($grupo);
        if (empty($erros)) {
            try {
                // Inserir a lista no banco de dados
                $this->grupoDao->insertGrupo($grupo);

                // Enviar mensagem de sucesso como JSON
                $response = array(
                    'ok' => true,
                    'message' => 'Grupo salvo com sucesso.'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } catch (PDOException $e) {
                // Se ocorrer um erro durante a inserção, enviar mensagem de erro como JSON
                $response = array(
                    'ok' => false,
                    'message' => 'Ocorreu um erro durante a requisição',
                    'error' => $e->getMessage() // Usar a mensagem de erro da exceção
                );

                // Enviar a resposta como JSON
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        } else {
            // Se houver erros de validação, enviar a resposta com os erros como JSON
            $response = array(
                'ok' => true,
                'message' => 'Ocorreram erros de validação',
                'errors' => $erros // Incluir os erros de validação na resposta
            );

            // Enviar a resposta como JSON
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
-- Cria a tabela de listas
CREATE TABLE tb_listas (
    idtb_listas INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(45),
    idtb_usuario INT,
    idtb_grupo INT,
    FOREIGN KEY (idtb_grupo) REFERENCES tb_grupos (idtb_grupos),
    FOREIGN KEY (idtb_usuario) REFERENCES tb_usuarios (id_usuario)
);

INSERT INTO tb_listas (nome, idtb_usuario) VALUES ('Lista 01', 1);
INSERT INTO tb_listas (nome, idtb_usuario) VALUES ('Lista 02', 1);

INSERT INTO tb_listas (nome, idtb_usuario) VALUES ('Lista 01', 2);
INSERT INTO tb_listas (nome, idtb_usuario) VALUES ('Lista 02', 2);

-- Cria a tabela de tarefas
CREATE TABLE tb_tarefas (
    id_tarefa INT AUTO_INCREMENT PRIMARY KEY,
    nome_tarefa VARCHAR(150) NOT NULL,
    descricao VARCHAR(150),
    concluida BOOLEAN DEFAULT false,
    dificuldade INT,
    prioridade INT,
    valor_pontos INT,
    data_criacao TIMESTAMP DEFAULT (CURRENT_TIMESTAMP),
    id_usuario INT,
    idtb_listas INT,
    FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario),
    FOREIGN KEY (idtb_listas) REFERENCES tb_listas (idtb_listas)
);

-- Cria a tabela de associação entre usuários e grupos
CREATE TABLE tb_grupos_usuarios (
    id_grupos_usuarios INT NOT NULL AUTO_INCREMENT,
    id_grupo INT,
    id_usuario INT,
    administrador TINYINT,
    pontos INT,
    PRIMARY KEY (id_grupos_usuarios),
    FOREIGN KEY (id_grupo) REFERENCES tb_grupos (idtb_grupos),
    FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario), 
    CONSTRAINT uc_grupos_usuarios UNIQUE (id_usuario, id_grupo) 
);

-- Adiciona a chave estrangeira referenciando a tabela tb_listas
ALTER TABLE tb_tarefas ADD FOREIGN KEY (idtb_listas) REFERENCES tb_listas (idtb_listas);

-- Adiciona a chave estrangeira referenciando a tabela tb_usuarios na tabela tb_tarefas
ALTER TABLE tb_tarefas ADD CONSTRAINT fk_usuarios FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

-- Insere alguns dados iniciais na tabela de tarefas
INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos, id_usuario, idtb_listas) VALUES ('Tarefa 1', 'Descrição da tarefa 1', 1, 1, 150, 1, 1);
INSERT INTO tb_tarefas (nome_tarefa, descricao, dificuldade, prioridade, valor_pontos, id_usuario, idtb_listas) VALUES ('Tarefa 2', 'Descrição da tarefa 2', 2, 2, 50, 1, 1);

CREATE TABLE tb_rewards (
    id_reward INT AUTO_INCREMENT PRIMARY KEY,
    reward_name VARCHAR(80),
    reward_cost INT,
    reward_unities INT,
    claimed_times INT,
    id_user INT,
    id_group INT,
    FOREIGN KEY (id_user) REFERENCES tb_usuarios (id_usuario),
    FOREIGN KEY (id_group) REFERENCES tb_grupos (idtb_grupos)
);

INSERT INTO tb_rewards (reward_name, reward_cost, id_user, reward_unities, claimed_times) VALUES ('Sorvete', 50, 1, 5, 2);
INSERT INTO tb_rewards (reward_name, reward_cost, id_user, reward_unities, claimed_times) VALUES ('Maconha', 150, 1, 6, 2);

