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
    /**
     * Renderiza o Painel do Freelancer buscando dados de 'users' e 'professional_profiles'
     */
    public function freelaIndex(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . \App\Config\AppConfig::url('/login'));
            exit;
        }

        try {
            $db = \App\Core\Database::getInstance();
            $userId = $_SESSION['user_id'];
            
            // 1. Busca os dados principais da conta
            $stmtUser = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmtUser->execute([$userId]);
            $usuario = $stmtUser->fetch(\PDO::FETCH_ASSOC);

            // 2. Busca os dados profissionais do perfil do freelancer
            $stmtProfile = $db->prepare("SELECT * FROM professional_profiles WHERE user_id = ?");
            $stmtProfile->execute([$userId]);
            $perfil = $stmtProfile->fetch(\PDO::FETCH_ASSOC) ?: []; // Array vazio caso seja o primeiro acesso

            // Renderiza o arquivo visual passando as duas variáveis preenchidas
            require_once __DIR__ . '/../Views/dashboard_freela.php';
        } catch (\PDOException $e) {
            echo "Erro ao carregar dados do painel: " . $e->getMessage();
        }
    }

    public function salvarPerfilFreela(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: ' . \App\Config\AppConfig::url('/login'));
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        // Captura e limpa os campos enviados pelo formulário HTML
        $name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $category = filter_input(INPUT_POST, 'category', FILTER_DEFAULT);
        $bio = filter_input(INPUT_POST, 'bio', FILTER_DEFAULT);
        $price = filter_input(INPUT_POST, 'price_per_hour', FILTER_VALIDATE_FLOAT);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_DEFAULT);
        $portfolio = filter_input(INPUT_POST, 'portfolio_text', FILTER_DEFAULT);

        try {
            $db = \App\Core\Database::getInstance();
            
            // 1. Atualiza o nome principal na tabela 'users'
            $stmtUser = $db->prepare("UPDATE users SET name = ? WHERE id = ?");
            $stmtUser->execute([$name, $userId]);
            $_SESSION['user_name'] = $name; // Atualiza o nome no menu superior na hora

            // 2. Verifica se o perfil profissional já existe no banco
            $stmtCheck = $db->prepare("SELECT id FROM professional_profiles WHERE user_id = ?");
            $stmtCheck->execute([$userId]);
            $existe = $stmtCheck->fetch();

            if ($existe) {
                // Se já existe, faz um UPDATE
                $query = "UPDATE professional_profiles SET category = ?, bio = ?, price_per_hour = ?, phone = ?, portfolio_text = ? WHERE user_id = ?";
                $stmtProfile = $db->prepare($query);
                $stmtProfile->execute([$category, $bio, $price, $phone, $portfolio, $userId]);
            } else {
                // Se é a primeira vez, faz um INSERT
                $query = "INSERT INTO professional_profiles (user_id, category, bio, price_per_hour, phone, portfolio_text) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtProfile = $db->prepare($query);
                $stmtProfile->execute([$userId, $category, $bio, $price, $phone, $portfolio]);
            }

            $_SESSION['sucesso_painel'] = "Perfil atualizado com sucesso!";
            header('Location: ' . \App\Config\AppConfig::url('/dashboard-freela'));
            exit;
        } catch (\PDOException $e) {
            echo "Erro ao salvar perfil: " . $e->getMessage();
            exit;
        }
    }


}
