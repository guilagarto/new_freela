<?php
// app/Views/dashboard_freela.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui o cabeçalho padrão do seu projeto (TalentoHub)
require_once __DIR__ . '/partials/header.php'; 

// Recupera os dados vindos dinamicamente através do DashboardController
$nome_atual = $usuario['name'] ?? '';
$profissao_atual = $perfil['category'] ?? '';
$bio_atual = $perfil['bio'] ?? '';
$preco_atual = $perfil['price_per_hour'] ?? '';
$telefone_atual = $perfil['phone'] ?? '';
$portfolio_atual = $perfil['portfolio_text'] ?? '';
?>

<style>
    /* CSS Responsivo integrado para o Painel do Freelancer */
    .container-painel-freela {
        max-width: 1000px; 
        margin: 40px auto; 
        padding: 0 15px; 
        font-family: 'Segoe UI', sans-serif; 
        box-sizing: border-box;
    }
    .form-row-dupla {
        display: flex; 
        gap: 20px; 
        flex-wrap: wrap; 
        box-sizing: border-box;
    }
    .form-col {
        flex: 1; 
        min-width: 280px; 
        display: flex; 
        flex-direction: column; 
        gap: 8px;
    }
    .input-freela {
        padding: 12px; 
        border: 1px solid #cbd5e1; 
        border-radius: 8px; 
        font-size: 14px; 
        outline: none; 
        background: #fff; 
        color: #333;
        transition: border-color 0.2s;
    }
    .input-freela:focus {
        border-color: #4f46e5;
    }
    .btn-salvar-perfil {
        background: #4f46e5; 
        color: #ffffff; 
        padding: 14px 28px; 
        border: none; 
        border-radius: 8px; 
        font-size: 15px; 
        font-weight: bold; 
        cursor: pointer; 
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15); 
        transition: background 0.2s, transform 0.1s;
        width: auto;
    }
    .btn-salvar-perfil:active {
        transform: scale(0.98);
    }

    /* Adaptações para telas pequenas (Celular) */
    @media (max-width: 600px) {
        .container-painel-freela {
            margin: 20px auto;
        }
        .welcome-banner {
            padding: 20px !important;
        }
        .card-formulario-perfil {
            padding: 20px !important;
        }
        .btn-salvar-perfil {
            width: 100% !important; /* Botão ocupa toda a largura no mobile */
            text-align: center;
        }
        .btn-chat-atalho {
            width: 100% !important;
            justify-content: center;
        }
    }
</style>

<div class="container-painel-freela">
    
    <!-- NOTIFICAÇÃO INTERNA DE RESSALVA DE SUCESSO -->
    <?php if (isset($_SESSION['sucesso_painel'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; border: 1px solid #a7f3d0;">
            ✅ <?php echo $_SESSION['sucesso_painel']; unset($_SESSION['sucesso_painel']); ?>
        </div>
    <?php endif; ?>

    <!-- BLOCO SUPERIOR DE BOAS-VINDAS E REDIRECIONAMENTOS -->
    <div class="welcome-banner" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 30px; box-sizing: border-box;">
        <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Meu Perfil Profissional 🛠️</span>
        <h1 style="margin: 15px 0 5px 0; font-size: 26px; color: #1e293b;">Olá, <?= htmlspecialchars(explode(' ', $nome_atual)[0]); ?>!</h1>
        <p style="margin: 0 0 20px 0; color: #64748b; font-size: 15px;">Mantenha seus dados atualizados para atrair mais clientes na listagem do TalentoHub.</p>
        
        <!-- LINK DIRETO PARA O CHAT RESPONSIVO -->
        <a href="./chat" class="btn-chat-atalho" style="background: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 15px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);">
            💬 Abrir Meu Chat / Mensagens
        </a>
    </div>

    <!-- FORMULÁRIO COMPLETO DE GERENCIAMENTO DE PERFIL -->
    <div class="card-formulario-perfil" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); box-sizing: border-box;">
        <h2 style="margin: 0 0 20px 0; font-size: 19px; color: #334155; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; font-weight: 600;">Configurações da Vitrine</h2>
        
        <form action="./dashboard-freela/salvar" method="POST" style="display: flex; flex-direction: column; gap: 20px; margin: 0;">
            
            <!-- CAMPOS: NOME E ESPECIALIDADE -->
            <div class="form-row-dupla">
                <div class="form-col">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Seu Nome de Exibição:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($nome_atual); ?>" required class="input-freela">
                </div>
                <div class="form-col">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Sua Profissão / Especialidade principal:</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($profissao_atual); ?>" placeholder="Ex: Desenvolvedor Web, Eletricista, Designer..." required class="input-freela">
                </div>
            </div>

            <!-- CAMPOS: VALOR DA HORA E WHATSAPP -->
            <div class="form-row-dupla">
                <div class="form-col">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Valor cobrado por Hora (Deixe em branco ou 0 para "A combinar"):</label>
                    <input type="number" step="0.01" name="price_per_hour" value="<?= htmlspecialchars($preco_atual); ?>" placeholder="Ex: 50.00 ou 0" class="input-freela">
                </div>
                <div class="form-col">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Telefone / WhatsApp de Contato:</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($telefone_atual); ?>" placeholder="Ex: 5511999999999" required class="input-freela">
                </div>
            </div>

            <!-- CAMPO: BIOGRAFIA -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 14px; color: #475569;">Resumo Profissional / Biografia:</label>
                <textarea name="bio" rows="4" placeholder="Descreva sua experiência técnica e principais qualificações..." required class="input-freela" style="resize: vertical; font-family: inherit; line-height: 1.5;"><?= htmlspecialchars($bio_atual); ?></textarea>
            </div>

            <!-- CAMPO: PORTFÓLIO / SERVIÇOS -->
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: 600; font-size: 14px; color: #475569;">📋 Detalhes do Meu Portfólio (Trabalhos Postados):</label>
                <textarea name="portfolio_text" rows="4" placeholder="Insira links de projetos anteriores realizados (GitHub, Behance) ou liste os serviços executados..." class="input-freela" style="resize: vertical; font-family: inherit; line-height: 1.5;"><?= htmlspecialchars($portfolio_atual); ?></textarea>
            </div>

            <!-- BOTÃO DE SUBMIT ENQUADRADO -->
            <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                <button type="submit" class="btn-salvar-perfil">
                    💾 Salvar Alterações
                </button>
            </div>

        </form>
    </div>
</div>

<?php 
require_once __DIR__ . '/partials/footer.php'; 
?>
