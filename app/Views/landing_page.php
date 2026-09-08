<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conecte-se com os Melhores Profissionais | TalentoHub</title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>?v=1.3">
    <style>
        /* Estilos específicos e isolados para a Landing Page */
        .lp-body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #1e1b4b;
        }
        .lp-nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }
        .lp-logo {
            font-size: 22px;
            font-weight: 800;
            color: #4f46e5;
            text-decoration: none;
        }
        .lp-btn-entrar {
            background: #4f46e5;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }
        .lp-btn-entrar:hover {
            background: #4338ca;
        }
        .lp-hero {
            background: linear-gradient(135deg, #f5f3ff 0%, #e0e7ff 100%);
            padding: 100px 20px;
            text-align: center;
        }
        .lp-hero-title {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.2;
            max-width: 800px;
            margin: 0 auto 20px auto;
        }
        .lp-hero-subtitle {
            font-size: 18px;
            color: #475569;
            max-width: 600px;
            margin: 0 auto 40px auto;
            line-height: 1.6;
        }
        .lp-cta-button {
            display: inline-block;
            background: #4f46e5;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            padding: 16px 36px;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
            transition: transform 0.2s;
        }
        .lp-cta-button:hover {
            transform: translateY(-2px);
        }
        .lp-footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 40px 20px;
            text-align: center;
            margin-top: 80px;
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
        .lp-footer-link:hover {
            color: #ffffff;
        }
        @media (max-width: 768px) {
            .lp-hero-title { font-size: 32px; }
            .lp-footer-links { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body class="lp-body">

    <!-- Menu Simplificado da Landing Page (Apenas Logo e botão de Acesso ao Site Principal) -->
    <header style="border-bottom: 1px solid #e2e8f0;">
        <div class="lp-nav">
            <a href="#" class="lp-logo">🚀 TalentoHub</a>
            <!-- Direciona para a Home real do sistema -->
            <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" class="lp-btn-entrar">Acessar a Plataforma</a>
        </div>
    </header>

    <!-- Seção Principal de Conversão (Hero) -->
    <section class="lp-hero">
        <h1 class="lp-hero-title">Precisa de um profissional qualificado ou quer novos projetos?</h1>
        <p class="lp-hero-subtitle">Conectamos contratantes e profissionais de tecnologia, suporte e serviços em um único ecossistema seguro com avaliações reais.</p>
        
        <!-- O botão de ação principal joga o usuário direto para a tela de buscas que acabamos de criar -->
        <a href="<?php echo \App\Config\AppConfig::url('/profissionais'); ?>" class="lp-cta-button">
            🔍 Encontrar Profissionais Agora
        </a>
    </section>

    <!-- Rodapé Isolado com Links Obrigatórios -->
    <footer class="lp-footer">
        <p>&copy; <?php echo date('Y'); ?> TalentoHub. Todos os direitos reservados.</p>
        <div class="lp-footer-links">
            <a href="<?php echo \App\Config\AppConfig::url('/politica-de-privacidade'); ?>" class="lp-footer-link">Política de Privacidade</a>
            <a href="<?php echo \App\Config\AppConfig::url('/termos-de-uso'); ?>" class="lp-footer-link">Termos de Uso</a>
        </div>
    </footer>

</body>
</html>
