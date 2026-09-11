<?php
// app/Controllers/ChatController.php

// Usando o Namespace correto do seu projeto
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
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        require_once __DIR__ . '/../Views/chat.php';
    }

    /**
     * Recebe os dados via POST assíncrono (JavaScript) e grava a mensagem no Banco de Dados
     */
    public function enviar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Forçando ID de teste caso você não esteja logado no sistema localmente
            //if (!isset($_SESSION['user_id'])) {
              //  $_SESSION['user_id'] = 1; 
            //}

            $remetente = $_SESSION['user_id'] ?? null;
            $destinatario = isset($_POST['destinatario_id']) ? (int)$_POST['destinatario_id'] : null;
            $mensagem = isset($_POST['mensagem']) ? htmlspecialchars(trim($_POST['mensagem'])) : null;

            if ($remetente && $destinatario && $mensagem !== '') {
                try {
                    // CORREÇÃO: Chamando o método estático correto da sua classe Database
                    $db = Database::getInstance(); 
                    
                    $stmt = $db->prepare("INSERT INTO mensagens_chat (remetente_id, destinatario_id, mensagem) VALUES (?, ?, ?)");
                    $stmt->execute([$remetente, $destinatario, $mensagem]);

                    echo json_encode(['status' => 'success']);
                    exit;
                } catch (PDOException $e) {
                    echo json_encode(['status' => 'error', 'reason' => 'Erro no banco: ' . $e->getMessage()]);
                    exit;
                }
            }
        }
        
        echo json_encode(['status' => 'error', 'reason' => 'Dados inválidos ou sessão expirada']);
    }

    /**
     * Retorna o histórico de mensagens trocadas entre os dois usuários em formato JSON
     */
    public function buscarMensagens() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            exit;
        }

        // Forçando ID de teste caso você não esteja logado no sistema localmente
       // if (!isset($_SESSION['user_id'])) {
         //   $_SESSION['user_id'] = 1; 
       // }

        $remetente = $_SESSION['user_id'] ?? null;
        $destinatario = isset($_POST['destinatario_id']) ? (int)$_POST['destinatario_id'] : null;

        if (!$remetente || !$destinatario) {
            echo json_encode([]);
            exit;
        }

        try {
            // CORREÇÃO: Chamando o método estático correto da sua classe Database
            $db = Database::getInstance();
            
            $stmt = $db->prepare("
                SELECT * FROM mensagens_chat 
                WHERE (remetente_id = ? AND destinatario_id = ?) 
                   OR (remetente_id = ? AND destinatario_id = ?) 
                ORDER BY criado_em ASC
            ");
            $stmt->execute([$remetente, $destinatario, $destinatario, $remetente]);
            $mensagens = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($mensagens);
            exit;
        } catch (PDOException $e) {
            echo json_encode([]);
            exit;
        }
        
    }
        /**
     * Busca todos os usuários do sistema para listar na barra lateral do chat
     */
    public function listarUsuarios() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            exit;
        }

        $usuario_logado_id = $_SESSION['user_id'] ?? 1;

        try {
            $db = Database::getInstance();
            
            // Busca os usuários cadastrados, ignorando o próprio usuário logado.
            // NOTA: Se na tabela 'users' a coluna de nome se chamar 'nome' (em português), mude 'name' para 'nome' abaixo.
            $stmt = $db->prepare("SELECT id, name FROM users WHERE id != ? ORDER BY name ASC");
            $stmt->execute([$usuario_logado_id]);
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($usuarios);
            exit;
        } catch (PDOException $e) {
            echo json_encode([]);
            exit;
        }
    }

}
