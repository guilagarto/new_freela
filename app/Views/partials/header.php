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
        /* Estilos base do Cabeçalho */
        .main-header {
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            background: #ffffff; 
            border-bottom: 1px solid #e2e8f0; 
            z-index: 99999; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            box-sizing: border-box;
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

        /* Menu para Computador (Desktop) */
        .nav-menu {
            display: flex; 
            gap: 24px; 
            list-style: none; 
            margin: 0; 
            padding: 0;
            transition: all 0.3s ease-in-out;
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

        /* Botão Hamburguer (Escondido no Computador) */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #1e1b4b;
            padding: 5px;
            focus: outline-none;
        }

        /* ========================================================================= */
        /* COMPORTAMENTO PARA CELULAR (MOBILE)                                       */
        /* ========================================================================= */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block; /* Mostra o botão hamburguer no celular */
            }

            .nav-menu {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%; /* Fixa logo abaixo da barra branca */
                left: 0;
                width: 100%;
                background: #ffffff;
                border-bottom: 1px solid #e2e8f0;
                padding: 0;
                gap: 0;
                max-height: 0; /* Começa fechado (escondido) */
                overflow: hidden; /* Esconde as letras quando fechado */
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            }

            /* Classe injetada pelo JS para dar o efeito de deslizar e abrir */
            .nav-menu.active {
                max-height: 300px; /* Expande suavemente até essa altura máxima */
                padding: 15px 0;
            }

            .nav-menu li {
                width: 100%;
                text-align: center;
            }

            .nav-link {
                display: block;
                padding: 12px 20px;
                font-size: 16px; /* Letras maiores no celular para facilitar o clique */
                border-bottom: 1px solid #f1f5f9;
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
