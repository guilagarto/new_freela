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
       /**
     * 🔍 ENDPOINT API: Retorna profissionais com média de avaliações em tempo real (JSON)
     */
   public function filtrar(): void {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json; charset=utf-8');

    $nome = filter_input(INPUT_GET, 'nome', FILTER_DEFAULT) ?? '';
    $tecnologia = filter_input(INPUT_GET, 'tecnologia', FILTER_DEFAULT) ?? '';

    $nome = trim($nome);
    $tecnologia = trim($tecnologia);

    try {
        $db = Database::getInstance();

        // QUERY AJUSTADA: Usando r.rating e vinculando r.professional_id com p.id
        $sql = "SELECT p.id, p.user_id, p.category, p.bio, u.name as nome_usuario, 
                       IFNULL(AVG(r.rating), 0) as media_estrelas,
                       COUNT(r.id) as total_avaliacoes
                FROM professional_profiles p
                INNER JOIN users u ON p.user_id = u.id
                LEFT JOIN reviews r ON p.id = r.professional_id
                WHERE 1=1";

        $params = [];

        if (!empty($nome)) {
            $sql .= " AND (u.name LIKE ? OR p.category LIKE ?)";
            $params[] = "%{$nome}%";
            $params[] = "%{$nome}%";
        }

        if (!empty($tecnologia)) {
            $sql .= " AND p.category LIKE ?";
            $params[] = "%{$tecnologia}%";
        }

        // Agrupamento completo com as colunas selecionadas para evitar erros estritos de SQL
        $sql .= " GROUP BY p.id, p.user_id, p.category, p.bio, u.name";
        $sql .= " ORDER BY media_estrelas DESC, p.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $json = json_encode($resultados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);

        if ($json === false) {
            throw new Exception("Falha na codificação JSON: " . json_last_error_msg());
        }

        echo $json;

    } catch (Exception $e) {
        error_log("Erro no filtro de profissionais: " . $e->getMessage());
        echo json_encode([]);
    }

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
