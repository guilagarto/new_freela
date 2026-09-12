<?php
// app/Controllers/ChatController.php

namespace App\Controllers;

use App\Core\Database;

class ChatController {
    
    /**
     * O construtor garante que a sessão do PHP esteja ativa em qualquer
     * chamada vinda do chat (seja abrindo a página ou via requisições assíncronas do JS).
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Carrega e renderiza a tela visual do chat
     */
      /**
     * Carrega e renderiza a tela visual do chat recebendo o ID dinâmico da rota
     */
    public function index($id = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        // Se o roteador não passou o ID por parâmetro na função, tenta pegar de um parâmetro padrão do seu Router
        // NOTA: Isso garante que o valor '5' seja armazenado na variável $destinatario_id
        $destinatario_id = $id ?? $_GET['id'] ?? null;

        // Inclui a View passando o ID adiante
        require_once __DIR__ . '/../Views/chat.php';
    }


    /**
     * Recebe os dados via POST assíncrono (JavaScript) e grava a mensagem no Banco de Dados
     */
    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $remetente = $_SESSION['user_id'] ?? null;
            $destinatario = isset($_POST['destinatario_id']) ? (int)$_POST['destinatario_id'] : null;
            $mensagem = isset($_POST['mensagem']) ? htmlspecialchars(trim($_POST['mensagem'])) : null;

            if ($remetente && $destinatario && $mensagem !== '') {
                try {
                    $db = Database::getInstance(); 
                    
                    $stmt = $db->prepare("INSERT INTO mensagens_chat (remetente_id, destinatario_id, mensagem) VALUES (?, ?, ?)");
                    $stmt->execute([$remetente, $destinatario, $mensagem]);

                    echo json_encode(['status' => 'success']);
                    exit;
                } catch (\PDOException $e) {
                    echo json_encode(['status' => 'error', 'reason' => 'Erro no banco: ' . $e->getMessage()]);
                    exit;
                }
            }
        }
        
        echo json_encode(['status' => 'error', 'reason' => 'Dados inválidos ou sessão expirada']);
    }

    /**
     * Retorna o histórico de mensagens trocadas entre os dois usuários em formato JSON (Via GET)
     */
        public function buscarMensagens() {
        // Altere para aceitar apenas POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            exit;
        }

        $remetente = $_SESSION['user_id'] ?? null;
        
        // CORREÇÃO: Captura usando o INPUT_POST tradicional que seu roteador aceita
        $destinatario = filter_input(INPUT_POST, 'destinatario_id', FILTER_VALIDATE_INT);

        if (!$remetente || !$destinatario) {
            echo json_encode([]);
            exit;
        }
        // ... restante do SQL continua igual ...


        try {
            $db = Database::getInstance();
            
            $stmt = $db->prepare("
                SELECT * FROM mensagens_chat 
                WHERE (remetente_id = ? AND destinatario_id = ?) 
                   OR (remetente_id = ? AND destinatario_id = ?) 
                ORDER BY criado_em ASC
            ");
            $stmt->execute([$remetente, $destinatario, $destinatario, $remetente]);
            $mensagens = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode($mensagens);
            exit;
        } catch (\PDOException $e) {
            echo json_encode([]);
            exit;
        }
    }

    /**
     * Busca os usuários com quem o logado possui conversas no banco de dados (Via GET)
     */
        public function listarUsuarios() {
        // Altere para aceitar apenas POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            exit;
        }
        // ... restante da busca de contatos continua igual ...


        $usuario_logado_id = $_SESSION['user_id'] ?? null;
        if (!$usuario_logado_id) {
            echo json_encode([]);
            exit;
        }

        try {
            $db = Database::getInstance();
            
            $query = "
                SELECT DISTINCT u.id, u.name 
                FROM users u
                INNER JOIN mensagens_chat m ON (
                    (m.remetente_id = ? AND m.destinatario_id = u.id) OR 
                    (m.destinatario_id = ? AND m.remetente_id = u.id)
                )
                WHERE u.id != ?
                ORDER BY u.name ASC
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([$usuario_logado_id, $usuario_logado_id, $usuario_logado_id]);
            $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            echo json_encode($usuarios);
            exit;
        } catch (\PDOException $e) {
            echo json_encode([]);
            exit;
        }
    }
}
