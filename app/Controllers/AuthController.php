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
     * 📥 PROCESSA O CADASTRO INICIAL (POST)
     */
    public function cadastrarUsuario(): void {
        $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $tipo = filter_input(INPUT_POST, 'tipo_cadastro', FILTER_DEFAULT) ?? 'cliente';

        if (!$name || !$email || empty($password)) {
            $_SESSION['erro_auth'] = "Preencha todos os campos corretamente.";
            header('Location: ' . AppConfig::url('/cadastrar'));
            exit;
        }

        $db = Database::getInstance();

        // Verifica se o e-mail já existe no sistema
        $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            $_SESSION['erro_auth'] = "Este e-mail já está cadastrado.";
            header('Location: ' . AppConfig::url('/cadastrar'));
            exit;
        }

        // Criptografia profissional Bcrypt para a senha
                // Criptografia profissional Bcrypt para a senha
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $db = Database::getInstance();

        // 1. Insere o usuário ganhando as 20 moedas padrão automaticamente pelo banco (definido na estrutura)
        $stmt = $db->prepare("INSERT INTO users (name, email, password, tipo_cadastro) VALUES (?, ?, ?, ?)");
        $sucesso = $stmt->execute([$name, $email, $passwordHash, $tipo]);

        if ($sucesso) {
            $lastId = $db->lastInsertId();

            // 2. Grava a movimentação de entrada das moedas no histórico por segurança
            $stmtHist = $db->prepare("INSERT INTO moedas_historico (user_id, quantidade, descricao) VALUES (?, 20, 'Bônus de boas-vindas')");
            $stmtHist->execute([$lastId]);

            // 3. Dispara o e-mail de boas-vindas com o manual de como usar as moedas
            \App\Services\EmailService::enviarBoasVindas($name, $email);

            $_SESSION['sucesso_auth'] = "Conta criada com sucesso com 20 moedas grátis! Faça seu login.";
            header('Location: ' . AppConfig::url('/login'));
        } else {
            $_SESSION['erro_auth'] = "Erro interno ao criar conta. Tente novamente.";
            header('Location: ' . AppConfig::url('/cadastrar'));
        }
        exit;

    }

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
            
            // Define o painel inicial padrão escolhido pelo usuário no banco
            $_SESSION['tipo_view'] = $user['tipo_cadastro'] ?? 'cliente';

            // 3. 🧠 MÁGICA DE PORTAS: Verifica se ele possui perfil cadastrado na tabela de freelas
            $stmtProf = $db->prepare("SELECT id FROM professional_profiles WHERE user_id = ?");
            $stmtProf->execute([$user['id']]);
            $professional = $stmtProf->fetch(PDO::FETCH_ASSOC);

            if ($professional) {
                $_SESSION['is_professional'] = true;
                $_SESSION['professional_id'] = $professional['id'];
            } else {
                $_SESSION['is_professional'] = false;
                $_SESSION['professional_id'] = null;
            }

            header('Location: ' . AppConfig::url('/dashboard')); // Mudar para /dashboard futuramente
            exit;
        } else {
            $_SESSION['erro_auth'] = "E-mail ou senha inválidos.";
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }
    }

    /**
     * 🚪 LOGOUT / SAIR
     */
    public function sair(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header('Location: ' . AppConfig::url('/login'));
        exit;
    }
}
