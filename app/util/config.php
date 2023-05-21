<?php
#Nome do arquivo: config.php
#Objetivo: define constantes para serem utilizadas no projeto

//Banco de dados: conexão MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'taskflow');
define('DB_USER', 'root');
define('DB_PASSWORD', '@Kekkei2003');

//Caminho para adionar imagens, scripts e chamar páginas no sistema
//Deve ter o nome da pasta do projeto no servidor APACHE
define('BASEURL', '/taskflow/app'); //NÃO MUDA ESSA ROTA JOAO FILHO DA PUTA

//Nome do sistema
define('APP_NAME', 'Template do Projeto Integrador');

//Página inicial do sistema
define('HOME_PAGE', BASEURL . '/controller/HomeController.php?action=home');

//Página de logout do sistema
define('LOGIN_PAGE', BASEURL . '/controller/LoginController.php?action=login');

//Página de login do sistema
define('LOGOUT_PAGE', BASEURL . '/controller/LoginController.php?action=logout');

//Sessão do usuário
define('SESSAO_USUARIO_ID', "usuarioLogadoId");
define('SESSAO_USUARIO_NOME', "usuarioLogadoNome");
define('SESSAO_USUARIO_PAPEIS', "usuarioLogadoPapeis");




