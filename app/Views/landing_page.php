<?php
// app/Views/landing_page.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega o cabeçalho padrão do seu ecossistema (TalentoHub)
require_once __DIR__ . '/partials/header.php'; 
?>

<!-- ESTILIZAÇÃO EXCLUSIVA DA NOVA PORTA DE ENTRADA -->
<!-- Substitua o bloco <style> atual do seu app/Views/landing_page.php por este: -->
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        --text-main: #0f172a;
        --text-muted: #475569;
        --bg-light: #f8fafc;
    }

    .landing-wrapper {
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
        background-color: #ffffff;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        overflow-x: hidden; /* CORREÇÃO CRUCIAL: Impede qualquer rolagem lateral no celular */
        width: 100%;
    }

    /* HERO SECTION */
    .hero-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 80px 20px;
        display: flex;
        align-items: center;
        gap: 50px;
        box-sizing: border-box;
    }

    .hero-content {
        flex: 1;
        min-width: 0; /* Impede que o conteúdo flex force larguras estáticas */
    }

    .hero-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 6px 16px;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
    }

    .hero-title {
        font-size: 48px;
        font-weight: 800;
        line-height: 1.2;
        margin: 0 0 20px 0;
        color: var(--text-main);
        word-break: break-word;
    }

    .hero-title span {
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: 18px;
        line-height: 1.6;
        color: var(--text-muted);
        margin: 0 0 35px 0;
        max-width: 540px;
    }

    .hero-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-landing {
        padding: 16px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        box-sizing: border-box;
    }

    .btn-landing:hover {
        transform: translateY(-2px);
    }

    .btn-fill {
        background: var(--primary-gradient);
        color: #ffffff;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }

    .btn-outline {
        background: #ffffff;
        color: #4f46e5;
        border: 2px solid #e2e8f0;
    }

    .hero-graphic {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .platform-mockup {
        background: var(--primary-gradient);
        width: 100%;
        max-width: 500px;
        height: 350px;
        border-radius: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ffffff;
        font-size: 72px;
    }

    /* SEÇÃO DE BENEFÍCIOS / DIFERENCIAIS */
    .features-section {
        background-color: var(--bg-light);
        padding: 80px 20px;
        box-sizing: border-box;
        width: 100%;
    }

    .section-header {
        text-align: center;
        max-width: 600px;
        margin: 0 auto 50px auto;
        padding: 0 10px;
        box-sizing: border-box;
    }

    .section-title {
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 15px 0;
        line-height: 1.3;
        word-break: break-word; /* Força o título longo a quebrar linha no celular */
    }

    .section-desc {
        color: var(--text-muted);
        font-size: 16px;
        line-height: 1.6;
        margin: 0;
    }

    .grid-features {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        box-sizing: border-box;
    }

    .feature-card {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        background: #e0e7ff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .feature-card h3 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 12px 0;
    }

    .feature-card p {
        color: var(--text-muted);
        font-size: 15px;
        line-height: 1.6;
        margin: 0;
    }

    /* SEÇÃO DE MÉTRICAS */
    .stats-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 80px 20px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 40px;
        text-align: center;
        box-sizing: border-box;
    }

    .stat-box h2 {
        font-size: 42px;
        font-weight: 800;
        margin: 0 0 5px 0;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-box p {
        color: var(--text-muted);
        font-size: 13px;
        font-weight: 600;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ========================================================================= */
    /* MELHORIAS COMPLETAS DE RESPONSIVIDADE (MOBILE / CELULAR)                  */
    /* ========================================================================= */
    @media (max-width: 992px) {
        .hero-container {
            flex-direction: column;
            text-align: center;
            padding: 50px 20px;
        }
        .hero-subtitle {
            margin-left: auto;
            margin-right: auto;
        }
        .hero-buttons {
            justify-content: center;
        }
        .hero-graphic {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 26px; /* Encolhe o tamanho do título para caber perfeitamente */
        }
        .hero-title {
            font-size: 34px;
        }
        .features-section {
            padding: 50px 15px;
        }
        .grid-features {
            grid-template-columns: 1fr; /* Empilha os cards em uma coluna perfeita no mobile */
            gap: 20px;
        }
        .feature-card {
            padding: 24px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 30px;
        }
        .btn-landing {
            width: 100%; /* Botões ocupam 100% da largura em celulares muito pequenos */
            justify-content: center;
        }
        .platform-mockup {
            height: 220px;
            font-size: 48px;
        }
        .stat-box {
            width: 100%; /* Empilha as estatísticas no mobile */
        }
    }
</style>


<div class="landing-wrapper">

    <!-- SEÇÃO HERO DE CONVERSÃO -->
    <section class="hero-container">
        <div class="hero-content">
            <span class="hero-badge">Conexão Direta e Inteligente 🚀</span>
            <h1 class="hero-title">A maneira inteligente de contratar <span>Talentos</span></h1>
            <p class="hero-subtitle">Seja para contratar serviços locais de confiança ou para transformar suas habilidades em um negócio lucrativo, o seu lugar é aqui no TalentoHub.</p>
            
            <div class="hero-buttons">
                <!-- Links amarrados ao helper AppConfig para funcionar local e na Hostinger -->
                <a href="<?php echo \App\Config\AppConfig::url('/profissionais'); ?>" class="btn-landing btn-fill">
                    🔍 Encontrar Profissional
                </a>
                <a href="<?php echo \App\Config\AppConfig::url('/dashboard-freela'); ?>" class="btn-landing btn-outline">
                    💼 Oferecer Serviços
                </a>
            </div>
        </div>
        
        <div class="hero-graphic">
            <div class="platform-mockup">
                💼
            </div>
        </div>
    </section>

    <!-- SEÇÃO DE DIFERENCIAIS DA PORTA DE ENTRADA -->
    <section class="features-section">
        <div class="section-header">
            <h2 class="section-title">Por que escolher o TalentoHub?</h2>
            <p class="section-desc">Criamos uma infraestrutura blindada focada no contato ágil, transparente e seguro entre contratantes e prestadores autônomos.</p>
        </div>

        <div class="grid-features">
            <!-- Card 1 -->
            <div class="feature-card">
                <div class="feature-icon" style="background: #ecfdf5; color: #10b981;">🔍</div>
                <h3>Busca Direta</h3>
                <p>Filtre prestadores qualificados instantaneamente por nome ou categoria profissional em sua região.</p>
            </div>
            <!-- Card 2 -->
            <div class="feature-card">
                <div class="feature-icon" style="background: #eff6ff; color: #3b82f6;">💬</div>
                <h3>Chat em Tempo Real</h3>
                <p>Converse, negocie escopos e combine orçamentos diretamente pela plataforma sem intermediários burocráticos.</p>
            </div>
            <!-- Card 3 -->
            <div class="feature-card">
                <div class="feature-icon" style="background: #fffbec; color: #f59e0b;">⭐</div>
                <h3>Avaliações Reais</h3>
                <p>Consulte depoimentos e notas de clientes reais antes de fechar qualquer contratação profissional.</p>
            </div>
        </div>
    </section>

    <!-- SEÇÃO DE ESTATÍSTICAS / GERAÇÃO DE AUTORIDADE -->
    <section class="stats-section">
        <div class="stat-box">
            <h2>+1.200</h2>
            <p>Profissionais</p>
        </div>
        <div class="stat-box">
            <h2>99.4%</h2>
            <p>Satisfação</p>
        </div>
        <div class="stat-box">
            <h2>+5.000</h2>
            <p>Conexões Reais</p>
        </div>
    </section>

</div>

<?php 
// Fecha o ecossistema com o rodapé padrão do projeto
require_once __DIR__ . '/partials/footer.php'; 
?>
