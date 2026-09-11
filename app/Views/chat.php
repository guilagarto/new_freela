<?php
// app/Views/chat.php

// Inclui o cabeçalho padrão do seu projeto
require_once __DIR__ . '/partials/header.php'; 

// Garante que o ID do usuário logado está disponível
$usuario_logado_id = $_SESSION['user_id'] ?? 1; // Mantendo o padrão de teste

// Descobre dinamicamente a pasta base (resulta em '/new-freela' localmente e '' em produção)
$base_url = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
$base_url = rtrim($base_url, '/');
?>

<script>
    // Injeta a URL base correta para o arquivo chat.js utilizar nas requisições Fetch
    window.CHAT_BASE_URL = "<?php echo $base_url; ?>";
</script>

<!-- CONTAINER PRINCIPAL COM MARGEM SUPERIOR ALTA PARA NÃO FICAR DEBAIXO DO MENU -->
<div class="container-chat-principal" style="max-width: 1100px; margin: 50px auto; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; box-sizing: border-box;">
    
    <div class="chat-wrapper" style="display: flex; background: #ffffff; border: 1px solid #ddd; border-radius: 12px; overflow: hidden; height: 550px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); box-sizing: border-box;">
        
        <!-- BARRA LATERAL: Lista de Conversas -->
        <div class="chat-sidebar" style="width: 30%; border-right: 1px solid #eee; background: #fcfcfc; display: flex; flex-direction: column; box-sizing: border-box;">
            <div style="padding: 15px; border-bottom: 1px solid #eee; font-weight: bold; color: #333; background: #fff; font-size: 15px;">
                Conversas Recentes
            </div>
            <div class="usuarios-lista" style="flex: 1; overflow-y: auto;">
                <div class="usuarios-lista" style="flex: 1; overflow-y: auto;">
                        <!-- O JavaScript irá injetar os contatos dinamicamente aqui -->
                </div>
            </div>
        </div>

        <!-- ÁREA PRINCIPAL: Janela de Mensagens -->
        <div class="chat-main" style="width: 70%; display: flex; flex-direction: column; background: #f7f9fa; box-sizing: border-box;">
            
            <!-- Cabeçalho do Chat Ativo -->
            <div style="padding: 15px; background: #fff; border-bottom: 1px solid #eee; font-weight: 600; color: #444; font-size: 15px;">
                Conversando com: <span id="nome-chat-ativo" style="color: #007bff;">Usuário de Teste</span>
            </div>

            <!-- Corpo do Chat (Ajustado com display flex column para alinhar os balões corretamente) -->
            <div id="chat-box" style="flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px; background: #f4f6f8; min-height: 350px; box-sizing: border-box;">
                <!-- As mensagens serão injetadas aqui pelo JavaScript -->
            </div>

            <!-- Formulário de Envio -->
            <form id="chat-form" style="padding: 15px; background: #fff; border-top: 1px solid #eee; display: flex; gap: 10px; align-items: center; box-sizing: border-box; margin: 0;">
                <input type="hidden" id="remetente_id" value="<?php echo $usuario_logado_id; ?>">
                <input type="hidden" id="destinatario_id" value="2">
                
                <input type="text" id="mensagem-input" placeholder="Digite sua mensagem aqui..." autocomplete="off" required 
                       style="flex: 1; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; outline: none; font-size: 14px; background: #fff; color: #333;">
                <button type="submit" style="padding: 12px 24px; background: #007bff; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; transition: background 0.2s;">
                    Enviar
                </button>
            </form>
            
        </div>
    </div>
</div>

<!-- Script do Chat usando o caminho relativo correto -->
<script src="js/chat.js"></script>

<?php 
// Inclui o rodapé padrão do seu projeto
require_once __DIR__ . '/partials/footer.php'; 
?>
