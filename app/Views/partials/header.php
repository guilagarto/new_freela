<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecossistema de Talentos</title>
    <!-- Chamada dinâmica do CSS Externo -->
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>?v=1.5">
    
    <!-- CSS INTERNO DO MENU PARA EVITAR PROBLEMAS DE CACHE -->
   <style>
    /* Estilos base do Cabeçalho com Efeito Vidro Fosco Moderno */
    .main-header {
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        
        /* Fundo branco com 80% de opacidade (Transparência suave) */
        background: rgba(255, 255, 255, 0.85); 
        
        /* Efeito de desfoque nativo do navegador para o conteúdo que passa atrás */
        backdrop-filter: blur(10px); 
        -webkit-backdrop-filter: blur(10px); 
        
        border-bottom: 1px solid rgba(226, 232, 240, 0.6); 
        z-index: 99999; 
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        box-sizing: border-box;
        transition: background 0.3s ease;
    }

    .nav-container {
        max-width: 1200px; 
        margin: 0 auto; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 20px;
        position: relative;
    }

    .nav-logo {
        font-size: 20px; 
        font-weight: 700; 
        color: #1e1b4b; 
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Menu para Computador */
    .nav-menu {
        display: flex; 
        gap: 24px; 
        list-style: none; 
        margin: 0; 
        padding: 0;
    }

    .nav-link {
        color: #475569; 
        text-decoration: none; 
        font-size: 14px;
        font-weight: 600;
        transition: color 0.2s;
    }

    .nav-link:hover {
        color: #4f46e5;
    }

    /* Botão Hamburguer */
    .menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #1e1b4b;
        padding: 5px;
    }

    /* Ajuste de Margem Global Prático: 
       Aplica automaticamente um recuo em todas as páginas para descolar do topo fixo */
    body {
        margin: 0;
        padding-top: 85px !important; 
    }

    /* ========================================================================= */
    /* COMPORTAMENTO PARA CELULAR (MOBILE)                                       */
    /* ========================================================================= */
    @media (max-width: 768px) {
            /* ========================================================================= */
    /* COMPORTAMENTO PARA CELULAR (MOBILE)                                       */
    /* ========================================================================= */
    
    /* Configuração para o Header sumir suavemente ao rolar a página */
    header, .header-principal {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        background: #ffffff;
        transition: transform 0.3s ease-in-out !important;
    }

    /* Classe que o JavaScript vai injetar para ocultar o menu */
    .header-hidden {
        transform: translateY(-100%) !important;
    }

    @media (max-width: 768px) {
        /* CORREÇÃO DO ALINHAMENTO: Força a logo e as barrinhas a ficarem na mesma linha */
        header nav, .nav-container, .header-wrapper { 
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 10px 15px !important;
            width: 100%;
            box-sizing: border-box;
        }

        .menu-toggle {
            display: block;
            margin: 0 !important;
            padding: 5px !important;
            cursor: pointer;
        }

        /* Garante que o link da logo/foguete não quebre o alinhamento */
        .logo, .navbar-brand, header a {
            display: flex;
            align-items: center;
            margin: 0 !important;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 100%; 
            left: 0;
            width: 100%;
            /* Acompanha a transparência fosca no menu aberto mobile */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 0;
            gap: 0;
            max-height: 0; 
            overflow: hidden; 
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: max-height 0.3s ease-in-out, padding 0.3s ease-in-out;
        }

        .nav-menu.active {
            max-height: 300px; 
            padding: 10px 0;
        }

        .nav-menu li {
            width: 100%;
            text-align: center;
        }

        .nav-link {
            display: block;
            padding: 14px 20px;
            font-size: 16px; 
            border-bottom: 1px solid rgba(241, 245, 249, 0.8);
        }
        
        .nav-menu li:last-child .nav-link {
            border-bottom: none;
        }
       }
    }
</style>

</head>
<body>

<header class="main-header">
    <nav class="nav-container">
        <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" class="nav-logo">🚀 TalentoHub</a>
        
        <!-- Botão do Menu Hamburguer -->
        <button class="menu-toggle" id="mobile-menu-btn" aria-label="Abrir menu">☰</button>
        
        <!-- Lista de Links -->
        <ul id="nav-links-container" class="nav-menu">
    <!-- Link público padrão -->
    <li><a href="<?php echo \App\Config\AppConfig::url('/'); ?>" class="nav-link">Home</a></li>

    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- ========================================================================= -->
        <!-- LINKS EXCLUSIVOS PARA USUÁRIO LOGADO                                      -->
        <!-- ========================================================================= -->
        <li><a href="<?php echo \App\Config\AppConfig::url('/profissionais'); ?>" class="nav-link">Buscar Profissionais</a></li>
        <li><a href="<?php echo \App\Config\AppConfig::url('/chat'); ?>" class="nav-link">Mensagens</a></li>
        
        <!-- INDICADOR VISUAL EXIGIDO: NOME + STATUS LOGADO COM BOLINHA VERDE -->
        <!-- Localize essa linha no seu header.php e mude o href para apontar para a dashboard do freela -->
        <li style="display: flex; align-items: center; justify-content: center;">
            <a href="<?php echo \App\Config\AppConfig::url('/dashboard-freela'); ?>" class="nav-link" style="color: #4f46e5; font-weight: bold; display: inline-flex; align-items: center; gap: 6px;">
                <span style="width: 8px; height: 8px; background-color: #22c55e; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px #22c55e;"></span>
                👤 <?php echo explode(' ', $_SESSION['user_name'] ?? 'Usuário')[0]; ?> (Logado)
            </a>
        </li>

        
        <!-- OPÇÃO COMPLETA DE SAIR (LOGOUT) -->
        <li>
            <a href="<?php echo \App\Config\AppConfig::url('/sair'); ?>" class="nav-link" style="color: #dc3545; font-weight: 600;">
                🚪 Sair
            </a>
        </li>
        
    <?php else: ?>
        <!-- ========================================================================= -->
        <!-- LINKS EXIBIDOS APENAS SE ESTIVER DESLOGADO (VISITANTE NA LANDING PAGE)     -->
        <!-- ========================================================================= -->
        <li><a href="<?php echo \App\Config\AppConfig::url('/login'); ?>" class="nav-link">Entrar</a></li>
        <li>
            <a href="<?php echo \App\Config\AppConfig::url('/cadastro'); ?>" class="nav-link btn-cadastro-menu" style="background: #4f46e5; color: #ffffff; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Cadastrar
            </a>
        </li>
    <?php endif; ?>
</ul>

    </nav>
</header>

<!-- JavaScript para alternar o efeito de abrir/fechar -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const menuBtn = document.getElementById("mobile-menu-btn");
    const navLinks = document.getElementById("nav-links-container");

    if (menuBtn && navLinks) {
        menuBtn.addEventListener("click", function() {
            // Liga e desliga a classe 'active' que expande o menu
            navLinks.classList.toggle("active");
            
            // Alterna o ícone visual entre ☰ (abrir) e ✕ (fechar)
            if (navLinks.classList.contains("active")) {
                menuBtn.innerHTML = "✕";
            } else {
                menuBtn.innerHTML = "☰";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    // Captura o elemento header do projeto
    const headerElement = document.querySelector("header") || document.querySelector(".header-principal");
    if (!headerElement) return;

    let ultimoScrollTop = 0;

    window.addEventListener("scroll", () => {
        const scrollAtual = window.pageYOffset || document.documentElement.scrollTop;

        // Se rolar para baixo (mais de 40px), esconde. Se rolar para cima, mostra.
        if (scrollAtual > ultimoScrollTop && scrollAtual > 40) {
            headerElement.classList.add("header-hidden");
        } else {
            headerElement.classList.remove("header-hidden");
        }

        // Evita valores negativos em rolagens rápidas (comum no Safari/iOS)
        ultimoScrollTop = scrollAtual <= 0 ? 0 : scrollAtual;
    });
});


</script>
