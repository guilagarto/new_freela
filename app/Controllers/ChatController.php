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
       /**
     * Busca os usuários que possuem propostas enviadas ou contratos ativos com o usuário logado
     */
    public function listarUsuarios() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            exit;
        }

        $usuario_logado_id = $_SESSION['user_id'] ?? 1;

        try {
            $db = Database::getInstance();
            
            /**
             * SQL ADAPTADO PARA A SUA ESTRUTURA:
             * 1. Busca usuários vinculados através da tabela 'proposals' cruzando com 'professional_profiles'
             * 2. Busca usuários vinculados através da tabela 'contracts' (tanto como cliente quanto como profissional)
             * 3. Une os resultados com UNION para listar todos sem duplicações
             */
            $query = "
                SELECT DISTINCT u.id, u.name 
                FROM users u
                INNER JOIN professional_profiles pp ON pp.user_id = u.id
                INNER JOIN proposals p ON p.professional_profile_id = pp.id
                INNER JOIN jobs j ON j.id = p.job_id
                WHERE j.user_id = ? AND u.id != ?

                UNION

                SELECT DISTINCT u.id, u.name 
                FROM users u
                INNER JOIN professional_profiles pp ON pp.user_id = u.id
                INNER JOIN proposals p ON p.professional_profile_id = pp.id
                WHERE pp.user_id = ? AND j.user_id != ?

                UNION

                SELECT DISTINCT u.id, u.name 
                FROM users u
                INNER JOIN contracts c ON (
                    (c.client_id = ? AND c.professional_id = u.id) OR 
                    (c.professional_id = ? AND c.client_id = u.id)
                )
                WHERE u.id != ?
                ORDER BY name ASC
            ";
            
            $stmt = $db->prepare($query);
            
            // Passamos as variáveis correspondentes a cada ponto de interrogação (?) definido no SQL combinado
            $stmt->execute([
                $usuario_logado_id, $usuario_logado_id, // Primeiro bloco (Dono da vaga vendo propostas)
                $usuario_logado_id, $usuario_logado_id, // Segundo bloco (Freelancer vendo dono da vaga)
                $usuario_logado_id, $usuario_logado_id, $usuario_logado_id // Terceiro bloco (Contratos mútuos)
            ]);
            
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($usuarios);
            exit;
        } catch (PDOException $e) {
            // Em caso de erro, você pode descomentar a linha abaixo para debug se a lista sumir:
            // echo json_encode(['error' => $e->getMessage()]); exit;
            echo json_encode([]);
            exit;
        }
    }


}
