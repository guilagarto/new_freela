<?php
// app/Views/chat.php

// Garante que a sessão esteja ativa para ler as credenciais do usuário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se tentar acessar o chat diretamente sem login, joga para a tela de autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login');
    exit;
}

// Inclui o cabeçalho padrão do seu projeto (TalentoHub)
require_once __DIR__ . '/partials/header.php'; 

$usuario_logado_id = $_SESSION['user_id'];

// Captura a URL base correta do seu projeto de forma dinâmica (Ex: /new-freela)
$base_url = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
$base_url = rtrim($base_url, '/');
?>

<script>
    // Injeta a rota absoluta global para o arquivo chat.js usar nas requisições assíncronas
    window.CHAT_API_URL = "<?php echo $base_url; ?>";
</script>

<style>
    /* ESTILIZAÇÃO PADRÃO DO CHAT (WEB / COMPUTADOR) */
    .chat-wrapper {
        display: flex; 
        background: #ffffff; 
        border: 1px solid #ddd; 
        border-radius: 12px; 
        overflow: hidden; 
        height: 550px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.08); 
        box-sizing: border-box;
    }
    .chat-sidebar {
        width: 30%; 
        border-right: 1px solid #eee; 
        background: #fcfcfc; 
        display: flex; 
        flex-direction: column; 
        box-sizing: border-box;
    }
    .chat-main {
        width: 70%; 
        display: flex; 
        flex-direction: column; 
        background: #f7f9fa; 
        box-sizing: border-box;
    }

    /* ========================================================================= */
    /* CORREÇÃO DE RESPONSIVIDADE EXCLUSIVA PARA CELULARES (MOBILE)              */
    /* ========================================================================= */
    @media (max-width: 768px) {
        .chat-wrapper {
            flex-direction: column !important;
            height: calc(100vh - 140px) !important; /* Estica o chat para caber na tela do celular */
        }
        .chat-sidebar {
            width: 100% !important;
            height: 160px !important; /* Lista de contatos compacta rolável no topo */
            border-right: none !important;
            border-bottom: 1px solid #eee !important;
        }
        .chat-main {
            width: 100% !important;
            flex: 1 !important;
        }
        #chat-box {
            padding: 12px !important;
        }
    }
</style>

<div class="container-chat-principal" style="max-width: 1100px; margin: 50px auto; padding: 0 15px; font-family: 'Segoe UI', sans-serif; box-sizing: border-box;">
    
    <div class="chat-wrapper">
        
        <!-- BARRA LATERAL: Lista de Conversas Dinâmicas -->
        <div class="chat-sidebar">
            <div style="padding: 15px; border-bottom: 1px solid #eee; font-weight: bold; color: #333; background: #fff; font-size: 15px;">
                Conversas
            </div>
            <div class="usuarios-lista" style="flex: 1; overflow-y: auto;">
                <!-- O JavaScript injetará a lista de contatos reais aqui -->
            </div>
        </div>

        <!-- ÁREA PRINCIPAL: Janela de Mensagens -->
        <div class="chat-main">
            
            <!-- Cabeçalho do Chat Ativo -->
            <div style="padding: 15px; background: #fff; border-bottom: 1px solid #eee; font-weight: 600; color: #444; font-size: 15px;">
                Conversando com: <span id="nome-chat-ativo" style="color: #4f46e5;">Selecionando contato...</span>
            </div>

            <!-- Corpo do Chat (Ajustado com flexbox nativo para organizar os balões) -->
            <div id="chat-box" style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: #f4f6f8; min-height: 300px; box-sizing: border-box;">
                <!-- As mensagens trocadas aparecerão aqui -->
            </div>

            <!-- Formulário de Envio Ajustado com os identificadores exatos do chat.js -->
            <form id="chat-form" style="padding: 15px; background: #fff; border-top: 1px solid #eee; display: flex; gap: 10px; align-items: center; box-sizing: border-box; margin: 0;">
                <!-- ID do Remetente ativo na sessão -->
                <input type="hidden" id="remetente_id" value="<?php echo (int)$usuario_logado_id; ?>">
                
                <!-- ID do Destinatário atual (Lê dinamicamente o parâmetro ?destinatario_id vindo do botão do perfil) -->
                <input type="hidden" id="destinatario_id" value="<?php echo isset($_GET['destinatario_id']) ? (int)$_GET['destinatario_id'] : ''; ?>">
                
                <input type="text" id="mensagem-input" placeholder="Digite sua mensagem aqui..." autocomplete="off" required 
                       style="flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-size: 14px; background: #fff; color: #333;">
                
                <button type="submit" style="padding: 12px 24px; background: #4f46e5; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; transition: background 0.2s;">
                    Enviar
                </button>
            </form>
            
        </div>
    </div>
</div>

<!-- Carrega a inteligência do chat com caminho estável -->
<script src="js/chat.js"></script>

<?php 
// Inclui o rodapé padrão do seu projeto (TalentoHub)
require_once __DIR__ . '/partials/footer.php'; 
?>
