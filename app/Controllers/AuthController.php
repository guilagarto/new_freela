<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;
use PDO;

class AuthController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Exibe a página visual de login
     */
    public function mostrarLogin(): void {
        require_once __DIR__ . '/../Views/login.php';
    }

    /**
     * Exibe a página visual de cadastro
     */
    public function mostrarCadastro(): void {
        require_once __DIR__ . '/../Views/cadastro.php';
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

        try {
            $db = Database::getInstance();

            // 1. Busca o usuário principal
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // 2. Valida a senha criptografada e redireciona para /profissionais
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                header('Location: ' . AppConfig::url('/profissionais'));
    exit;
            } else {
                $_SESSION['erro_auth'] = "E-mail ou senha inválidos.";
                header('Location: ' . AppConfig::url('/login'));
                exit;
            }
        } catch (\PDOException $e) {
            $_SESSION['erro_auth'] = "Erro de conexão com o banco.";
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }
    }

    /**
     * 🚪 FINALIZA A SESSÃO DO USUÁRIO (LOGOUT)
     */
    public function logout(): void {
        $_SESSION = array();

        if (ini_get("session_use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        header('Location: ' . AppConfig::url('/'));
        exit;
    }

    /**
     * 📥 PROCESSA O CADASTRO INICIAL E FAZ LOGIN AUTOMÁTICO (POST)
     */
    public function cadastrarUsuario(): void {
        $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';
        $tipo = 'unico'; 

        if (!$name || !$email || empty($password)) {
            $_SESSION['erro_auth'] = "Preencha todos os campos corretamente.";
            header('Location: ' . AppConfig::url('/profissionais'));
    exit;
        }

        try {
            $db = Database::getInstance();

            // Verifica se o e-mail já existe no sistema
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmtCheck->execute([$email]);
            if ($stmtCheck->fetch()) {
                $_SESSION['erro_auth'] = "Este e-mail já está cadastrado.";
                header('Location: ' . AppConfig::url('/cadastro'));
                exit;
            }

            // Criptografia profissional Bcrypt para a senha
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            // Insere o usuário na tabela principal do banco
            $stmt = $db->prepare("INSERT INTO users (name, email, password, tipo_cadastro) VALUES (?, ?, ?, ?)");
            $sucesso = $stmt->execute([$name, $email, $passwordHash, $tipo]);

            if ($sucesso) {
                $lastId = $db->lastInsertId();

                // Grava a movimentação de moedas bônus por segurança
                try {
                    $stmtHist = $db->prepare("INSERT INTO moedas_historico (user_id, quantidade, descricao) VALUES (?, 20, 'Bônus de boas-vindas')");
                    $stmtHist->execute([$lastId]);
                } catch (\PDOException $e) {}

                // MÁGICA DO LOGIN AUTOMÁTICO: Inicia a sessão na hora!
                $_SESSION['user_id'] = $lastId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;

                $_SESSION['sucesso_auth'] = "Conta criada com sucesso! Seja bem-vindo.";
                
                // Redireciona o usuário já Logado direto para ver a vitrine de profissionais
                header('Location: ' . AppConfig::url('/profissionais'));
                exit;
            } else {
                $_SESSION['erro_auth'] = "Erro interno ao criar conta. Tente novamente.";
                header('Location: ' . AppConfig::url('/cadastro'));
                exit;
            }
        } catch (\PDOException $e) {
            $_SESSION['erro_auth'] = "Erro no banco de dados: " . $e->getMessage();
            header('Location: ' . AppConfig::url('/cadastro'));
            exit;
        }
    }
}
