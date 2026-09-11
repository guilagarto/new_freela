<?php
// app/Views/chat.php

// Inclui o cabeçalho padrão do seu projeto
require_once __DIR__ . '/partials/header.php'; 

// Garante que o ID do usuário logado está disponível
$usuario_logado_id = $_SESSION['user_id'] ?? 1;

// Descobre dinamicamente a pasta base (resulta em '/new-freela' localmente e '' em produção)
$base_url = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
$base_url = rtrim($base_url, '/');
?>

<!-- ESTILOS CSS ADAPTATIVOS (RESPONSIVO) -->
<style>
    /* Força o botão de menu (barrinhas) a ficar alinhado à direita no seu header */
    header .fa-bars, header svg, header .navbar-toggler, header button {
        float: right !important;
        margin-left: auto !important;
    }

    .container-chat-principal {
        max-width: 1100px; 
        margin: 30px auto; 
        padding: 0 15px; 
        font-family: 'Segoe UI', sans-serif; 
        box-sizing: border-box;
    }

    .chat-wrapper {
        display: flex; 
        background: #ffffff; 
        border: 1px solid #ddd; 
        border-radius: 12px; 
        overflow: hidden; 
        height: 600px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .chat-sidebar {
        width: 30%; 
        border-right: 1px solid #eee; 
        background: #fcfcfc; 
        display: flex; 
        flex-direction: column;
    }

    .chat-main {
        width: 70%; 
        display: flex; 
        flex-direction: column; 
        background: #f7f9fa;
    }

    /* REGRAS ESPECÍFICAS PARA CELULAR (TELAS PEQUENAS) */
    @media (max-width: 768px) {
        .container-chat-principal {
            margin: 10px auto;
            padding: 0 5px;
        }

        .chat-wrapper {
            flex-direction: column; /* Empilha a barra lateral e as mensagens */
            height: calc(100vh - 140px); /* Ajusta a altura de forma dinâmica com base na tela do celular */
        }

        .chat-sidebar {
            width: 100%;
            height: 150px; /* Deixa a lista de usuários menor no topo */
            border-right: none;
            border-bottom: 1px solid #eee;
        }

        .usuarios-lista {
            display: flex; /* Transforma a lista de contatos em uma barra horizontal de rolagem no celular */
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            padding: 5px;
        }

        .usuario-item {
            display: inline-block;
            border-bottom: none !important;
            border-right: 1px solid #eee;
            padding: 10px 15px !important;
            margin: 5px;
            border-radius: 8px;
            background: #f1f1f1;
        }

        .chat-main {
            width: 100%;
            flex: 1; /* Faz o corpo das mensagens ocupar o restante do espaço */
        }
        
        #chat-box {
            min-height: auto !important;
        }
    }
</style>

<script>
    window.CHAT_BASE_URL = "<?php echo $base_url; ?>";
</script>

<div class="container-chat-principal">
    <div class="chat-wrapper">
        
        <!-- BARRA LATERAL: Lista de Conversas -->
        <div class="chat-sidebar">
            <div style="padding: 15px; border-bottom: 1px solid #eee; font-weight: bold; color: #333; background: #fff; font-size: 15px;">
                Conversas
            </div>
            <div class="usuarios-lista">
                <!-- Injeção dinâmica do JavaScript -->
            </div>
        </div>

        <!-- ÁREA PRINCIPAL: Janela de Mensagens -->
        <div class="chat-main">
            
            <div style="padding: 15px; background: #fff; border-bottom: 1px solid #eee; font-weight: 600; color: #444; font-size: 15px;">
                Conversando com: <span id="nome-chat-ativo" style="color: #007bff;">...</span>
            </div>

            <div id="chat-box" style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: #f4f6f8; min-height: 350px; box-sizing: border-box;">
                <!-- Injeção dinâmica do JavaScript -->
            </div>

            <form id="chat-form" style="padding: 15px; background: #fff; border-top: 1px solid #eee; display: flex; gap: 10px; align-items: center; box-sizing: border-box; margin: 0;">
                <input type="hidden" id="remetente_id" value="<?php echo $usuario_logado_id; ?>">
                <input type="hidden" id="destinatario_id" value="">
                
                <input type="text" id="mensagem-input" placeholder="Digite sua mensagem..." autocomplete="off" required 
                       style="flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-size: 14px; background: #fff; color: #333;">
                <button type="submit" style="padding: 12px 20px; background: #007bff; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px;">
                    Enviar
                </button>
            </form>
            
        </div>
    </div>
</div>

<script src="js/chat.js"></script>

<?php 
require_once __DIR__ . '/partials/footer.php'; 
?>
