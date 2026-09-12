<?php
// app/Views/perfil_profissional.php

// Garante que a sessão seja lida corretamente na View para identificar o usuário logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o cabeçalho padrão do seu projeto (TalentoHub)
require_once __DIR__ . '/partials/header.php'; 

// Formata o preço por hora de maneira amigável em Real
$preco_hora = isset($profissional['price_per_hour']) ? number_format($profissional['price_per_hour'], 2, ',', '.') : 'A combinar';
?>

<!-- CONTAINER PRINCIPAL DO PERFIL PÚBLICO -->
<div class="container-perfil-publico" style="max-width: 900px; margin: 50px auto; padding: 0 15px; font-family: 'Segoe UI', sans-serif; box-sizing: border-box;">
    
    <div class="card-perfil" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 25px; box-sizing: border-box;">
        
        <!-- BLOCO DE IDENTIFICAÇÃO SUPERIOR -->
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; box-sizing: border-box;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <!-- Foto/Ícone de Perfil Padrão -->
                <div style="width: 80px; height: 80px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; flex-shrink: 0;">
                    👤
                </div>
                <div>
                    <!-- Nome do Profissional -->
                    <h1 style="margin: 0; font-size: 24px; color: #1e293b; font-weight: 700;"><?= htmlspecialchars($profissional['name']); ?></h1>
                    <!-- Categoria/Profissão cadastrada -->
                    <p style="margin: 5px 0 0 0; font-size: 16px; color: #4f46e5; font-weight: 600;">
                        💼 <?= htmlspecialchars($profissional['profissao'] ?? 'Profissional Autônomo'); ?>
                    </p>
                </div>
            </div>

            <!-- VALOR / PREÇO POR HORA -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 8px; text-align: right; min-width: 150px;">
                <span style="font-size: 12px; color: #64748b; font-weight: 600; text-transform: uppercase;">Preço por Hora</span>
                <h2 style="margin: 2px 0 0 0; font-size: 22px; color: #10b981; font-weight: 700;">R$ <?= $preco_hora; ?></h2>
            </div>
        </div>

        <!-- BIOGRAFIA / RESUMO PROFISSIONAL -->
        <div style="box-sizing: border-box;">
            <h3 style="margin: 0 0 12px 0; color: #334155; font-size: 18px; font-weight: 600;">Sobre o Profissional</h3>
            <p style="margin: 0; line-height: 1.6; color: #64748b; background: #f8fafc; padding: 18px; border-radius: 8px; border: 1px solid #f1f5f9; font-size: 15px; text-align: justify; word-break: break-word;">
                <?= nl2br(htmlspecialchars($profissional['biografia'] ?? 'Nenhuma descrição biográfica informada ainda pelo profissional.')); ?>
            </p>
        </div>

        <!-- PORTFÓLIO / TRABALHOS POSTADOS -->
        <div style="box-sizing: border-box;">
            <h3 style="margin: 0 0 12px 0; color: #334155; font-size: 18px; font-weight: 600;">📋 Portfólio & Trabalhos Realizados</h3>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; padding: 18px; border-radius: 8px; font-size: 15px; color: #64748b; line-height: 1.6;">
                <?php if (!empty($profissional['portfolio_text'])): ?>
                    <?= nl2br(htmlspecialchars($profissional['portfolio_text'])); ?>
                <?php else: ?>
                    <p style="margin: 0; color: #94a3b8; font-style: italic;">Nenhum link ou histórico de projeto anexado ao portfólio deste profissional ainda.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- SESSÃO DE AVALIAÇÕES (MUDANÇA SOLICITADA) -->
        <div style="box-sizing: border-box;">
            <h3 style="margin: 0 0 12px 0; color: #334155; font-size: 18px; font-weight: 600;">⭐ Avaliações dos Clientes</h3>
            
            <?php 
            // Mock de segurança: caso você ainda não tenha registros na tabela de reviews do MySQL
            $avaliacoes = $profissional['avaliacoes'] ?? []; 
            
            if (!empty($avaliacoes)): 
                // Loop futuro quando você tiver a tabela vinculada no SQL
                foreach ($avaliacoes as $review): 
            ?>
                <!-- Modelo de Card de Review Ativo -->
                <div style="border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; color: #f59e0b; font-weight: bold; margin-bottom: 5px;">
                        <span>⭐⭐⭐⭐⭐</span>
                        <span style="color: #64748b; font-size: 12px;"><?= $review['data']; ?></span>
                    </div>
                    <p style="margin: 0; color: #475569; font-size: 14px;"><?= htmlspecialchars($review['comentario']); ?></p>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <!-- ALERTA DE PROFISSIONAL SEM AVALIAÇÕES -->
                <div style="background: #fffdf5; border: 1px solid #fef08a; padding: 18px; border-radius: 8px; color: #854d0e; font-size: 14px; display: flex; align-items: center; gap: 10px;">
                    <span>ℹ️</span>
                    <em style="font-style: normal; font-weight: 500;">Este profissional ainda não recebeu avaliações na plataforma do TalentoHub.</em>
                </div>
            <?php endif; ?>
        </div>

        <!-- RESTRITO: Regra de negócio para proteger o contato direto -->
        <div style="background: #f8fafc; padding: 15px 20px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 14px; color: #64748b; display: flex; align-items: center; gap: 10px; box-sizing: border-box;">
            <span>🔒</span> 
            <em style="font-style: normal;">Informações de contato direto (como WhatsApp) serão liberadas após a primeira negociação no chat interno.</em>
        </div>

        <!-- AÇÕES DO PERFIL: BOTÃO DE CHAT UNIFICADO -->
        <div style="margin-top: 5px; display: flex; flex-wrap: wrap; gap: 15px; align-items: center; box-sizing: border-box;">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Caso esteja logado: Caminho relativo voltando um nível para evitar 404 -->
                <a href="../chat?destinatario_id=<?= (int)$profissional['id']; ?>" 
                   style="background: #4f46e5; color: #ffffff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); transition: background 0.2s; border: none; cursor: pointer;">
                    💬 Iniciar Chat / Conversar
                </a>
            <?php else: ?>
                <!-- Caso esteja deslogado -->
                <a href="../login" 
                   style="background: #64748b; color: #ffffff; padding: 14px 28px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-flex; align-items: center; transition: background 0.2s;">
                    🔒 Faça login para Iniciar Conversa
                </a>
            <?php endif; ?>
            
            <!-- Link simples de retorno para a listagem -->
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
