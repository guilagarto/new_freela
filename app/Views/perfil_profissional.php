<?php
// app/Views/perfil_profissional.php

// Garante que a sessão seja lida corretamente na View para identificar o usuário logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o cabeçalho padrão do seu projeto (TalentoHub)
require_once __DIR__ . '/partials/header.php'; 
?>

<!-- CONTAINER PRINCIPAL DO PERFIL PÚBLICO -->
<div class="container-perfil-publico" style="max-width: 900px; margin: 50px auto; padding: 0 15px; font-family: 'Segoe UI', sans-serif; box-sizing: border-box;">
    
    <div class="card-perfil" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 25px; box-sizing: border-box;">
        
        <!-- BLOCO DE IDENTIFICAÇÃO SUPERIOR -->
        <div style="display: flex; align-items: center; gap: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; box-sizing: border-box;">
            <!-- Foto/Ícone de Perfil Padrão -->
            <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; flex-shrink: 0;">
                👤
            </div>
            <div>
                <!-- Nome do Profissional vindo dinamicamente da query do banco -->
                <h1 style="margin: 0; font-size: 24px; color: #1e293b; font-weight: 700;"><?= htmlspecialchars($profissional['name']); ?></h1>
                <!-- Categoria/Profissão cadastrada -->
                <p style="margin: 5px 0 0 0; font-size: 16px; color: #4f46e5; font-weight: 600;">
                    💼 <?= htmlspecialchars($profissional['profissao'] ?? 'Profissional Autônomo'); ?>
                </p>
            </div>
        </div>

        <!-- BIOGRAFIA / RESUMO PROFISSIONAL -->
        <div style="box-sizing: border-box;">
            <h3 style="margin: 0 0 12px 0; color: #334155; font-size: 18px; font-weight: 600;">Sobre o Profissional</h3>
            <p style="margin: 0; line-height: 1.6; color: #64748b; background: #f8fafc; padding: 18px; border-radius: 8px; border: 1px solid #f1f5f9; font-size: 15px; text-align: justify; word-break: break-word;">
                <?= nl2br(htmlspecialchars($profissional['biografia'] ?? 'Nenhuma descrição biográfica informada ainda pelo profissional.')); ?>
            </p>
        </div>

        <!-- RESTRITO: Regra de negócio para proteger o contato direto e reter o usuário na plataforma -->
        <div style="background: #f8fafc; padding: 15px 20px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px; color: #64748b; display: flex; align-items: center; gap: 10px; box-sizing: border-box;">
            <span>🔒</span> 
            <em style="font-style: normal;">Informações de contato direto (como WhatsApp) serão liberadas após a primeira negociação no chat interno.</em>
        </div>

        <!-- AÇÕES DO PERFIL: BOTÃO DE CHAT UNIFICADO -->
                <!-- AÇÕES DO PERFIL: BOTÃO DE CHAT UNIFICADO CORRIGIDO -->
        <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 15px; align-items: center; box-sizing: border-box;">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- CORREÇÃO: Usando '../chat' para subir um nível e achar a rota correta do painel -->
               <!-- Altere o link antigo do botão azul de Iniciar Chat para este formato de subpasta limpo: -->
<!-- SUBSTITUA O BOTÃO AZUL ANTERIOR POR ESTA VERSÃO COM PARÂMETRO TRADICIONAL -->
<a href="../chat?destinatario_id=<?= (int)$profissional['id']; ?>" 
   style="background: #4f46e5; color: #ffffff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);">
    💬 Iniciar Chat / Conversar
</a>


            <?php else: ?>
                <!-- CORREÇÃO: Ajustado também para o login voltar um nível -->
                <a href="../login" 
                   style="background: #64748b; color: #ffffff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-flex; align-items: center; transition: background 0.2s;">
                    🔒 Faça login para Iniciar Conversa
                </a>
            <?php endif; ?>
            
            <!-- CORREÇÃO: Ajustado para voltar à lista geral de profissionais -->
            <a href="../profissionais" style="padding: 14px 20px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-flex; align-items: center; transition: color 0.2s;">
                ← Voltar para a lista
            </a>
        </div>


    </div>
</div>

<?php 
// Inclui o rodapé padrão do seu projeto (TalentoHub)
require_once __DIR__ . '/partials/footer.php'; 
?>
