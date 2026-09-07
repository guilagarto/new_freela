<?php

namespace App\Controllers;

use App\Config\AppConfig;
use App\Core\Database;

class DashboardController {

    /**
     * Construtor para proteger a rota do Dashboard
     */
    public function __construct() {
        // Se o usuário não tiver uma sessão ativa, barra o acesso imediatamente
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . AppConfig::url('/login'));
            exit;
        }
    }

    /**
     * Renderiza o Painel correspondente ao tipo de visão ativa
     */
    public function index(): void {
        $titulo = "Painel de Controle | 8ou80.site";
        
        // Define qual visão exibir com base na sessão
        $tipoVisao = $_SESSION['tipo_view'] ?? 'cliente';

        if ($tipoVisao === 'freelancer') {
            $viewPath = __DIR__ . '/../Views/dashboard_freela.php';
        } else {
            $viewPath = __DIR__ . '/../Views/dashboard_cliente.php';
        }

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("<h1>Erro: A View do Dashboard não foi encontrada.</h1>");
        }
    }

    /**
     * 🔄 INTERRUPTOR DE PERFIL: Alterna a visão em tempo real
     */
    public function alternarPerfil(): void {
        $novoTipo = filter_input(INPUT_GET, 'tipo', FILTER_DEFAULT);

        if ($novoTipo === 'cliente' || $novoTipo === 'freelancer') {
            // Atualiza a sessão temporária com a preferência de tela atual
            $_SESSION['tipo_view'] = $novoTipo;

            // 👉 MELHORIA PROFISSIONAL (Opcional): Salva a escolha no banco para lembrar no próximo login
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE users SET tipo_cadastro = ? WHERE id = ?");
            $stmt->execute([$novoTipo, $_SESSION['user_id']]);
        }

        // Recarrega o painel com a nova interface ativada
        header('Location: ' . AppConfig::url('/dashboard'));
        exit;
    }
}
