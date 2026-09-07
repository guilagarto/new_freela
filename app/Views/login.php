<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Entrar'; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container">
        <span class="badge-version">Acesso ao Sistema</span>
        <h1 class="main-title">Fazer Login</h1>
        <p class="description-text">Insira suas credenciais unificadas para acessar seus painéis.</p>

        <!-- Mensagens de Feedback vindas do Controller -->
        <?php if (isset($_SESSION['erro_auth'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro_auth']; unset($_SESSION['erro_auth']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['sucesso_auth'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['sucesso_auth']; unset($_SESSION['sucesso_auth']); ?></div>
        <?php endif; ?>

        <form action="<?php echo \App\Config\AppConfig::url('/login'); ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">E-mail Cadastrado</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="exemplo@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Sua Senha</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-action btn-primary" style="width: 100%;">Entrar na Plataforma</button>
        </form>

        <p class="auth-footer-link">
            Não tem uma conta? <a href="<?php echo \App\Config\AppConfig::url('/cadastrar'); ?>">Cadastre-se grátis</a>
        </p>
    </div>

</body>
</html>
