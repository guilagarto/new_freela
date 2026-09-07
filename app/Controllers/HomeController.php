<?php

namespace App\Controllers;

// Importações oficiais limpas via Autoload PSR-4
use App\Config\AppConfig;
use App\Core\Database;

class HomeController {

    /**
     * Renderiza a página inicial do ecossistema
     */
       /**
     * Renderiza a página inicial pública e dinâmica do ecossistema
     */
        /**
     * Renderiza a página inicial pública e dinâmica do ecossistema
     */
    public function index(): void {
        $titulo = "8ou80 | Encontre Profissionais Liberais e Vagas Freelancer";
        
        try {
            $db = \App\Core\Database::getInstance();
            
            // 📊 1. Busca os contadores reais
            $stmtVagas = $db->query("SELECT COUNT(*) as total FROM jobs WHERE status = 'aberto'");
            $totalVagas = $stmtVagas->fetch()['total'] ?? 0;

            $stmtFreelas = $db->query("SELECT COUNT(*) as total FROM professional_profiles");
            $totalFreelas = $stmtFreelas->fetch()['total'] ?? 0;

            // 🧰 2. BUSCA AS CATEGORIAS REAIS DO BANCO (Mágica Dinâmica)
            // Seleciona as categorias distintas que possuem vagas abertas no momento
            $stmtCat = $db->query("SELECT category, COUNT(*) as total_vagas 
                                   FROM jobs 
                                   WHERE status = 'aberto' 
                                   GROUP BY category 
                                   ORDER BY total_vagas DESC LIMIT 4");
            $categoriesPopulares = $stmtCat->fetchAll(\PDO::FETCH_ASSOC);

            // 💼 3. Traz as 3 vagas mais recentes
            $stmtMural = $db->query("SELECT j.*, u.name as nome_cliente 
                                     FROM jobs j 
                                     INNER JOIN users u ON j.user_id = u.id 
                                     WHERE j.status = 'aberto' 
                                     ORDER BY j.id DESC LIMIT 3");
            $vagasRecentes = $stmtMural->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\Exception $e) {
            $totalVagas = 0;
            $totalFreelas = 0;
            $categoriesPopulares = [];
            $vagasRecentes = [];
        }

        $viewPath = __DIR__ . '/../Views/home.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        }
    }



    /**
     * Testa a conexão real com o banco de dados
     */
    public function testeBanco(): void {
        try {
            // Puxa a conexão única via Singleton gerenciada pelo Autoload
            $db = Database::getInstance();
            
            // Realiza uma consulta simples para contar os dados existentes
            $stmt = $db->query("SELECT COUNT(*) as total FROM users");
            $resultado = $stmt->fetch();
            
            echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
            echo "<h1 style='color:#16a34a;'>✅ Conexão com o Banco bem-sucedida!</h1>";
            echo "<p style='color:#475569;'>O sistema leu a tabela <strong>users</strong> do banco <strong>freela_db</strong>.</p>";
            echo "<p style='color:#64748b;'>Total de registros encontrados: <strong>" . $resultado['total'] . "</strong></p>";
            echo "<a href='" . AppConfig::url('/') . "' style='color:#4f46e5; text-decoration:none; font-weight:bold;'>← Voltar para a Home</a>";
            echo "</div>";
        } catch (\Exception $e) {
            echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
            echo "<h1 style='color:#dc3545;'>❌ Falha ao ler dados:</h1>";
            echo "<p style='color:#475569;'>" . $e->getMessage() . "</p>";
            echo "</div>";
        }
    }
        /**
     * Exibe a listagem de profissionais (Rota temporária)
     */
    public function profissionais(): void {
        echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
        echo "<h1 style='color:#4f46e5;'>🔍 Página de Busca de Profissionais</h1>";
        echo "<p style='color:#64748b;'>Esta rota foi ativada com sucesso! Logo mais criaremos os filtros dinâmicos aqui.</p>";
        echo "<a href='" . \App\Config\AppConfig::url('/') . "' style='color:#334155; font-weight:bold;'>← Voltar para a Home</a>";
        echo "</div>";
    }

}
