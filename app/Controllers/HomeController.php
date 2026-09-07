<?php

namespace App\Controllers;

// Importações oficiais limpas via Autoload PSR-4
use App\Config\AppConfig;
use App\Core\Database;

class HomeController {

    /**
     * Renderiza a página inicial do ecossistema
     */
    public function index(): void {
        $titulo = "8ou80 | Conexão de Freelancers Profissionais";
        $viewPath = __DIR__ . '/../Views/home.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("<h1>Erro: A View home.php não foi encontrada na pasta app/Views/</h1>");
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
