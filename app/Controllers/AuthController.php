<?php

namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;
use PDO;

class AuthController {

    public function mostrarLogin(): void {
        $titulo = "Entrar | 8ou80.site";
        $viewPath = __DIR__ . '/../Views/login.php';
        if (file_exists($viewPath)) require_once $viewPath;
    }

    public function mostrarCadastro(): void {
        $titulo = "Criar Conta Grátis | 8ou80.site";
        $viewPath = __DIR__ . '/../Views/cadastro.php';
        if (file_exists($viewPath)) require_once $viewPath;
    }

    /**
       /**
     * 🔑 PROCESSA A AUTENTICAÇÃO / LOGIN ÚNICO (POST)
     */
    public function autenticarUsuario(): void {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || empty($password)) {
            $_SESSION['erro_auth'] = "Preencha o e-mail e a senha.";
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }

        $db = Database::getInstance();

        // 1. Busca o usuário principal
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Valida a senha criptografada
         if ($user && password_verify($password, $user['password'])) {
            
            // Inicializa as variáveis de sessão unificadas
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // =========================================================================
            // AJUSTE: Redireciona o usuário para a página de busca assim que faz login
            // =========================================================================
            header('Location: ' . AppConfig::url('/profissionais'));
            exit;
            
        } else {
            $_SESSION['erro_auth'] = "E-mail ou senha inválidos.";
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }
    }
    /**
     * 📥 FINALIZA A SESSÃO DO USUÁRIO (LOGOUT)
     */
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpa a sessão
        $_SESSION = array();

        if (ini_get("session_use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Redireciona de forma limpa usando a classe configurada para a Home
        header('Location: ' . AppConfig::url('/'));
        exit;
    }

    /**
     * 📥 PROCESSA O CADASTRO INICIAL (POST)
     */
        /**
     * 📥 PROCESSA O CADASTRO INICIAL (POST)
     */
    public function cadastrarUsuario(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        
        $tipo = 'unico'; 

        if (!$name || !$email || empty($password)) {
            $_SESSION['erro_auth'] = "Preencha todos os campos corretamente.";
            header('Location: ' . AppConfig::url('/cadastro'));
            exit;
        }

        $db = \App\Core\Database::getInstance();

        // Verifica se o e-mail já existe
        $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            $_SESSION['erro_auth'] = "Este e-mail já está cadastrado.";
            header('Location: ' . AppConfig::url('/cadastro'));
            exit;
        }

        // 1. Criptografia da senha (Organizado no local correto antes do Insert)
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // 2. Prepara e envia os dados para o banco de dados
        $stmt = $db->prepare("INSERT INTO users (name, email, password, tipo_cadastro) VALUES (?, ?, ?, ?)");
        $sucesso = $stmt->execute([$name, $email, $passwordHash, $tipo]);

        // 3. Agora sim, valida se a inserção deu sucesso!
        if ($sucesso) {
            $lastId = $db->lastInsertId();

            // Grava o bônus de moedas por segurança se a tabela existir
            try {
                $stmtHist = $db->prepare("INSERT INTO moedas_historico (user_id, quantidade, descricao) VALUES (?, 20, 'Bônus de boas-vindas')");
                $stmtHist->execute([lastId]);
            } catch (\PDOException $e) {
                // Se a tabela de moedas não existir, ignora
            }

            // MÁGICA DO LOGIN AUTOMÁTICO: Salva os dados na sessão imediatamente
            $_SESSION['user_id'] = $lastId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            $_SESSION['sucesso_auth'] = "Conta criada com sucesso! Seja bem-vindo.";
            
            // AJUSTE: Redireciona o novo usuário cadastrado e logado DIRETO para ver os profissionais
            header('Location: ' . AppConfig::url('/profissionais'));
            exit;
        } else {
            $_SESSION['erro_auth'] = "Erro interno ao criar conta. Tente novamente.";
            header('Location: ' . AppConfig::url('/cadastro'));
            exit;
        }
    }
} // Fechamento final da classe AuthController (Garante que fique apenas um no fim)



        // Criptografia da senha
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insere o usuário no banco
        $stmt = $db->prepare("INSERT INTO users (name, email, password, tipo_cadastro) VALUES (?, ?, ?, ?)");
        $sucesso = $stmt->execute([$name, $email, $passwordHash, $tipo]);

        if ($sucesso) {
            $lastId = $db->lastInsertId();

            // Grava o bônus de moedas por segurança se a tabela existir
            try {
                $stmtHist = $db->prepare("INSERT INTO moedas_historico (user_id, quantidade, descricao) VALUES (?, 20, 'Bônus de boas-vindas')");
                $stmtHist->execute([$lastId]);
            } catch (\PDOException $e) {
                // Se a tabela de moedas não existir no momento, ignora e continua o fluxo
            }

            // MÁGICA DO LOGIN AUTOMÁTICO: Salva os dados na sessão imediatamente
            $_SESSION['user_id'] = $lastId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            $_SESSION['sucesso_auth'] = "Conta criada com sucesso! Seja bem-vindo.";
            
            // Joga o usuário logado direto na Home
            header('Location: ' . AppConfig::url('/'));
            exit;
        } else {
            $_SESSION['erro_auth'] = "Erro interno ao criar conta. Tente novamente.";
            header('Location: ' . AppConfig::url('/cadastro'));
            exit;
        }
    }
} // Fechamento final da classe AuthController

