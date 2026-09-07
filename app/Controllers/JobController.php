<?php

namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;


namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;

class JobController {

    /**
     * Valida a segurança básica de login para o ecossistema de vagas
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 🚀 CORRIGIDO: Agora apenas verifica se o usuário está logado. 
        // Freelancers e Clientes podem acessar os métodos de listagem e detalhes!
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }
    }

    /**
     * Exibe a tela de publicar vaga
     */
    public function mostrarPublicar(): void {
        // Trava interna de segurança: Apenas contratantes publicam vagas
        if (isset($_SESSION['tipo_view']) && $_SESSION['tipo_view'] === 'freelancer') {
            header('Location: ' . AppConfig::url('/dashboard'));
            exit;
        }

        $titulo = "Publicar Projeto | 8ou80.site";
        $viewPath = __DIR__ . '/../Views/vagas_publicar.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

    /**
     * 📥 PROCESSA O CADASTRO DA VAGA (POST)
     */
    public function salvarVaga(): void {
        // Trava interna de segurança: Impede o freelancer de forçar uma publicação via requisição POST
        if (isset($_SESSION['tipo_view']) && $_SESSION['tipo_view'] === 'freelancer') {
            header('Location: ' . AppConfig::url('/dashboard'));
            exit;
        }

        $userId = $_SESSION['user_id'];
        $title = filter_input(INPUT_POST, 'title', FILTER_DEFAULT);
        $description = filter_input(INPUT_POST, 'description', FILTER_DEFAULT);
        $category = filter_input(INPUT_POST, 'category', FILTER_DEFAULT);
        $budget = filter_input(INPUT_POST, 'budget', FILTER_VALIDATE_FLOAT);

        if (!$title || !$description || !$category) {
            $_SESSION['erro_vaga'] = "Preencha todos os campos obrigatórios (*).";
            header('Location: ' . AppConfig::url('/vagas/publicar'));
            exit;
        }

        $db = Database::getInstance();

        $stmt = $db->prepare("INSERT INTO jobs (user_id, title, description, category, budget) VALUES (?, ?, ?, ?, ?)");
        $sucesso = $stmt->execute([$userId, $title, $description, $category, $budget]);

        if ($sucesso) {
            header('Location: ' . AppConfig::url('/dashboard?vaga=publicada'));
        } else {
            $_SESSION['erro_vaga'] = "Erro interno ao publicar vaga. Tente novamente.";
            header('Location: ' . AppConfig::url('/vagas/publicar'));
        }
        exit;
    }

    // ... Mantenha os seus métodos seguintes (listarVagas, detalhesVaga, salvarProposta) idênticos ...

        /**
     * 💼 LISTAGEM: Exibe todas as vagas disponíveis no ecossistema
     */
    public function listarVagas(): void {
        $titulo = "Vagas Disponíveis | 8ou80.site";
        $db = Database::getInstance();

        // Traz as vagas abertas cruzando com o nome do cliente que postou
        $stmt = $db->query("SELECT j.*, u.name as nome_cliente 
                            FROM jobs j 
                            INNER JOIN users u ON j.user_id = u.id 
                            WHERE j.status = 'aberto' 
                            ORDER BY j.is_featured DESC, j.id DESC");
        $vagas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $viewPath = __DIR__ . '/../Views/vagas_lista.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

    /**
     * 🔍 DETALHES: Tela para ver o escopo completo e preencher a proposta
     */
    public function detalhesVaga(): void {
        $idJob = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$idJob) {
            header('Location: ' . AppConfig::url('/vagas'));
            exit;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT j.*, u.name as nome_cliente FROM jobs j INNER JOIN users u ON j.user_id = u.id WHERE j.id = ?");
        $stmt->execute([$idJob]);
        $vaga = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$vaga) {
            header('Location: ' . AppConfig::url('/vagas'));
            exit;
        }

        $titulo = "Enviar Proposta | " . $vaga['title'];
        $viewPath = __DIR__ . '/../Views/vagas_detalhes.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

    /**
     * 📥 ENVIAR PROPOSTA (POST)
     */
        /**
     * 📥 ENVIAR PROPOSTA (POST) - COM CONSUMO REAL DE MOEDAS
     */
    public function salvarProposta(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $jobId = filter_input(INPUT_POST, 'job_id', FILTER_VALIDATE_INT);
        $coverLetter = filter_input(INPUT_POST, 'cover_letter', FILTER_DEFAULT);
        $bidAmount = filter_input(INPUT_POST, 'bid_amount', FILTER_VALIDATE_FLOAT);
        $deliveryDays = filter_input(INPUT_POST, 'delivery_days', FILTER_VALIDATE_INT);
        $userId = $_SESSION['user_id'] ?? null;

        if (!$jobId || !$coverLetter || !$bidAmount || !$deliveryDays || !$userId) {
            $_SESSION['erro_proposta'] = "Preencha todos os campos da proposta.";
            header('Location: ' . AppConfig::url('/vagas/detalhes?id=' . $jobId));
            exit;
        }

        $db = Database::getInstance();

        // 🧠 1. VERIFICA SE O FREELANCER TEM SALDO DE MOEDAS SUFICIENTE
        $stmtUser = $db->prepare("SELECT moedas FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $userData = $stmtUser->fetch(\PDO::FETCH_ASSOC);
        $saldoAtual = $userData['moedas'] ?? 0;

        if ($saldoAtual < 2) {
            $_SESSION['erro_proposta'] = "🚫 Saldo insuficiente! Você precisa de pelo menos 2 moedas para enviar propostas.";
            header('Location: ' . AppConfig::url('/vagas/detalhes?id=' . $jobId));
            exit;
        }

        // Descobre o ID do perfil profissional do freelancer
        $stmtProf = $db->prepare("SELECT id FROM professional_profiles WHERE user_id = ?");
        $stmtProf->execute([$userId]);
        $profile = $stmtProf->fetch(\PDO::FETCH_ASSOC);

        if (!$profile) {
            $_SESSION['erro_proposta'] = "Você precisa ativar seu perfil freelancer primeiro.";
            header('Location: ' . AppConfig::url('/vagas/detalhes?id=' . $jobId));
            exit;
        }

        // Inicia uma Transação no banco para garantir que as moedas só saiam se a proposta for gravada
        $db->beginTransaction();

        try {
            // A. Insere a proposta comercial
            $stmt = $db->prepare("INSERT INTO proposals (job_id, professional_profile_id, cover_letter, bid_amount, delivery_days) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$jobId, $profile['id'], $coverLetter, $bidAmount, $deliveryDays]);

            // B. Deduz 2 moedas do saldo do usuário
            $stmtDedução = $db->prepare("UPDATE users SET moedas = moedas - 2 WHERE id = ?");
            $stmtDedução->execute([$userId]);

            // C. Grava a movimentação no extrato histórico
            $stmtHist = $db->prepare("INSERT INTO moedas_historico (user_id, quantidade, descricao) VALUES (?, -2, ?)");
            $stmtHist->execute([$userId, "Envio de proposta para a vaga #{$jobId}"]);

            $db->commit();
            header('Location: ' . AppConfig::url('/dashboard?proposta=enviada'));
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['erro_proposta'] = "Erro interno ao processar moedas. Tente novamente.";
            header('Location: ' . AppConfig::url('/vagas/detalhes?id=' . $jobId));
        }
        exit;
    }


}
