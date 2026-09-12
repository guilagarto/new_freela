<?php

namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;
use PDO;

class DashboardController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }
    }

    /**
     * Exibe o Painel Único Integrado
     */
    public function index(): void {
        $titulo = "Meu Painel Central | 8ou80.site";
        $userId = $_SESSION['user_id'];
        $db = Database::getInstance();

        // 1. Dados atualizados do Usuário (Saldo de Moedas, Telefone, etc)
        $stmtUser = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // 2. Perfil Freelancer (se houver cadastrado)
        $stmtProf = $db->prepare("SELECT * FROM professional_profiles WHERE user_id = ?");
        $stmtProf->execute([$userId]);
        $perfilFreela = $stmtProf->fetch(PDO::FETCH_ASSOC);

        // 3. Vagas que este usuário POSTOU (Como Contratante)
        $stmtJobs = $db->prepare("SELECT * FROM jobs WHERE user_id = ? ORDER BY id DESC");
        $stmtJobs->execute([$userId]);
        $minhasVagas = $stmtJobs->fetchAll(PDO::FETCH_ASSOC);

        // 4. Propostas que este usuário SUBMETEU (Como Freelancer)
        $stmtProposals = $db->prepare("
            SELECT p.*, j.title as titulo_vaga 
            FROM proposals p 
            INNER JOIN professional_profiles pp ON p.professional_profile_id = pp.id 
            INNER JOIN jobs j ON p.job_id = j.id
            WHERE pp.user_id = ? 
            ORDER BY p.id DESC
        ");
        $stmtProposals->execute([$userId]);
        $minhasPropostas = $stmtProposals->fetchAll(PDO::FETCH_ASSOC);


        $viewPath = __DIR__ . '/../Views/dashboard_unico.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

    /**
     * Atualiza os dados cadastrais do Perfil
     */
    public function atualizarPerfil(): void {
        $userId = $_SESSION['user_id'];
        $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT);
        $category = filter_input(INPUT_POST, 'category', FILTER_DEFAULT);
        $bio = filter_input(INPUT_POST, 'bio', FILTER_DEFAULT);

        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // Atualiza dados na tabela users
            $stmtUser = $db->prepare("UPDATE users SET name = ?, telefone = ? WHERE id = ?");
            $stmtUser->execute([$name, $telefone, $userId]);
            $_SESSION['user_name'] = $name; // Atualiza o nome na sessão atual

            // Atualiza ou insere na professional_profiles caso ele defina dados de freela
            if (!empty($category)) {
                $stmtCheck = $db->prepare("SELECT id FROM professional_profiles WHERE user_id = ?");
                $stmtCheck->execute([$userId]);
                
                if ($stmtCheck->fetch()) {
                    $stmtUpdate = $db->prepare("UPDATE professional_profiles SET category = ?, bio = ? WHERE user_id = ?");
                    $stmtUpdate->execute([$category, $bio, $userId]);
                } else {
                    $stmtInsert = $db->prepare("INSERT INTO professional_profiles (user_id, category, bio) VALUES (?, ?, ?)");
                    $stmtInsert->execute([$userId, $category, $bio]);
                }
                $_SESSION['is_professional'] = true;
            }

            $db->commit();
            header('Location: ' . AppConfig::url('/dashboard?perfil=atualizado'));
        } catch (\Exception $e) {
            $db->rollBack();
            header('Location: ' . AppConfig::url('/dashboard?erro=perfil'));
        }
        exit;
    }
        /**
     * 🗑️ EXCLUIR VAGA COM SEGURANÇA
     */
    public function excluirVaga(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $jobId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $userId = $_SESSION['user_id'] ?? null;

        if ($jobId && $userId) {
            $db = Database::getInstance();
            // Só executa o DELETE se o user_id da vaga bater com quem está logado
            $stmt = $db->prepare("DELETE FROM jobs WHERE id = ? AND user_id = ?");
            $stmt->execute([$jobId, $userId]);
        }

        header('Location: ' . AppConfig::url('/dashboard?vaga=excluida'));
        exit;
    }

        /* Abre a tela da Dashboard Unificada
    
    public function unicoIndex(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Bloqueio de segurança básico
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . \AppConfig\AppConfig::url('/login'));
            exit;
        }

        // Renderiza o arquivo visual que você já possui na pasta Views
        require_once __DIR__ . '/../Views/dashboard_unico.php';
    }

        /**
     * Renderiza o Painel do Freelancer buscando os dados reais do banco
     */
    public function freelaIndex(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Bloqueio de segurança padrão do TalentoHub
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . \App\Config\AppConfig::url('/login'));
            exit;
        }

        try {
            $db = \App\Core\Database::getInstance();
            
            // Busca os dados do usuário na tabela principal para preencher os inputs da tela
            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Carrega o arquivo visual tendo a variável $usuario disponível e preenchida
            require_once __DIR__ . '/../Views/dashboard_freela.php';
        } catch (\PDOException $e) {
            echo "Erro ao carregar dados do painel: " . $e->getMessage();
        }
    }


}
