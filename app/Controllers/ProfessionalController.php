<?php

namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;
use PDO;

class ProfessionalController {

    /**
     * Exibe a página principal de listagem de profissionais
     */
       /**
     * Exibe a página principal de listagem de profissionais com categorias dinâmicas
     */
    public function index(): void {
        $titulo = "Buscar Profissionais | 8ou80.site";
        
        try {
            $db = Database::getInstance();
            
            // 🧠 CAPTURA DINÂMICA: Busca todas as categorias reais que os profissionais digitaram no banco
            // Remove duplicadas usando o DISTINCT para não repetir o mesmo termo na lista
            $stmtCat = $db->query("SELECT DISTINCT category FROM professional_profiles WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
            $todasCategorias = $stmtCat->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (\Exception $e) {
            $todasCategorias = [];
        }

        $viewPath = __DIR__ . '/../Views/professionals_list.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }


    /**
     * 🔍 ENDPOINT API: Retorna profissionais filtrados em tempo real (JSON)
     */
        /**
     * 🔍 ENDPOINT API: Retorna profissionais filtrados em tempo real (JSON) - ATUALIZADO
     */
    public function filtrarApi(): void {
        header('Content-Type: application/json');
        
        $nome = filter_input(INPUT_GET, 'nome', FILTER_DEFAULT) ?? '';
        $tecnologia = filter_input(INPUT_GET, 'tecnologia', FILTER_DEFAULT) ?? '';

        $db = Database::getInstance();
        
        // Ajustado para buscar 'category' no lugar de 'title' e remover a coluna skills que não existe
        $sql = "SELECT p.id, p.user_id, p.category, p.bio, u.name as nome_usuario 
                FROM professional_profiles p
                INNER JOIN users u ON p.user_id = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($nome)) {
            $sql .= " AND (u.name LIKE ? OR p.category LIKE ?)";
            $params[] = "%$nome%";
            $params[] = "%$nome%";
        }

        // Como não há tabela ou coluna de habilidades direta, filtramos a tecnologia pela própria categoria do perfil
        if (!empty($tecnologia)) {
            $sql .= " AND p.category LIKE ?";
            $params[] = "%$tecnologia%";
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($resultados);
        exit;
    }


    /**
     * Exibe o formulário de "Completar Perfil" para virar freelancer
     */
    public function mostrarCompletarPerfil(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }

        $titulo = "Seja um Freelancer | 8ou80.site";
        $viewPath = __DIR__ . '/../Views/completar_perfil.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }

    /**
     * 📥 PROCESSA O CADASTRO DE FREELANCER (POST)
     */
        /**
     * 📥 PROCESSA O CADASTRO DE FREELANCER (POST) - CORRIGIDO PARA NOMES REAIS DAS COLUNAS
     */
       /**
     * 📥 PROCESSA O CADASTRO DE FREELANCER (POST) - CONFIGURADO COM A ESTRUTURA REAL DO BANCO
     */
    public function salvarPerfilProfessional(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $userId = $_SESSION['user_id'] ?? null;
        
        // Mapeia o campo "title" do formulário para salvar na coluna "category" do seu banco
        $category = filter_input(INPUT_POST, 'title', FILTER_DEFAULT); 
        $bio = filter_input(INPUT_POST, 'bio', FILTER_DEFAULT);

        if (!$userId || !$category) {
            $_SESSION['erro_perfil'] = "Preencha os campos obrigatórios (*).";
            header('Location: ' . AppConfig::url('/profissional/completar-perfil'));
            exit;
        }

        $db = Database::getInstance();

        // 🚀 VALIDAÇÃO EM NÍVEL DE ENGENHARIA: Insere estritamente nas colunas existentes na sua tabela
        $sql = "INSERT INTO professional_profiles (user_id, category, bio) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $sucesso = $stmt->execute([$userId, $category, $bio]);

        if ($sucesso) {
            // Atualiza a sessão para o sistema saber que ele agora também é profissional
            $_SESSION['is_professional'] = true;
            $_SESSION['tipo_view'] = 'freelancer'; // Muda a visão atual para a do painel freela

            header('Location: ' . AppConfig::url('/dashboard'));
        } else {
            $_SESSION['erro_perfil'] = "Erro interno ao salvar perfil.";
            header('Location: ' . AppConfig::url('/profissional/completar-perfil'));
        }
        exit;
    }

}
