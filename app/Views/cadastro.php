<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? 'Criar Conta'; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Config\AppConfig::url('/css/style.css'); ?>">
</head>
<body>

    <div class="welcome-container">
        <span class="badge-version">Novo Cadastro</span>
        <h1 class="main-title">Criar sua Conta</h1>
        <p class="description-text">Uma única conta para contratar profissionais ou receber por projetos.</p>

        <!-- Mensagens de Erro vindas do Controller -->
        <?php if (isset($_SESSION['erro_auth'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro_auth']; unset($_SESSION['erro_auth']); ?></div>
        <?php endif; ?>

        <!-- Formulário de Envio -->
        <form action="<?php echo \App\Config\AppConfig::url('/cadastrar'); ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="name">Nome Completo</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Como quer ser chamado?" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail de Acesso</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="seu@email.com" required>
            </div>

            <div class="form-group">
                <label for="password">Crie uma Senha Segura</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
            </div>

            <div class="form-group">
                <label for="tipo_cadastro">Qual seu objetivo principal?</label>
                <select name="tipo_cadastro" id="tipo_cadastro" class="form-control" required>
                    <option value="cliente" selected>Contratar Freelancers (Postar Vagas)</option>
                    <option value="freelancer">Trabalhar como Freelancer (Buscar Vagas)</option>
                </select>
            </div>

            <button type="submit" class="btn-action btn-primary" style="width: 100%;">Finalizar Cadastro</button>
        </form>

        <p class="auth-footer-link">
            Já possui uma conta? <a href="<?php echo \App\Config\AppConfig::url('/login'); ?>">Fazer Login</a>
        </p>
    </div>

</body>
</html>
