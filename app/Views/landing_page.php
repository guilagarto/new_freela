<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentoHub | Conectando Profissionais e Contratantes na Sua Região</title>
    
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --success-hover: #059669;
            --text-dark: #0f172a;
            --text-muted: #475569;
            --bg-light: #f8fafc;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: var(--text-dark);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* 1. Header */
        .lp-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .lp-nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .lp-logo {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
        }

        .lp-btn-plataforma {
            background: #f1f5f9;
            color: var(--text-dark);
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }

        .lp-btn-plataforma:hover { background: #e2e8f0; }

        /* 2. Hero Section */
        .hero-container {
            background: linear-gradient(135deg, #f5f3ff 0%, #e0e7ff 100%);
            padding: 100px 20px 140px 20px;
            text-align: center;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0 0 20px 0;
            line-height: 1.2;
            letter-spacing: -1px;
        }

        .hero-title span { color: var(--primary); }

        .hero-subtitle {
            font-size: 20px;
            color: var(--text-muted);
            max-width: 750px;
            margin: 0 auto;
        }

        /* 3. Split Cards Section */
        .split-section {
            max-width: 1200px;
            margin: -60px auto 80px auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .card-parallel {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 45px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-parallel:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        }

        .card-tag {
            align-self: flex-start;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 20px;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .tag-contratante { background: #e0e7ff; color: var(--primary); }
        .tag-profissional { background: #d1fae5; color: #065f46; }

        .card-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 15px 0;
            color: var(--text-dark);
        }

        .card-description {
            font-size: 16px;
            color: var(--text-muted);
            margin: 0 0 25px 0;
        }

        .benefit-list {
            list-style: none;
            padding: 0;
            margin: 0 0 35px 0;
        }

        .benefit-list li {
            margin-bottom: 12px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-dark);
        }

        .btn-cta {
            display: block;
            text-align: center;
            padding: 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: background 0.2s, box-shadow 0.2s;
        }

        .btn-contratar {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }
        .btn-contratar:hover { background: var(--primary-hover); }

        .btn-anunciar {
            background: var(--success);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }
        .btn-anunciar:hover { background: var(--success-hover); }

        /* 4. Como Funciona Section */
        .steps-section {
            background-color: var(--bg-light);
            padding: 80px 20px;
            text-align: center;
        }

        .section-title {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 50px;
        }

        .steps-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .step-item {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: #ffffff;
            border-radius: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 0 auto 20px auto;
        }

        .step-item h3 { margin: 0 0 10px 0; font-size: 18px; font-weight: 700; }
        .step-item p { margin: 0; color: var(--text-muted); font-size: 14px; }

        /* 5. FAQ Section (Sanfona) */
        .faq-section {
            max-width: 800px;
            margin: 80px auto;
            padding: 0 20px;
        }

        .faq-item {
            border-bottom: 1px solid var(--border);
            padding: 15px 0;
        }

        .faq-item summary {
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .faq-item summary::-webkit-details-marker { display: none; }
        .faq-item summary::after { content: '➕'; font-size: 14px; }
        .faq-item[open] summary::after { content: '➖'; }
        .faq-item p { margin: 10px 0 0 0; color: var(--text-muted); font-size: 15px; }

        /* 6. Footer */
        .lp-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 40px 20px;
            text-align: center;
            font-size: 14px;
        }

        .lp-footer-links {
            margin-top: 15px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .lp-footer-link {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
        }
        .lp-footer-link:hover { color: #ffffff; }

        /* Responsividade */
        @media (max-width: 900px) {
            .hero-title { font-size: 36px; }
            .split-section { grid-template-columns: 1fr; margin-top: 20px; gap: 20px; }
            .card-parallel { padding: 30px 20px; }
            .lp-footer-links { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="lp-header">
        <div class="lp-nav">
            <a href="#" class="lp-logo">🚀 TalentoHub</a>
            <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" class="lp-btn-plataforma">Acessar Plataforma</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-container">
        <h1 class="hero-title">A maneira mais inteligente de conectar <span>Talento</span> e <span>Oportunidade</span></h1>
        <p class="hero-subtitle">Seja para contratar serviços locais de confiança ou para transformar suas habilidades em um negócio lucrativo, o seu lugar é aqui.</p>
    </section>

    <!-- Split Cards Section -->
    <main class="split-section">
        
        <!-- Bloco Contratante -->
        <div class="card-parallel">
            <div>
                <div class="card-tag tag-contratante">Para Contratantes</div>
                <h2 class="card-title">Encontre profissionais qualificados na sua região</h2>
                <p class="card-description">Esqueça as indicações incertas. No nosso ecossistema, você tem acesso imediato a especialistas prontos para atender suas demandas.</p>
                <ul class="benefit-list">
                    <li>✅ <b>Busca Inteligente:</b> Filtre instantaneamente por nome ou categoria profissional.</li>
                    <li>✅ <b>Avaliações Reais:</b> Analise a reputação por estrelas deixada por clientes anteriores.</li>
                    <li>✅ <b>Contratação Direta:</b> Visualize biografias detalhadas antes de fechar o serviço.</li>
                </ul>
            </div>
            <a href="<?php echo \App\Config\AppConfig::url('/profissionais'); ?>" class="btn-cta btn-contratar">
                🔍 Buscar Especialistas Agora
            </a>
        </div>

        <!-- Bloco Profissional -->
        <div class="card-parallel">
            <div>
                <div class="card-tag tag-profissional">Para Profissionais</div>
                <h2 class="card-title">Transforme suas habilidades em oportunidades reais</h2>
                <p class="card-description">Cadastre-se, destaque seu portfólio e receba propostas de clientes que valorizam o seu talento.</p>
                <ul class="benefit-list">
                    <li>✅ <b>Visibilidade Local:</b> Seja encontrado por contratantes na sua região.</li>
                    <li>✅ <b>Gestão de Portfólio:</b> Mostre seus projetos e conquistas de forma profissional.</li>
                    <li>✅ <b>Feedback Construtivo:</b> Receba avaliações que fortalecem sua reputação.</li>
                </ul>
            </div>
            <a href="<?php echo \App\Config\AppConfig::url('/login'); ?>" class="btn-cta btn-anunciar">
                💼 Anunciar Serviços Agora
            </a>
        </div>