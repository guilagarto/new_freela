<?php
// app/Views/cadastro.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/partials/header.php'; 
?>

<div class="container-cadastro" style="max-width: 450px; margin: 60px auto; padding: 0 15px; font-family: 'Segoe UI', sans-serif; box-sizing: border-box;">
    
    <div class="card-cadastro" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); box-sizing: border-box;">
        
        <h2 style="margin: 0 0 10px 0; font-size: 22px; color: #1e293b; font-weight: 700; text-align: center;">Criar Nova Conta</h2>
        <p style="margin: 0 0 25px 0; color: #64748b; font-size: 14px; text-align: center;">Cadastre-se rapidamente para começar a usar o TalentoHub.</p>

        <!-- ALERTA DE ERRO CASO EXISTA -->
        <?php if (isset($_SESSION['erro_auth'])): ?>
            <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 500; border: 1px solid #fca5a5;">
                ❌ <?php echo $_SESSION['erro_auth']; unset($_SESSION['erro_auth']); ?>
            </div>
        <?php endif; ?>

        <!-- FORMULÁRIO DE CADASTRO DIRECIONADO PARA O PROCESSAMENTO CORRETO -->
        <form action="./cadastrar" method="POST" style="display: flex; flex-direction: column; gap: 18px; margin: 0;">
            
            <!-- CAMPO: NOME -->
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="font-weight: 600; font-size: 13px; color: #475569;">Nome Completo:</label>
                <input type="text" name="name" required placeholder="Digite seu nome" style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
            </div>

            <!-- CAMPO: E-MAIL -->
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="font-weight: 600; font-size: 13px; color: #475569;">E-mail de Acesso:</label>
                <input type="email" name="email" required placeholder="seu@email.com" style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
            </div>

            <!-- CAMPO: SENHA -->
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="font-weight: 600; font-size: 13px; color: #475569;">Senha:</label>
                <input type="password" name="password" required placeholder="Crie uma senha forte" style="padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; background: #fff; color: #333;">
            </div>

            <!-- BOTÃO ENVIAR FORMULÁRIO -->
            <button type="submit" style="background: #4f46e5; color: #ffffff; padding: 14px; border: none; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15); margin-top: 5px;">
                🚀 Criar Conta Grátis
            </button>

            <p style="margin: 15px 0 0 0; font-size: 13px; color: #64748b; text-align: center;">
                Já possui uma conta? <a href="./login" style="color: #4f46e5; text-decoration: none; font-weight: 600;">Faça login aqui</a>
            </p>

        </form>
    </div>
</div>

<?php 
require_once __DIR__ . '/partials/footer.php'; 
?>
