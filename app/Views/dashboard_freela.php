<?php
// app/Views/dashboard_freela.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteção visual: garante que o cabeçalho padrão seja carregado
require_once __DIR__ . '/partials/header.php'; 

// Recupera os dados vindos dinamicamente através do DashboardController
$nome_atual = $usuario['name'] ?? '';
$profissao_atual = $perfil['category'] ?? '';
$bio_atual = $perfil['bio'] ?? '';
$preco_atual = $perfil['price_per_hour'] ?? '';
$telefone_atual = $perfil['phone'] ?? '';
$portfolio_atual = $perfil['portfolio_text'] ?? '';
?>

<div class="container-painel-freela" style="max-width: 1000px; margin: 40px auto; padding: 0 15px; font-family: 'Segoe UI', sans-serif; box-sizing: border-box;">
    
    <!-- MENSAGENS DE SUCESSO VISUAIS -->
    <?php if (isset($_SESSION['sucesso_painel'])): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; border: 1px solid #a7f3d0;">
            ✅ <?php echo $_SESSION['sucesso_painel']; unset($_SESSION['sucesso_painel']); ?>
        </div>
    <?php endif; ?>

    <!-- BLOCO SUPERIOR DE BOAS-VINDAS E MENSAGENS -->
    <div class="welcome-banner" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 30px; box-sizing: border-box;">
        <span style="background: #fef3c7; color: #d97706; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">Painel do Freelancer 🛠️</span>
        <h1 style="margin: 15px 0 5px 0; font-size: 28px; color: #1e293b;">Olá, <?= htmlspecialchars(explode(' ', $nome_atual)[0]); ?>!</h1>
        <p style="margin: 0 0 20px 0; color: #64748b; font-size: 15px;">Monitore suas conversas, gerencie seu portfólio profissional e edite suas informações de vitrine.</p>
        
        <!-- BOTÃO DE ATALHO DIRETO PARA O CHAT EM TEMPO REAL -->
        <a href="./chat" style="background: #4f46e5; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 15px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); transition: background 0.2s; border: none; cursor: pointer;">
            💬 Abrir Histórico de Mensagens / Chat
        </a>
    </div>

    <!-- FORMULÁRIO COMPLETO DE GERENCIAMENTO DE PERFIL -->
    <div class="card-formulario-perfil" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); box-sizing: border-box;">
        <h2 style="margin: 0 0 20px 0; font-size: 20px; color: #334155; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; font-weight: 600;">Editar Informações Próprias do Perfil</h2>
        
        <form action="./dashboard-freela/salvar" method="POST" style="display: flex; flex-direction: column; gap: 20px; margin: 0; box-sizing: border-box;">
            
            <!-- LINHA 1: NOME COMPLETO E CATEGORIA -->
            <div class="form-row-dupla" style="display: flex; gap: 20px; flex-wrap: wrap; box-sizing: border-box;">
                <div style="flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Seu Nome Completo:</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($nome_atual); ?>" required style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
                </div>
                <div style="flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Sua Profissão / Especialidade:</label>
                    <input type="text" name="category" value="<?= htmlspecialchars($profissao_atual); ?>" placeholder="Ex: Desenvolvedor Web, Eletricista, Designer..." required style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
                </div>
            </div>

            <!-- LINHA 2: PREÇO POR HORA E TELEFONE/WHATSAPP -->
            <div class="form-row-dupla" style="display: flex; gap: 20px; flex-wrap: wrap; box-sizing: border-box;">
                <div style="flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Preço por Hora Cobrado (R$):</label>
                    <input type="number" step="0.01" name="price_per_hour" value="<?= htmlspecialchars($preco_atual); ?>" placeholder="Ex: 50.00" required style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
                </div>
                <div style="flex: 1; min-width: 280px; display: flex; flex-direction: column; gap: 8px;">
                    <label style="font-weight: 600; font-size: 14px; color: #475569;">Telefone / WhatsApp de Contato:</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($telefone_atual); ?>" placeholder="Ex: 5511999999999" required style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
                </div>
            </div>

            <!-- LINHA 3: BIOGRAFIA -->
            <div style="display: flex; flex-direction: column; gap: 8px; box-sizing: border-box;">
                <label style="font-weight: 600; font-size: 14px; color: #475569;">Biografia / Resumo Profissional (O que aparecerá no seu Perfil Público):</label>
                <textarea name="bio" rows="4" placeholder="Fale um pouco sobre a sua experiência técnica, habilidades e ferramentas que domina..." required style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; resize: vertical; font-family: inherit; background: #fff; color: #333;"><?= htmlspecialchars($bio_atual); ?></textarea>
            </div>

            <!-- LINHA 4: GERENCIAMENTO DE TRABALHOS ANTERIORES / PORTFÓLIO -->
            <div style="display: flex; flex-direction: column; gap: 8px; box-sizing: border-box;">
                <label style="font-weight: 600; font-size: 14px; color: #475569;">📋 Meus Trabalhos Postados / Portfólio (Adicione links ou resumos):</label>
                <textarea name="portfolio_text" rows="4" placeholder="Adicione links de projetos anteriores realizados (GitHub, Behance, Portfólio próprio) ou liste os principais serviços que você já executou com sucesso..." style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; resize: vertical; font-family: inherit; background: #fff; color: #333;"><?= htmlspecialchars($portfolio_atual); ?></textarea>
            </div>

            <!-- BOTÃO DE SUBMIT DA ATUALIZAÇÃO -->
            <div style="display: flex; justify-content: flex-end; margin-top: 10px; box-sizing: border-box;">
                <button type="submit" style="background: #4f46e5; color: #ffffff; padding: 14px 28px; border: none; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15); transition: background 0.2s;">
                    💾 Salvar Alterações do Perfil Professional
                </button>
            </div>

        </form>
    </div>
</div>

<?php 
// Garante o fechamento correto do rodapé padrão do projeto
require_once __DIR__ . '/partials/footer.php'; 
?>
