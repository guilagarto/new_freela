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
        .menu-toggle {
            display: block;
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
</style>

</head>
<body>

<header class="main-header">
    <nav class="nav-container">
        <a href="<?php echo \App\Config\AppConfig::url('/'); ?>" class="nav-logo">🚀 TalentoHub</a>
        
        <!-- Botão do Menu Hamburguer -->
        <button class="menu-toggle" id="mobile-menu-btn" aria-label="Abrir menu">☰</button>
        
        <!-- Lista de Links -->
        <ul class="nav-menu" id="nav-links-container">
            <li><a href="<?php echo \App\Config\AppConfig::url('/'); ?>" class="nav-link">Home</a></li>
            <li><a href="<?php echo \App\Config\AppConfig::url('/profissionais'); ?>" class="nav-link">Buscar Profissionais</a></li>
            <li><a href="<?php echo \App\Config\AppConfig::url('/blog'); ?>" class="nav-link">Blog</a></li>
            <li><a href="<?php echo \App\Config\AppConfig::url('/contato'); ?>" class="nav-link">Contato</a></li>
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
</script>
